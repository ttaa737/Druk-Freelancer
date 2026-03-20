@extends('layouts.app')
@section('title', 'Proposal from ' . $proposal->freelancer->name)
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        @if(auth()->user()->id === $proposal->freelancer_id)
        <li class="breadcrumb-item"><a href="{{ route('proposals.my') }}" class="text-decoration-none">My Proposals</a></li>
        @else
        <li class="breadcrumb-item"><a href="{{ route('jobs.my') }}" class="text-decoration-none">My Jobs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jobs.proposals', $proposal->job) }}" class="text-decoration-none">Proposals</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Proposal Details</li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Professional Proposal Header --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-4" style="border-bottom: 3px solid var(--bs-primary);">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">PROJECT PROPOSAL</h3>
                    <p class="text-muted mb-0">For: {{ $proposal->job->title }}</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>SUBMITTED BY (Freelancer)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $proposal->freelancer->avatar_url }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $proposal->freelancer->name }}</p>
                                    <p class="mb-0 text-muted small">{{ $proposal->freelancer->email }}</p>
                                    <p class="mb-0 text-muted small">
                                        {{ $proposal->freelancer->profile?->headline ?? 'Freelancer' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>SUBMITTED TO (Client)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $proposal->job->poster->avatar_url }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $proposal->job->poster->name }}</p>
                                    <p class="mb-0 text-muted small">{{ $proposal->job->poster->email }}</p>
                                    <p class="mb-0 text-muted small">Job Poster</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border">
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Proposal Status</small>
                            @php
                                $statusConfig = match($proposal->status) {
                                    'pending' => ['class' => 'warning text-dark', 'icon' => 'clock'],
                                    'awarded' => ['class' => 'success', 'icon' => 'trophy'],
                                    'rejected' => ['class' => 'danger', 'icon' => 'times-circle'],
                                    'withdrawn' => ['class' => 'secondary', 'icon' => 'undo'],
                                    default => ['class' => 'secondary', 'icon' => 'circle'],
                                };
                            @endphp
                            <span class="badge bg-{{ $statusConfig['class'] }} mt-1">
                                <i class="fa fa-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($proposal->status) }}
                            </span>
                            @if($proposal->is_shortlisted)
                            <div class="mt-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="fa fa-bookmark me-1"></i>Shortlisted
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Bid Amount</small>
                            <strong class="text-primary d-block mt-1 h5 mb-0">Nu. {{ number_format($proposal->bid_amount) }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Delivery Time</small>
                            <strong class="d-block mt-1">{{ $proposal->delivery_days ?? 'Flexible' }} {{ $proposal->delivery_days ? 'days' : '' }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Submitted On</small>
                            <strong class="d-block mt-1">{{ $proposal->created_at->format('d M Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cover Letter --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-file-alt me-2"></i>Cover Letter</h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $proposal->cover_letter }}</p>
            </div>
        </div>

        {{-- Proposed Milestones --}}
        @if($proposal->milestones->isNotEmpty())
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-tasks me-2"></i>Proposed Project Milestones</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Milestone Title</th>
                                <th>Description</th>
                                <th width="120">Amount</th>
                                <th width="100">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proposal->milestones as $index => $ms)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $index + 1 }}</span>
                                </td>
                                <td><strong>{{ $ms->title }}</strong></td>
                                <td class="text-muted small">{{ $ms->description ?? '-' }}</td>
                                <td><strong class="text-primary">Nu. {{ number_format($ms->amount) }}</strong></td>
                                <td class="text-muted small">{{ $ms->days }} days</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Project Value:</strong></td>
                                <td><strong class="text-primary">Nu. {{ number_format($proposal->milestones->sum('amount')) }}</strong></td>
                                <td><strong>{{ $proposal->milestones->sum('days') }} days</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Project Scope / Job Details --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-briefcase me-2"></i>Project Details</h6>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-2">{{ $proposal->job->title }}</h6>
                @if($proposal->job->description)
                <p class="text-muted small mb-3">{!! nl2br(e($proposal->job->description)) !!}</p>
                @endif
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Budget Range</small>
                        <strong>Nu. {{ number_format($proposal->job->budget_min) }} - Nu. {{ number_format($proposal->job->budget_max) }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Category</small>
                        <strong>{{ $proposal->job->category->name ?? 'Other' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Proposal Actions --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                @if(auth()->user()->id === $proposal->job->poster_id && $proposal->status === 'pending')
                <form method="POST" action="{{ route('proposals.award', $proposal) }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Award this project to {{ $proposal->freelancer->name }}?')">
                        <i class="fa fa-trophy me-1"></i> Award Project
                    </button>
                </form>
                <form method="POST" action="{{ route('proposals.shortlist', $proposal) }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning w-100">
                        <i class="fa fa-bookmark me-1"></i> {{ $proposal->is_shortlisted ? 'Remove from Shortlist' : 'Add to Shortlist' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('proposals.reject', $proposal) }}">
                    @csrf
                    <input type="hidden" name="reason" value="Not selected">
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Reject this proposal?')">
                        <i class="fa fa-times me-1"></i> Reject Proposal
                    </button>
                </form>
                @endif

                @if(auth()->user()->id === $proposal->freelancer_id && $proposal->status === 'pending')
                <form method="POST" action="{{ route('proposals.withdraw', $proposal) }}" onsubmit="return confirm('Withdraw this proposal? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fa fa-times-circle me-1"></i> Withdraw Proposal
                    </button>
                </form>
                @endif

                @if($proposal->status === 'awarded')
                <div class="alert alert-success mb-2">
                    <i class="fa fa-trophy me-1"></i> This proposal has been awarded!
                </div>
                <a href="{{ route('contracts.index') }}" class="btn btn-primary w-100">
                    <i class="fa fa-file-contract me-1"></i> View Contract
                </a>
                @endif

                @if($proposal->status === 'rejected' && $proposal->rejection_reason)
                <div class="alert alert-danger small mb-0">
                    <strong>Rejection Reason:</strong><br>
                    {{ $proposal->rejection_reason }}
                </div>
                @endif

                <hr class="my-3">

                <a href="{{ route('messages.start') }}" class="btn btn-outline-secondary btn-sm w-100"
                   onclick="event.preventDefault(); document.getElementById('msg-form').submit();">
                    <i class="fa fa-comments me-1"></i> Message {{ auth()->user()->id === $proposal->freelancer_id ? 'Client' : 'Freelancer' }}
                </a>
                <form id="msg-form" method="POST" action="{{ route('messages.start') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ auth()->user()->id === $proposal->freelancer_id ? $proposal->job->poster_id : $proposal->freelancer_id }}">
                    <input type="hidden" name="job_id" value="{{ $proposal->job_id }}">
                </form>
            </div>
        </div>

        {{-- Freelancer Profile Summary --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-user me-2"></i>Freelancer Profile</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="{{ $proposal->freelancer->avatar_url }}" class="rounded-circle mb-2" width="80" height="80" style="object-fit: cover;">
                    <h6 class="fw-semibold mb-0">{{ $proposal->freelancer->name }}</h6>
                    <p class="text-muted small mb-0">{{ $proposal->freelancer->profile?->headline ?? 'Freelancer' }}</p>
                </div>

                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Rating:</span>
                        <span>
                            <i class="fa fa-star text-warning"></i>
                            {{ number_format($proposal->freelancer->profile?->rating ?? 0, 1) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Experience:</span>
                        <span>{{ $proposal->freelancer->profile?->experience_years ?? 0 }} years</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Location:</span>
                        <span>{{ $proposal->freelancer->profile?->dzongkhag ?? 'Bhutan' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Projects:</span>
                        <span>{{ $proposal->freelancer->profile?->completed_jobs ?? 0 }} completed</span>
                    </div>

                    <a href="{{ route('profile.show', $proposal->freelancer) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa fa-eye me-1"></i>View Full Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- Job Info --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-info-circle me-2"></i>Proposal Information</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <strong>Submitted:</strong><br>
                    <span class="text-muted">{{ $proposal->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="mb-2">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted">{{ $proposal->updated_at->diffForHumans() }}</span>
                </div>
                @if($proposal->awarded_at)
                <div class="mb-2">
                    <strong>Awarded On:</strong><br>
                    <span class="text-muted">{{ $proposal->awarded_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                <div class="mb-0">
                    <strong>Related Job:</strong><br>
                    <a href="{{ route('jobs.show', $proposal->job->slug) }}" class="text-decoration-none">View Job Posting</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
