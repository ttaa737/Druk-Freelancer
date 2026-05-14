<?php

namespace App\Policies;

use App\Models\CompletionSubmission;
use App\Models\CompletionSubmissionAttachment;
use App\Models\User;

class CompletionSubmissionPolicy
{
    /**
     * Only freelancer can submit
     */
    public function submitCompletion(User $user, $contract): bool
    {
        return $user->id === $contract->freelancer_id;
    }

    /**
     * Only relevant users can view
     */
    public function view(User $user, CompletionSubmission $submission): bool
    {
        // Freelancer, poster, or admin can view
        return $user->id === $submission->freelancer_id ||
               $user->id === $submission->contract->poster_id ||
               $user->is_admin ||
               $user->hasRole('admin');
    }

    /**
     * Only admin can verify
     */
    public function verify(User $user): bool
    {
        return $user->is_admin || $user->hasRole('admin');
    }

    /**
     * Only admin can reject
     */
    public function reject(User $user): bool
    {
        return $user->is_admin || $user->hasRole('admin');
    }
}

class CompletionSubmissionAttachmentPolicy
{
    /**
     * Only authorized users can download
     */
    public function download(User $user, CompletionSubmissionAttachment $attachment): bool
    {
        $submission = $attachment->completionSubmission;

        return $user->id === $submission->freelancer_id ||
               $user->id === $submission->contract->poster_id ||
               $user->is_admin ||
               $user->hasRole('admin');
    }
}
