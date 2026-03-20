<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            true
        );
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
            in_array($proposal->status, ['accepted', 'rejected'])
        );
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
            true
        );
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
            true
        );
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
}
