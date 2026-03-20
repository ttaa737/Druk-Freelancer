@extends('layouts.app')
@section('title', 'My Proposals')
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Proposals</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fa fa-paper-plane me-2"></i>My Proposals</h5>
    <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-search me-1"></i>Browse Jobs
    </a>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-3">
    @php
        $filter = request('filter', 'all');
        $statusCounts = [
            'all' => $proposals->total(),
            'pending' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'pending')->count(),
            'shortlisted' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('is_shortlisted', true)->count(),
            'awarded' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'awarded')->count(),
            'rejected' => \App\Models\Proposal::where('freelancer_id', auth()->id())->where('status', 'rejected')->count(),
        ];
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="?filter=all">
            All <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'pending' ? 'active' : '' }}" href="?filter=pending">
            Pending <span class="badge bg-warning text-dark ms-1">{{ $statusCounts['pending'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'shortlisted' ? 'active' : '' }}" href="?filter=shortlisted">
            <i class="fa fa-bookmark me-1"></i>Shortlisted <span class="badge bg-warning ms-1">{{ $statusCounts['shortlisted'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'awarded' ? 'active' : '' }}" href="?filter=awarded">
            <i class="fa fa-trophy me-1"></i>Awarded <span class="badge bg-success ms-1">{{ $statusCounts['awarded'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'rejected' ? 'active' : '' }}" href="?filter=rejected">
            Rejected <span class="badge bg-danger ms-1">{{ $statusCounts['rejected'] }}</span>
        </a>
    </li>
</ul>

@forelse($proposals as $proposal)
<div class="card mb-3 shadow-sm {{ $proposal->is_shortlisted ? 'border-warning' : '' }}">
    <div class="card-body">
        <div class="row align-items-start g-3">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-1">
                        <a href="{{ route('jobs.show', $proposal->job->slug) }}" class="text-decoration-none">{{ $proposal->job->title }}</a>
                    </h6>
                    @php
                        $statusConfig = match($proposal->status) {
                            'pending' => ['class' => 'warning text-dark', 'icon' => 'clock'],
                            'awarded' => ['class' => 'success', 'icon' => 'trophy'],
                            'rejected' => ['class' => 'danger', 'icon' => 'times-circle'],
                            'withdrawn' => ['class' => 'secondary', 'icon' => 'undo'],
                            default => ['class' => 'secondary', 'icon' => 'circle'],
                        };
                    @endphp
                    <span class="badge bg-{{ $statusConfig['class'] }}">
                        <i class="fa fa-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($proposal->status) }}
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-muted small me-3">
                        <i class="fa fa-user me-1"></i>{{ $proposal->job->poster->name }}
                    </span>
                    <span class="text-muted small me-3">
                        <i class="fa fa-calendar me-1"></i>Submitted {{ $proposal->created_at->diffForHumans() }}
                    </span>
                    @if($proposal->delivery_days)
                    <span class="text-muted small me-3">
                        <i class="fa fa-clock me-1"></i>{{ $proposal->delivery_days }} days delivery
                    </span>
                    @endif
                    @if($proposal->is_shortlisted)
                    <span class="badge bg-warning text-dark">
                        <i class="fa fa-bookmark me-1"></i>Shortlisted
                    </span>
                    @endif
                </div>

                <p class="text-muted small mb-2">{{ Str::limit($proposal->cover_letter, 180) }}</p>

                @if($proposal->milestones->isNotEmpty())
                <div class="border-start border-3 border-primary ps-2 mt-2">
                    <small class="text-muted fw-semibold d-block mb-1">Proposed Milestones:</small>
                    @foreach($proposal->milestones->take(2) as $ms)
                    <small class="text-muted d-block">• {{ $ms->title }} - Nu. {{ number_format($ms->amount) }}</small>
                    @endforeach
                    @if($proposal->milestones->count() > 2)
                    <small class="text-primary">+{{ $proposal->milestones->count() - 2 }} more milestones</small>
                    @endif
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="text-lg-end">
                    <div class="mb-2">
                        <small class="text-muted d-block">Your Bid Amount</small>
                        <h5 class="fw-bold text-primary mb-0">Nu. {{ number_format($proposal->bid_amount) }}</h5>
                    </div>

                    <div class="d-flex gap-2 justify-content-lg-end mt-3">
                        <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>View Details
                        </a>
                        @if($proposal->status === 'pending')
                        <form method="POST" action="{{ route('proposals.withdraw', $proposal) }}" class="d-inline" onsubmit="return confirm('Withdraw this proposal? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa fa-times me-1"></i>Withdraw
                            </button>
                        </form>
                        @endif
                        @if($proposal->status === 'awarded')
                        <a href="{{ route('contracts.index') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-file-contract me-1"></i>View Contract
                        </a>
                        @endif
                    </div>

                    @if($proposal->rejection_reason && $proposal->status === 'rejected')
                    <div class="alert alert-danger alert-sm mt-2 mb-0 text-start">
                        <small><strong>Reason:</strong> {{ $proposal->rejection_reason }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card text-center py-5 shadow-sm">
    <div class="card-body">
        <i class="fa fa-paper-plane fa-4x text-muted mb-3" style="opacity: 0.3"></i>
        <h6 class="text-muted mb-2">
            @if($filter === 'all')
                You haven't submitted any proposals yet.
            @elseif($filter === 'pending')
                No pending proposals.
            @elseif($filter === 'shortlisted')
                No shortlisted proposals.
            @elseif($filter === 'awarded')
                No awarded proposals yet.
            @elseif($filter === 'rejected')
                No rejected proposals.
            @endif
        </h6>
        <p class="text-muted small mb-3">Start browsing jobs and submit proposals to win projects!</p>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">
            <i class="fa fa-search me-1"></i>Browse Available Jobs
        </a>
    </div>
</div>
@endforelse

@if($proposals->hasPages())
<div class="mt-3">
    {{ $proposals->links() }}
</div>
@endif

@endsection
