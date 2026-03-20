<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;

class ProposalPolicy
{
    public function view(User $user, Proposal $proposal): bool
    {
        return $user->isAdmin()
            || $proposal->freelancer_id === $user->id
            || $proposal->job->poster_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('freelancer');
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $proposal->freelancer_id === $user->id && $proposal->status === 'pending';
    }

    public function withdraw(User $user, Proposal $proposal): bool
    {
        return $proposal->freelancer_id === $user->id && $proposal->status === 'pending';
    }

    public function award(User $user, Proposal $proposal): bool
    {
        return $proposal->job->poster_id === $user->id && $proposal->status === 'pending';
    }
}
