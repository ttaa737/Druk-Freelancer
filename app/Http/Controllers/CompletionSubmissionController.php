<?php

namespace App\Http\Controllers;

use App\Models\CompletionSubmission;
use App\Models\CompletionSubmissionAttachment;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class CompletionSubmissionController extends Controller
{
    /**
     * Show submission form for a contract
     */
    public function create(Contract $contract)
    {
        $this->authorize('submitCompletion', $contract);

        // Check if submission already exists
        $existingSubmission = CompletionSubmission::where('contract_id', $contract->id)->first();

        return view('completion.create', [
            'contract' => $contract,
            'existingSubmission' => $existingSubmission,
        ]);
    }

    /**
     * Store completion submission with attachments
     */
    public function store(Contract $contract, Request $request)
    {
        $this->authorize('submitCompletion', $contract);

        $validated = $request->validate([
            'submission_notes' => 'required|string|min:20|max:2000',
            'attachments' => 'required|array|min:1|max:10',
            'attachments.*.file' => 'required|file|max:10240', // 10MB per file
            'attachments.*.document_type' => 'required|in:evidence,report,deliverable,screenshot,video,other',
            'attachments.*.description' => 'nullable|string|max:500',
        ]);

        // Check if submission already exists
        $submission = CompletionSubmission::where('contract_id', $contract->id)
            ->where('status', '!=', CompletionSubmission::STATUS_REJECTED)
            ->first();

        if (!$submission) {
            $submission = CompletionSubmission::create([
                'contract_id' => $contract->id,
                'freelancer_id' => Auth::id(),
                'submission_notes' => $validated['submission_notes'],
                'status' => CompletionSubmission::STATUS_PENDING,
            ]);
        } else {
            $submission->update([
                'submission_notes' => $validated['submission_notes'],
                'status' => CompletionSubmission::STATUS_PENDING,
                'submitted_at' => now(),
                'rejection_reason' => null,
                'rejected_at' => null,
            ]);
        }

        // Process attachments
        foreach ($request->file('attachments') as $attachment) {
            $this->storeAttachment($submission, $attachment, $request);
        }

        // Update contract status
        $contract->completion_status = 'submitted';
        $contract->completion_submitted_at = now();
        $contract->save();

        // Send notification to job poster
        NotificationService::completionSubmitted($contract->poster, $submission);

        return response()->json([
            'success' => true,
            'message' => 'Completion evidence submitted successfully. Admin will review and verify.',
            'submission_id' => $submission->id,
            'redirect' => route('contract.show', $contract),
        ]);
    }

    /**
     * Store individual attachment
     */
    private function storeAttachment(CompletionSubmission $submission, $file, Request $request)
    {
        $disk = Storage::disk('private');
        $path = "completions/{$submission->contract_id}/";

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $disk->putFileAs($path, $file, $filename);

        CompletionSubmissionAttachment::create([
            'completion_submission_id' => $submission->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->input('attachments')[array_search($file, $request->file('attachments'))]['description'] ?? null,
            'document_type' => $request->input('attachments')[array_search($file, $request->file('attachments'))]['document_type'],
        ]);
    }

    /**
     * View submission details
     */
    public function show(CompletionSubmission $submission)
    {
        $this->authorize('view', $submission);

        return view('completion.show', ['submission' => $submission]);
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(CompletionSubmissionAttachment $attachment)
    {
        $this->authorize('download', $attachment);

        $disk = Storage::disk('private');

        if (!$disk->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        return $disk->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * List submissions for freelancer
     */
    public function mySubmissions()
    {
        $submissions = CompletionSubmission::where('freelancer_id', Auth::id())
            ->with('contract', 'attachments')
            ->latest()
            ->paginate(15);

        return view('completion.my-submissions', ['submissions' => $submissions]);
    }
}
