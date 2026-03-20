<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // public listing
    }

    public function view(?User $user, Job $job): bool
    {
        return true; // public
    }

    public function create(User $user): bool
    {
        return $user->hasRole('job_poster') || $user->isAdmin();
    }

    public function update(User $user, Job $job): bool
    {
        return $user->isAdmin() || $job->poster_id === $user->id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->isAdmin() || $job->poster_id === $user->id;
    }

    /** A freelancer may submit a proposal on this job. */
    public function propose(User $user, Job $job): bool
    {
        return $user->hasRole('freelancer')
            && $job->status === 'open'
            && $job->poster_id !== $user->id;
    }
}
