<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Milestone;
use App\Services\AuditLogService;
use App\Services\EscrowService;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    public function __construct(
        private EscrowService $escrow,
        private InvoiceService $invoice
    ) {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Freelancer submits milestone work.
     */
    public function submit(Request $request, Milestone $milestone)
    {
        abort_unless(Auth::id() === $milestone->contract->freelancer_id, 403);
        abort_unless(in_array($milestone->status, ['pending', 'revision', 'in_progress']), 422, 'This milestone cannot be submitted in its current state.');

        $request->validate([
            'work_description' => 'required|string|min:20|max:3000',
            'attachments'      => 'nullable|array|max:5',
            'attachments.*'    => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:20480',
        ]);

        $milestone->update([
            'status'           => 'submitted',
            'work_description' => $request->work_description,
            'submitted_at'     => now(),
        ]);

        // Upload attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('milestone-attachments', 'public');
                $milestone->attachments()->create([
                    'file_path'       => $path,
                    'original_name'   => $file->getClientOriginalName(),
                    'file_type'       => $file->getClientMimeType(),
                    'file_size'       => $file->getSize(),
                    'uploaded_by_role' => 'freelancer',
                ]);
            }
        }

        NotificationService::milestoneSubmitted($milestone->contract->poster, $milestone);
        AuditLogService::log('milestone.submitted', $milestone, notes: $milestone->title);

        return back()->with('success', 'Work submitted! The job poster will review your submission.');
    }

    /**
     * Job poster approves milestone and releases payment.
     */
    public function approve(Milestone $milestone)
    {
        abort_unless(Auth::id() === $milestone->contract->poster_id, 403);
        abort_unless($milestone->status === 'submitted', 422, 'Milestone must be submitted before approval.');

        try {
            $transaction = $this->escrow->releaseMilestonePayment($milestone);
            $this->invoice->generateMilestoneInvoice($milestone->fresh());

            NotificationService::paymentReleased($milestone->contract->freelancer, $milestone);
            AuditLogService::log('milestone.approved', $milestone, notes: "Nu. {$milestone->amount} released");

            return back()->with('success', "Milestone approved and Nu. " . number_format($milestone->amount * 0.9, 2) . " released to the freelancer!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Job poster requests revision on a milestone.
     */
    public function requestRevision(Request $request, Milestone $milestone)
    {
        abort_unless(Auth::id() === $milestone->contract->poster_id, 403);
        abort_unless($milestone->status === 'submitted', 422);

        $request->validate(['feedback' => 'required|string|min:20|max:2000']);

        $milestone->update(['status' => 'revision']);

        NotificationService::send(
            $milestone->contract->freelancer,
            'revision_requested',
            'Revision Requested',
            "The job poster has requested a revision for milestone: {$milestone->title}. Feedback: {$request->feedback}",
            ['milestone_id' => $milestone->id, 'contract_id' => $milestone->contract_id]
        );

        AuditLogService::log('milestone.revision_requested', $milestone, notes: $request->feedback);

        return back()->with('success', 'Revision requested. The freelancer has been notified.');
    }
}

