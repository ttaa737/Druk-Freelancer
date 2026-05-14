<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompletionSubmission;
use App\Models\Contract;
use App\Services\PaymentProcessingService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCompletionController extends Controller
{
    private PaymentProcessingService $paymentService;

    public function __construct(PaymentProcessingService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware('admin');
    }

    /**
     * List all pending completion submissions
     */
    public function index()
    {
        $submissions = CompletionSubmission::with('contract', 'freelancer', 'attachments')
            ->latest('submitted_at')
            ->paginate(20);

        return view('admin.completions.index', ['submissions' => $submissions]);
    }

    /**
     * View submission details for verification
     */
    public function show(CompletionSubmission $submission)
    {
        $submission->load('contract', 'freelancer', 'attachments', 'verifiedBy');

        return view('admin.completions.show', ['submission' => $submission]);
    }

    /**
     * Verify and approve completion
     */
    public function verify(Request $request, CompletionSubmission $submission)
    {
        $validated = $request->validate([
            'verification_notes' => 'required|string|min:10|max:1000',
        ]);

        try {
            // Update submission status
            $submission->status = CompletionSubmission::STATUS_VERIFIED;
            $submission->verified_at = now();
            $submission->verified_by = auth()->id();
            $submission->save();

            // Process payment
            $paymentProcessed = $this->paymentService->processCompletionPayment($submission);

            if ($paymentProcessed) {
                // Update contract status
                $contract = $submission->contract;
                $contract->completion_status = 'verified';
                $contract->save();

                // Send notification to freelancer
                NotificationService::completionApproved($submission->freelancer, $submission);

                // Log the action
                Log::info('Completion verified and payment processed', [
                    'submission_id' => $submission->id,
                    'contract_id' => $contract->id,
                    'admin_id' => auth()->id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Completion verified successfully. Payment has been processed and transferred to accounts.',
                ]);
            } else {
                throw new \Exception('Payment processing failed');
            }
        } catch (\Exception $e) {
            Log::error('Completion verification failed', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing verification: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject completion submission
     */
    public function reject(Request $request, CompletionSubmission $submission)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:20|max:1000',
        ]);

        try {
            $submission->status = CompletionSubmission::STATUS_REJECTED;
            $submission->rejection_reason = $validated['rejection_reason'];
            $submission->rejected_at = now();
            $submission->verified_by = auth()->id();
            $submission->save();

            // Update contract status
            $contract = $submission->contract;
            $contract->completion_status = 'rejected';
            $contract->save();

            // Send notification to freelancer
            NotificationService::completionRejected($submission->freelancer, $submission);

            Log::info('Completion rejected', [
                'submission_id' => $submission->id,
                'contract_id' => $contract->id,
                'admin_id' => auth()->id(),
                'reason' => $validated['rejection_reason'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submission rejected. Freelancer will be notified to resubmit.',
            ]);
        } catch (\Exception $e) {
            Log::error('Rejection failed', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error rejecting submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Dashboard statistics
     */
    public function stats()
    {
        $stats = [
            'pending' => CompletionSubmission::where('status', CompletionSubmission::STATUS_PENDING)->count(),
            'verified' => CompletionSubmission::where('status', CompletionSubmission::STATUS_VERIFIED)->count(),
            'rejected' => CompletionSubmission::where('status', CompletionSubmission::STATUS_REJECTED)->count(),
            'payment_processed' => CompletionSubmission::where('status', CompletionSubmission::STATUS_PAYMENT_PROCESSED)->count(),
            'recent' => CompletionSubmission::with('freelancer', 'contract')
                ->latest('submitted_at')
                ->limit(5)
                ->get(),
        ];

        return view('admin.completions.stats', $stats);
    }
}
