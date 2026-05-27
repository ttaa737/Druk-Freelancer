<?php

namespace App\Http\Controllers;

use App\Models\CompletionSubmission;
use App\Models\CompletionSubmissionAttachment;
use App\Models\Contract;
use App\Services\NotificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            // Replace previous files so admin/poster always review the latest evidence set.
            foreach ($submission->attachments as $existingAttachment) {
                Storage::disk('local')->delete($existingAttachment->file_path);
            }
            $submission->attachments()->delete();
        }

        // Process attachments
        foreach ($validated['attachments'] as $index => $attachmentMeta) {
            $file = $request->file("attachments.{$index}.file");
            if (!$file) {
                continue;
            }

            $this->storeAttachment(
                $submission,
                $file,
                $attachmentMeta['document_type'],
                $attachmentMeta['description'] ?? null
            );
        }

        // Update contract status
        $contract->completion_status = 'submitted';
        $contract->completion_submitted_at = now();
        $contract->save();

        // Notify poster and admins that review is required.
        NotificationService::completionSubmitted($contract->poster, $submission);
        NotificationService::completionSubmittedToAdmins($submission);

        return response()->json([
            'success' => true,
            'message' => 'Completion evidence submitted successfully. Job poster and admin have been notified for review.',
            'submission_id' => $submission->id,
            'redirect' => route('contracts.show', $contract),
        ]);
    }

    /**
     * Store individual attachment
     */
    private function storeAttachment(
        CompletionSubmission $submission,
        UploadedFile $file,
        string $documentType,
        ?string $description
    ): void
    {
        $disk = Storage::disk('local');
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
            'description' => $description,
            'document_type' => $documentType,
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

        $disk = Storage::disk('local');

        if (!$disk->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        // If inline preview requested, stream the file for inline display (images, PDFs, video)
        if (request()->boolean('inline')) {
            $fullPath = $disk->path($attachment->file_path);
            return response()->file($fullPath, [
                'Content-Type' => $attachment->file_type,
            ]);
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
