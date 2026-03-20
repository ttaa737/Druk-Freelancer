@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Welcome back, {{ $user->name }} <span style="font-size:1.2rem"></span></h5>
        <span class="text-muted small">Manage your jobs, contracts and talent here.</span>
    </div>
    <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm px-3">
        <i class="fa fa-plus me-1"></i> Post a New Job
    </a>
</div>

{{--  Stats row  --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 flex-shrink-0" style="background:#fff3e0">
                        <i class="fa fa-briefcase fa-lg" style="color:var(--druk-orange)"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1">{{ $stats['active_jobs'] }}</div>
                        <div class="text-muted small">Active Jobs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #3b82f6">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 flex-shrink-0" style="background:#eff6ff">
                        <i class="fa fa-inbox fa-lg" style="color:#3b82f6"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1">{{ $stats['pending_proposals'] }}</div>
                        <div class="text-muted small">New Proposals</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #10b981">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 flex-shrink-0" style="background:#ecfdf5">
                        <i class="fa fa-file-contract fa-lg" style="color:#10b981"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1">{{ $stats['active_contracts'] }}</div>
                        <div class="text-muted small">Active Contracts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card h-100" style="border-left:4px solid #8b5cf6">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 flex-shrink-0" style="background:#f5f3ff">
                        <i class="fa fa-check-double fa-lg" style="color:#8b5cf6"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 lh-1">{{ $stats['completed_contracts'] }}</div>
                        <div class="text-muted small">Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  Wallet + Quick actions  --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card h-100" style="background:linear-gradient(135deg,var(--druk-blue),#2d5a96);color:#fff;border:none">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa fa-wallet fa-lg opacity-75"></i>
                    <span class="fw-semibold">My Wallet</span>
                </div>
                <div class="mb-2">
                    <div class="opacity-75 small">Available Balance</div>
                    <div class="fw-bold" style="font-size:1.5rem">Nu. {{ number_format($stats['available_balance']) }}</div>
                </div>
                <div class="d-flex gap-3 text-center mb-3">
                    <div>
                        <div class="small opacity-75">In Escrow</div>
                        <div class="fw-semibold small">Nu. {{ number_format($stats['escrow_balance']) }}</div>
                    </div>
                    <div class="border-start border-white opacity-50"></div>
                    <div>
                        <div class="small opacity-75">Total Spent</div>
                        <div class="fw-semibold small">Nu. {{ number_format($stats['total_spent']) }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('wallet.deposit.form') }}" class="btn btn-sm btn-light flex-grow-1 fw-semibold">
                        <i class="fa fa-plus me-1"></i>Deposit
                    </a>
                    <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-outline-light flex-grow-1 fw-semibold">
                        Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Quick Actions</h6>
                <div class="row g-2">
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('jobs.create') }}" class="btn btn-outline-secondary w-100 py-2 d-flex flex-column align-items-center gap-1 text-decoration-none">
                            <i class="fa fa-plus-circle" style="color:var(--druk-orange)"></i>
                            <small>Post Job</small>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('jobs.my') }}" class="btn btn-outline-secondary w-100 py-2 d-flex flex-column align-items-center gap-1 text-decoration-none">
                            <i class="fa fa-briefcase" style="color:#3b82f6"></i>
                            <small>My Jobs</small>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary w-100 py-2 d-flex flex-column align-items-center gap-1 text-decoration-none">
                            <i class="fa fa-file-contract" style="color:#10b981"></i>
                            <small>Contracts</small>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary w-100 py-2 d-flex flex-column align-items-center gap-1 text-decoration-none">
                            <i class="fa fa-comments" style="color:var(--druk-gold)"></i>
                            <small>Messages</small>
                        </a>
                    </div>
                </div>
                @if($stats['pending_proposals'] > 0)
                <div class="alert alert-info py-2 px-3 mt-3 mb-0 d-flex align-items-center gap-2" style="font-size:.82rem">
                    <i class="fa fa-bell-ring"></i>
                    <div>You have <strong>{{ $stats['pending_proposals'] }}</strong> unread proposal(s) awaiting your review.</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{--  My Jobs + Active Contracts  --}}
<div class="row g-3 mb-4">
    {{-- My Jobs --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                <span><i class="fa fa-briefcase me-2" style="color:var(--druk-orange)"></i>My Job Listings</span>
                <a href="{{ route('jobs.my') }}" class="btn btn-sm btn-link p-0 text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentJobs as $job)
                <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between gap-2">
                    <div class="overflow-hidden">
                        <a href="{{ route('jobs.show', $job->slug) }}" class="fw-semibold small text-dark text-decoration-none d-block text-truncate">
                            {{ $job->title }}
                        </a>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $job->category?->name }} &bull; Posted {{ $job->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        @if($job->proposals->count() > 0)
                        <a href="{{ route('jobs.proposals', $job) }}" class="badge text-decoration-none" style="background:#eff6ff;color:#3b82f6">
                            <i class="fa fa-inbox me-1"></i>{{ $job->proposals->count() }} proposals
                        </a>
                        @endif
                        <span class="badge bg-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning text-dark') }}-subtle text-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-briefcase fa-2x mb-2 opacity-25"></i>
                    <div class="small">No jobs posted yet</div>
                    <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm mt-2">Post Your First Job</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Active Contracts --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
                <span><i class="fa fa-file-contract me-2" style="color:#10b981"></i>Active Contracts</span>
                <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($activeContracts as $contract)
                <a href="{{ route('contracts.show', $contract) }}" class="d-block px-3 py-2 text-decoration-none border-bottom text-dark">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="overflow-hidden">
                            <div class="fw-semibold small text-truncate">{{ $contract->job?->title ?? 'N/A' }}</div>
                            <div class="text-muted" style="font-size:.75rem">
                                <img src="{{ $contract->freelancer?->avatar_url }}" class="rounded-circle me-1" width="14" height="14" style="object-fit:cover">
                                {{ $contract->freelancer?->name ?? 'N/A' }}
                            </div>
                        </div>
                        @php $done = $contract->milestones->where('status','approved')->count(); $total = $contract->milestones->count(); @endphp
                        <span class="small text-muted flex-shrink-0 ms-2">{{ $total ? round($done/$total*100) : 0 }}%</span>
                    </div>
                    @if($total)
                    <div class="progress mt-1" style="height:3px">
                        <div class="progress-bar bg-success" style="width:{{ round($done/$total*100) }}%"></div>
                    </div>
                    @endif
                </a>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-file-contract fa-2x mb-2 opacity-25"></i>
                    <div class="small">No active contracts</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{--  Recent Transactions  --}}
<div class="card">
    <div class="card-header bg-white fw-bold d-flex align-items-center justify-content-between">
        <span><i class="fa fa-receipt me-2" style="color:#8b5cf6"></i>Recent Transactions</span>
        <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none">View All</a>
    </div>
    @if($recentTransactions->isEmpty())
    <div class="card-body text-center text-muted py-4">
        <i class="fa fa-receipt fa-2x mb-2 opacity-25"></i><div class="small">No transactions yet</div>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr><th>Type</th><th>Description</th><th class="text-end">Amount</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $tx)
                <tr>
                    <td>
                        @php $types = ['deposit'=>['bg-success','arrow-down'],'withdrawal'=>['bg-danger','arrow-up'],'escrow'=>['bg-warning text-dark','lock'],'release'=>['bg-primary','check']]; $t=$types[$tx->type]??['bg-secondary','circle']; @endphp
                        <span class="badge {{ $t[0] }}"><i class="fa fa-{{ $t[1] }}"></i> {{ ucfirst($tx->type) }}</span>
                    </td>
                    <td class="text-truncate" style="max-width:200px">{{ $tx->description ?? '' }}</td>
                    <td class="text-end fw-semibold {{ in_array($tx->type,['deposit','release']) ? 'text-success' : 'text-danger' }}">
                        {{ in_array($tx->type,['deposit','release']) ? '+' : '-' }}Nu.{{ number_format($tx->amount) }}
                    </td>
                    <td class="text-muted">{{ $tx->created_at->format('d M') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
