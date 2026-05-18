<?php

namespace App\Policies;

use App\Models\CompletionSubmissionAttachment;
use App\Models\User;

class CompletionSubmissionAttachmentPolicy
{
    /**
     * Only users involved in the contract (or admin) can download evidence.
     */
    public function download(User $user, CompletionSubmissionAttachment $attachment): bool
    {
        $submission = $attachment->completionSubmission;

        return $user->id === $submission->freelancer_id
            || $user->id === $submission->contract->poster_id
            || $user->isAdmin()
            || $user->hasRole('admin');
    }
}
