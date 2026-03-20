@extends('layouts.app')
@section('title', 'Proposals for ' . $job->title)
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jobs.my') }}" class="text-decoration-none">My Jobs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">{{ Str::limit($job->title, 30) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Proposals</li>
    </ol>
</nav>

<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="fw-bold mb-1"><i class="fa fa-file-alt me-2"></i>{{ $job->title }}</h5>
                <p class="text-muted small mb-0">
                    <i class="fa fa-money-bill-wave me-1"></i>Budget: Nu. {{ number_format($job->budget_min) }} - Nu. {{ number_format($job->budget_max) }}
                    <span class="mx-2">•</span>
                    <i class="fa fa-calendar me-1"></i>Posted {{ $job->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-primary fs-6"><i class="fa fa-paper-plane me-1"></i>{{ $proposals->total() }} Proposals</span>
                <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fa fa-eye me-1"></i>View Job
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-3">
    @php
        $filter = request('filter', 'all');
        $filterCounts = [
            'all' => $job->proposals()->count(),
            'pending' => $job->proposals()->where('status', 'pending')->count(),
            'shortlisted' => $job->proposals()->where('is_shortlisted', true)->count(),
            'awarded' => $job->proposals()->where('status', 'awarded')->count(),
        ];
    @endphp
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="?filter=all">
            All <span class="badge bg-secondary ms-1">{{ $filterCounts['all'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'pending' ? 'active' : '' }}" href="?filter=pending">
            Pending <span class="badge bg-warning text-dark ms-1">{{ $filterCounts['pending'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'shortlisted' ? 'active' : '' }}" href="?filter=shortlisted">
            <i class="fa fa-bookmark me-1"></i>Shortlisted <span class="badge bg-warning ms-1">{{ $filterCounts['shortlisted'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'awarded' ? 'active' : '' }}" href="?filter=awarded">
            <i class="fa fa-trophy me-1"></i>Awarded <span class="badge bg-success ms-1">{{ $filterCounts['awarded'] }}</span>
        </a>
    </li>
</ul>

@forelse($proposals as $proposal)
<div class="card mb-3 shadow-sm {{ $proposal->is_shortlisted ? 'border-warning border-2' : '' }}">
    <div class="card-body">
        <div class="row align-items-start g-3">
            {{-- Freelancer Info --}}
            <div class="col-md-2 text-center">
                <img src="{{ $proposal->freelancer->avatar_url }}" class="rounded-circle mb-2" width="80" height="80" style="object-fit: cover;">
                <div>
                    <a href="{{ route('profile.show', $proposal->freelancer) }}" class="text-decoration-none fw-semibold">
                        {{ $proposal->freelancer->name }}
                    </a>
                    @if($proposal->is_shortlisted)
                    <div class="mt-1">
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-bookmark"></i> Shortlisted
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Proposal Details --}}
            <div class="col-md-6">
                <div class="mb-2">
                    <span class="text-muted small me-3">
                        <i class="fa fa-star text-warning me-1"></i>
                        {{ number_format($proposal->freelancer->profile?->rating ?? 0, 1) }} Rating
                    </span>
                    <span class="text-muted small me-3">
                        <i class="fa fa-briefcase me-1"></i>
                        {{ $proposal->freelancer->profile?->experience_years ?? 0 }} years exp
                    </span>
                    <span class="text-muted small">
                        <i class="fa fa-map-marker-alt me-1"></i>
                        {{ $proposal->freelancer->profile?->dzongkhag ?? 'Bhutan' }}
                    </span>
                </div>

                <p class="text-muted small mb-2">
                    <strong>Cover Letter:</strong><br>
                    {{ Str::limit($proposal->cover_letter, 200) }}
                </p>

                @if($proposal->milestones->isNotEmpty())
                <div class="border-start border-3 border-primary ps-2 mb-2">
                    <small class="text-muted fw-semibold d-block mb-1">
                        <i class="fa fa-tasks me-1"></i>Proposed Milestones ({{ $proposal->milestones->count() }}):
                    </small>
                    @foreach($proposal->milestones->take(2) as $ms)
                    <small class="text-muted d-block">
                        • {{ $ms->title }} - Nu. {{ number_format($ms->amount) }} ({{ $ms->days }} days)
                    </small>
                    @endforeach
                    @if($proposal->milestones->count() > 2)
                    <small class="text-primary">+{{ $proposal->milestones->count() - 2 }} more</small>
                    @endif
                </div>
                @endif

                <div class="text-muted small">
                    <i class="fa fa-clock me-1"></i>Submitted {{ $proposal->created_at->diffForHumans() }}
                </div>
            </div>

            {{-- Bid Amount & Actions --}}
            <div class="col-md-4">
                <div class="text-md-end">
                    <div class="mb-3">
                        <small class="text-muted d-block">Bid Amount</small>
                        <h4 class="fw-bold text-primary mb-0">Nu. {{ number_format($proposal->bid_amount) }}</h4>
                        @if($proposal->delivery_days)
                        <small class="text-muted">
                            <i class="fa fa-calendar-check me-1"></i>{{ $proposal->delivery_days }} days delivery
                        </small>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>View Full Proposal
                        </a>
                        
                        @if($proposal->status === 'pending')
                        <form method="POST" action="{{ route('proposals.award', $proposal) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Award this project to {{ $proposal->freelancer->name }}?')">
                                <i class="fa fa-trophy me-1"></i>Award Project
                            </button>
                        </form>
                        <form method="POST" action="{{ route('proposals.shortlist', $proposal) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                                <i class="fa fa-bookmark me-1"></i>{{ $proposal->is_shortlisted ? 'Remove Shortlist' : 'Shortlist' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('proposals.reject', $proposal) }}">
                            @csrf
                            <input type="hidden" name="reason" value="Not selected">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Reject this proposal?')">
                                <i class="fa fa-times me-1"></i>Reject
                            </button>
                        </form>
                        @else
                        <span class="badge bg-{{ $proposal->status === 'awarded' ? 'success' : 'secondary' }} w-100">
                            {{ ucfirst($proposal->status) }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card text-center py-5 shadow-sm">
    <div class="card-body">
        <i class="fa fa-inbox fa-4x text-muted mb-3" style="opacity: 0.3"></i>
        <h6 class="text-muted mb-2">
            @if($filter === 'all')
                No proposals received yet for this job.
            @elseif($filter === 'pending')
                No pending proposals.
            @elseif($filter === 'shortlisted')
                No shortlisted proposals.
            @elseif($filter === 'awarded')
                No awarded proposals.
            @endif
        </h6>
        <p class="text-muted small">
            @if($filter === 'all')
                Freelancers will submit proposals soon. Check back later!
            @endif
        </p>
    </div>
</div>
@endforelse

@if($proposals->hasPages())
<div class="mt-3">
    {{ $proposals->links() }}
</div>
@endif

@endsection
