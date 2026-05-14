<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use App\Mail\ProposalAcceptedMail;
use App\Mail\ProposalRejectedMail;
use App\Mail\ProposalShortlistedMail;
use App\Mail\NewProposalReceivedMail;
use App\Mail\VerificationApprovedMail;
use App\Mail\VerificationRejectedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send a platform notification (database + optional email).
     */
    public static function send(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $sendEmail = false
    ): void {
        try {
            // Store in database notifications
            $user->notifications()->create([
                'type'       => $type,
                'title'      => $title,
                'body'       => $message,
                'data'       => array_merge([
                    'title' => $title,
                    'message' => $message,
                    'icon' => $data['icon'] ?? 'bell',
                    'url' => $data['url'] ?? null,
                ], $data),
                'icon'       => $data['icon'] ?? 'bell',
                'action_url' => $data['url'] ?? null,
                'is_read'    => false,
                'read_at'    => null,
                'channel'    => 'in_app',
            ]);

            if ($sendEmail) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\PlatformNotificationMail($user, $title, $message));
                } catch (\Exception $e) {
                    Log::error("Notification email failed for user {$user->id}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to send notification to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify about new proposal received.
     */
    public static function newProposalReceived(User $poster, $proposal): void
    {
        self::send($poster, 'new_proposal', 'New Proposal Received',
            "You have received a new proposal from {$proposal->freelancer->name} for your job: {$proposal->job->title}",
            ['proposal_id' => $proposal->id, 'job_id' => $proposal->job_id],
            false // Will send custom email below
        );
        
        // Send beautiful custom email
        try {
            Mail::to($poster->email)->send(new NewProposalReceivedMail($proposal));
        } catch (\Exception $e) {
            Log::error("New proposal email failed for poster {$poster->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify freelancer about proposal status change.
     */
    public static function proposalStatusChanged(User $freelancer, $proposal): void
    {
        $statusMessages = [
            'shortlisted' => 'Congratulations! Your proposal has been shortlisted.',
            'accepted'    => '🎉 Your proposal has been accepted! A contract will be created.',
            'rejected'    => 'Your proposal was not selected for this project.',
        ];
        self::send($freelancer, 'proposal_status', 'Proposal Update',
            ($statusMessages[$proposal->status] ?? "Your proposal status changed to: {$proposal->status}") . " (Job: {$proposal->job->title})",
            ['proposal_id' => $proposal->id],
            false // Will send custom email below
        );
        
        // Send beautiful custom email based on status
        if (in_array($proposal->status, ['accepted', 'rejected', 'shortlisted'])) {
            try {
                $mail = match ($proposal->status) {
                    'accepted' => new ProposalAcceptedMail($proposal),
                    'rejected' => new ProposalRejectedMail($proposal),
                    'shortlisted' => new ProposalShortlistedMail($proposal),
                };
                Mail::to($freelancer->email)->send($mail);
            } catch (\Exception $e) {
                Log::error("Proposal status email failed for freelancer {$freelancer->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notify about new contract.
     */
    public static function contractCreated(User $freelancer, $contract): void
    {
        self::send($freelancer, 'contract_created', 'New Contract Created',
            "A contract #{$contract->contract_number} has been created for job: {$contract->job->title}. Please review and sign.",
            ['contract_id' => $contract->id],
            true
        );
    }

    /**
     * Notify about milestone submission.
     */
    public static function milestoneSubmitted(User $poster, $milestone): void
    {
        self::send($poster, 'milestone_submitted', 'Milestone Submitted for Review',
            "Freelancer has submitted work for milestone: {$milestone->title}. Please review and approve.",
            ['milestone_id' => $milestone->id, 'contract_id' => $milestone->contract_id],
            true
        );
    }

    /**
     * Notify about payment released.
     */
    public static function paymentReleased(User $freelancer, $milestone): void
    {
        $amount = 'Nu. ' . number_format($milestone->amount - ($milestone->amount * 0.10), 2);
        self::send($freelancer, 'payment_released', 'Payment Released! 💰',
            "{$amount} has been released to your wallet for milestone: {$milestone->title}",
            ['milestone_id' => $milestone->id],
            true
        );
    }

    /**
     * Notify about new message.
     */
    public static function newMessage(User $recipient, Message $message): void
    {
        if (!$recipient) {
            return;
        }
        $message->load('sender');
        self::send($recipient, 'new_message', 'New Message',
            "You have a new message from {$message->sender->name}",
            ['conversation_id' => $message->conversation_id]
        );
    }

    /**
     * Notify about dispute update.
     */
    public static function disputeUpdate(User $user, $dispute, string $updateMessage): void
    {
        self::send($user, 'dispute_update', "Dispute Case #{$dispute->case_number} Update",
            $updateMessage,
            ['dispute_id' => $dispute->id],
            true
        );
    }

    /**
     * Notify admin about new user registration needing verification.
     */
    public static function adminNewVerificationRequest(User $newUser): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::send($admin, 'verification_request', 'New Verification Request',
                "{$newUser->name} has submitted verification documents for review.",
                ['user_id' => $newUser->id],
                true
            );
        }
    }

    /**
     * Notify user that their verification document was approved.
     */
    public static function verificationApproved(User $user, string $documentType): void
    {
        $docTypeLabel = match($documentType) {
            'cid' => 'CID',
            'license' => 'Professional License',
            'brn' => 'Business Registration',
            'education' => 'Education Certificate',
            'tax_certificate' => 'Tax Clearance Certificate',
            default => ucfirst($documentType)
        };

        self::send($user, 'verification_approved', 'Document Verified! ✅',
            "Your {$docTypeLabel} has been verified successfully. Your account credibility has been enhanced.",
            ['document_type' => $documentType],
            false // Will send custom email below
        );

        // Send beautiful custom email
        try {
            Mail::to($user->email)->send(new VerificationApprovedMail($user, $documentType));
        } catch (\Exception $e) {
            Log::error("Verification approved email failed for user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify user that their verification document was rejected.
     */
    public static function verificationRejected(User $user, string $documentType, string $reason): void
    {
        $docTypeLabel = match($documentType) {
            'cid' => 'CID',
            'license' => 'Professional License',
            'brn' => 'Business Registration',
            'education' => 'Education Certificate',
            'tax_certificate' => 'Tax Clearance Certificate',
            default => ucfirst($documentType)
        };

        self::send($user, 'verification_rejected', 'Document Rejected',
            "Your {$docTypeLabel} was rejected. Reason: {$reason}. Please upload a valid document.",
            ['document_type' => $documentType, 'reason' => $reason],
            false // Will send custom email below
        );

        // Send beautiful custom email
        try {
            Mail::to($user->email)->send(new VerificationRejectedMail($user, $documentType, $reason));
        } catch (\Exception $e) {
            Log::error("Verification rejected email failed for user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify user that their account is now fully verified.
     */
    public static function accountVerified(User $user): void
    {
        self::send($user, 'account_verified', 'Account Verified! 🎉',
            "Congratulations! Your account is now verified. You'll appear more trustworthy to clients and have access to premium features.",
            [],
            true
        );
    }

    /**
     * Notify user about incomplete verification (still need more documents).
     */
    public static function verificationIncomplete(User $user, array $missingDocs): void
    {
        $docList = implode(', ', $missingDocs);
        $roleText = match($user->role) {
            'job_poster' => 'As a job poster',
            'freelancer' => 'As a freelancer',
            default => 'To fully verify your account'
        };

        self::send($user, 'verification_incomplete', 'More Documents Needed',
            "{$roleText}, you still need to submit: {$docList}. Please upload these documents to get fully verified.",
            ['missing_documents' => $missingDocs],
            true
        );
    }

    /**
     * Notify job poster when freelancer submits completion.
     */
    public static function completionSubmitted(User $poster, $submission): void
    {
        self::send($poster, 'completion_submitted', 'Work Submitted for Review',
            "Your freelancer has submitted their completed work for contract #{$submission->contract->contract_number}. Please review at your convenience.",
            ['submission_id' => $submission->id, 'contract_id' => $submission->contract_id],
            false // Will send custom email below
        );

        try {
            Mail::to($poster->email)->send(new \App\Mail\CompletionSubmittedMail($submission));
        } catch (\Exception $e) {
            Log::error("Completion submitted email failed for poster {$poster->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify freelancer when completion is verified/approved.
     */
    public static function completionApproved(User $freelancer, $submission): void
    {
        self::send($freelancer, 'completion_approved', 'Work Approved & Payment Processing',
            "Your work submission has been approved! Payment of Nu. " . number_format($submission->contract->freelancer_amount, 2) . " is being processed to your wallet.",
            ['submission_id' => $submission->id, 'contract_id' => $submission->contract_id],
            false // Will send custom email below
        );

        try {
            Mail::to($freelancer->email)->send(new \App\Mail\CompletionApprovedMail($submission));
        } catch (\Exception $e) {
            Log::error("Completion approved email failed for freelancer {$freelancer->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify freelancer when completion is rejected.
     */
    public static function completionRejected(User $freelancer, $submission): void
    {
        self::send($freelancer, 'completion_rejected', 'Resubmission Required',
            "Your work submission requires revisions. Feedback: " . Str::limit($submission->rejection_reason ?? 'See your dashboard for details', 100),
            ['submission_id' => $submission->id, 'contract_id' => $submission->contract_id],
            false // Will send custom email below
        );

        try {
            Mail::to($freelancer->email)->send(new \App\Mail\CompletionRejectedMail($submission));
        } catch (\Exception $e) {
            Log::error("Completion rejected email failed for freelancer {$freelancer->id}: " . $e->getMessage());
        }
    }
}
