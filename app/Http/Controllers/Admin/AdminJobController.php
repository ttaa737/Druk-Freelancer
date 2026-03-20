<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminJobController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Job::with(['poster.profile', 'category'])->withTrashed();

        if ($request->filled('status')) {
            $query->withoutTrashed()->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        $job->load(['poster.profile', 'category', 'skills', 'proposals.freelancer.profile']);

        return view('admin.jobs.show', compact('job'));
    }

    /** Toggle featured status. */
    public function toggleFeatured(Job $job)
    {
        $job->update(['is_featured' => !$job->is_featured]);
        AuditLogService::log('job.' . ($job->is_featured ? 'featured' : 'unfeatured'), $job);

        return back()->with('success', 'Job featured status updated.');
    }

    /** Soft-delete / moderate a job. */
    public function moderate(Request $request, Job $job)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $job->update(['status' => 'closed']);
        $job->delete();

        AuditLogService::log('job.moderated', $job, notes: $request->reason);

        return redirect()->route('admin.jobs.index')->with('success', 'Job closed and hidden from listings.');
    }

    /** Restore a soft-deleted job. */
    public function restore(int $id)
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->restore();
        $job->update(['status' => 'open']);

        AuditLogService::log('job.restored', $job);

        return back()->with('success', 'Job restored.');
    }
}

