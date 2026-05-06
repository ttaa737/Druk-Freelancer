<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('verified')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Job::with(['category', 'poster.profile', 'skills'])
                    ->whereIn('status', ['open', 'draft'])
                    ->where(function ($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    });

        // Filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('dzongkhag')) {
            $query->where(function ($q) use ($request) {
                $q->where('dzongkhag', $request->dzongkhag)->orWhere('remote_ok', true);
            });
        }
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }

        $sortMap = [
            'newest'   => ['created_at', 'desc'],
            'oldest'   => ['created_at', 'asc'],
            'budget_h' => ['budget_max', 'desc'],
            'budget_l' => ['budget_min', 'asc'],
        ];
        [$sortCol, $sortDir] = $sortMap[$request->sort ?? 'newest'];
        $query->orderBy($sortCol, $sortDir);

        $jobs = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $dzongkhags = Profile::DZONGKHAGS;

        return view('jobs.index', compact('jobs', 'categories', 'dzongkhags'));
    }

    public function create()
    {
        $this->authorize('create', Job::class);
        $categories = Category::where('is_active', true)->get();
        $skills = Skill::where('is_active', true)->get();
        $dzongkhags = Profile::DZONGKHAGS;

        return view('jobs.create', compact('categories', 'skills', 'dzongkhags'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);
        // Require account verification before creating a job
        if (Auth::user()->verification_status !== 'verified') {
            return redirect()->to(route('profile.edit') . '#tab-docs')
                             ->with('warning', 'Please verify your account before posting jobs.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'required|string|min:100|max:10000',
            'requirements'     => 'nullable|string|max:5000',
            'type'             => 'required|in:fixed,hourly,milestone',
            'budget_min'       => 'nullable|numeric|min:300|max:500000',
            'budget_max'       => 'nullable|numeric|min:300|max:500000|gt:budget_min',
            'duration_days'    => 'nullable|integer|min:1|max:365',
            'experience_level' => 'nullable|in:entry,intermediate,expert',
            'dzongkhag'        => 'nullable|string',
            'remote_ok'        => 'boolean',
            'skills'           => 'nullable|array|max:10',
            'skills.*'         => 'exists:skills,id',
            'attachments'      => 'nullable|array|max:5',
            'attachments.*'    => 'file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'temp_attachments' => 'nullable|array|max:5',
            'temp_attachments.*' => 'string',
            'temp_attachment_names' => 'nullable|array|max:5',
            'temp_attachment_names.*' => 'string',
            'deadline'         => 'nullable|date_format:d/m/Y|after:today',
        ]);

        $tempAttachments = $validated['temp_attachments'] ?? [];
        $tempAttachmentNames = $validated['temp_attachment_names'] ?? [];
        unset($validated['temp_attachments']);
        unset($validated['temp_attachment_names']);

        $job = Job::create([
            ...$validated,
            'poster_id' => Auth::id(),
            'slug'      => Str::slug($validated['title']) . '-' . Str::random(6),
            'status'    => 'open',
        ]);

        if (!empty($validated['skills'])) {
            $job->skills()->sync($validated['skills']);
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('job-attachments', 'public');
                $job->attachments()->create([
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type'     => $file->getClientMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }

        // Finalize temporary attachments uploaded before submit
        foreach ($tempAttachments as $index => $tempPath) {
            if (!Storage::disk('public')->exists($tempPath)) {
                continue;
            }

            $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
            $finalPath = 'job-attachments/' . $job->id . '/' . Str::random(24) . ($extension ? '.' . $extension : '');
            Storage::disk('public')->makeDirectory(dirname($finalPath));
            Storage::disk('public')->move($tempPath, $finalPath);

            $job->attachments()->create([
                'file_path'     => $finalPath,
                'original_name' => $tempAttachmentNames[$index] ?? basename($tempPath),
                'file_type'     => Storage::disk('public')->mimeType($finalPath),
                'file_size'     => Storage::disk('public')->size($finalPath),
            ]);
        }

        AuditLogService::log('job.created', $job, notes: "Job posted: {$job->title}");

        return redirect()->route('jobs.show', $job->slug)
                         ->with('success', 'Your job has been posted successfully!');
    }

    public function show(string $slug)
    {
        $job = Job::with([
            'category', 'poster.profile', 'skills', 'attachments',
            'proposals' => fn($q) => $q->where('freelancer_id', Auth::id()),
        ])->where('slug', $slug)->firstOrFail();

        $job->increment('views_count');

        $alreadyApplied = Auth::check()
            ? $job->proposals->isNotEmpty()
            : false;

        $similarJobs = Job::with('category')
                          ->where('category_id', $job->category_id)
                          ->where('id', '!=', $job->id)
                          ->open()
                          ->limit(4)
                          ->get();

        return view('jobs.show', compact('job', 'alreadyApplied', 'similarJobs'));
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        $categories = Category::where('is_active', true)->get();
        $skills = Skill::where('is_active', true)->get();
        $dzongkhags = Profile::DZONGKHAGS;

        return view('jobs.edit', compact('job', 'categories', 'skills', 'dzongkhags'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'required|string|min:100',
            'requirements'     => 'nullable|string',
            'type'             => 'required|in:fixed,hourly,milestone',
            'budget_min'       => 'nullable|numeric|min:300|max:500000',
            'budget_max'       => 'nullable|numeric|min:300|max:500000',
            'duration_days'    => 'nullable|integer|min:1',
            'experience_level' => 'nullable|in:entry,intermediate,expert',
            'dzongkhag'        => 'nullable|string',
            'remote_ok'        => 'boolean',
            'skills'           => 'nullable|array',
            'skills.*'         => 'exists:skills,id',
            'deadline'         => 'nullable|date_format:d/m/Y|after:today',
        ]);

        $oldValues = $job->toArray();
        $job->update($validated);

        if (isset($validated['skills'])) {
            $job->skills()->sync($validated['skills']);
        }

        AuditLogService::log('job.updated', $job, $oldValues, $job->fresh()->toArray());

        return redirect()->route('jobs.show', $job->slug)
                         ->with('success', 'Job updated successfully.');
    }

    public function uploadTempAttachment(Request $request)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('attachment');
        $tempName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $tempPath = $file->storeAs('job-attachments/tmp/' . Auth::id(), $tempName, 'public');

        return response()->json([
            'path' => $tempPath,
            'name' => $file->getClientOriginalName(),
            'url'  => Storage::disk('public')->url($tempPath),
            'size' => $file->getSize(),
        ]);
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);

        if ($job->contracts()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete a job with active contracts.');
        }

        AuditLogService::log('job.deleted', $job);
        $job->delete();

        return redirect()->route('jobs.index')
                         ->with('success', 'Job deleted successfully.');
    }

    /**
     * My posted jobs (poster only).
     */
    public function myJobs()
    {
        $jobs = Job::with(['category', 'proposals', 'contracts'])
                   ->where('poster_id', Auth::id())
                   ->latest()
                   ->paginate(10);

        return view('jobs.my-jobs', compact('jobs'));
    }
}

