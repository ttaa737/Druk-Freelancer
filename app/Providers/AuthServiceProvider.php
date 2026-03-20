<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\Job;
use App\Models\Proposal;
use App\Policies\ContractPolicy;
use App\Policies\JobPolicy;
use App\Policies\ProposalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Job::class      => JobPolicy::class,
        Contract::class => ContractPolicy::class,
        Proposal::class => ProposalPolicy::class,
    ];

    public function boot(): void
    {
        // Admins can do anything
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}

