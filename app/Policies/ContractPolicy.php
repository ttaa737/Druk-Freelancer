<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function view(User $user, Contract $contract): bool
    {
        return $user->isAdmin()
            || $contract->poster_id === $user->id
            || $contract->freelancer_id === $user->id;
    }

    public function fundEscrow(User $user, Contract $contract): bool
    {
        return $contract->poster_id === $user->id
            && in_array($contract->status, ['pending', 'active'])
            && $contract->escrow_funded_at === null;
    }

    public function sign(User $user, Contract $contract): bool
    {
        return ($contract->poster_id === $user->id || $contract->freelancer_id === $user->id)
            && $contract->status === 'pending';
    }

    public function cancel(User $user, Contract $contract): bool
    {
        return $user->isAdmin()
            || (($contract->poster_id === $user->id || $contract->freelancer_id === $user->id)
                && in_array($contract->status, ['pending', 'active']));
    }
}
