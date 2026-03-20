@extends('layouts.app')
@section('title', $job->title)
@section('content')

<div class="row g-3">

    {{--  Main Column  --}}
    <div class="col-lg-8">

        {{-- Job Details Card --}}
        <div class="card mb-3">
            <div class="card-body">

                @if($job->is_featured)
                <span class="badge bg-warning text-dark mb-2"><i class="fa fa-star me-1"></i>Featured</span>
                @endif

                <h4 class="fw-bold mb-2">{{ $job->title }}</h4>

                <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                    <span><i class="fa fa-tag me-1"></i>{{ $job->category?->name }}</span>
                    <span><i class="fa fa-briefcase me-1"></i>{{ ucfirst($job->type) }} Price</span>
                    <span><i class="fa fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                    <span><i class="fa fa-eye me-1"></i>{{ $job->views_count }} views</span>
                    <span><i class="fa fa-paper-plane me-1"></i>{{ $job->proposals_count ?? $job->proposals->count() }} proposals</span>
                </div>

                <h6 class="fw-semibold text-muted text-uppercase small mb-2">Project Description</h6>
                <div class="text-secondary" style="line-height:1.75">{!! nl2br(e($job->description)) !!}</div>

                @if($job->attachments()->exists())
                <hr>
                <h6 class="fw-semibold text-muted text-uppercase small mb-2">Attachments</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($job->attachments()->get() as $attachment)
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-paperclip me-1"></i>{{ $attachment->original_name }}
                    </a>
                    @endforeach
                </div>
                @endif

                @if($job->skills->isNotEmpty())
                <hr>
                <h6 class="fw-semibold text-muted text-uppercase small mb-2">Required Skills</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($job->skills as $skill)
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">{{ $skill->name }}</span>
                    @endforeach
                </div>
                @endif

            </div>
        </div>

        {{-- Proposals (visible to job poster only) --}}
        @auth
        @if(auth()->user()->id === $job->poster_id)
        <div class="card mb-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <span class="fw-bold">
                    <i class="fa fa-inbox me-2 text-primary"></i>Proposals
                    <span class="badge bg-primary ms-1">{{ $job->proposals_count }}</span>
                </span>
                <a href="{{ route('jobs.proposals', $job) }}" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                @foreach($job->proposals()->with('freelancer.profile')->latest()->take(10)->get() as $proposal)
                <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom">
                    <img src="{{ $proposal->freelancer->avatar_url }}" class="rounded-circle flex-shrink-0 object-fit-cover" width="42" height="42" alt="">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="small">{{ $proposal->freelancer->name }}</strong>
                            <span class="fw-bold text-primary small">Nu. {{ number_format($proposal->bid_amount) }}</span>
                        </div>
                        <p class="text-muted small mb-1">{{ Str::limit($proposal->cover_letter, 120) }}</p>
                        @php
                            $pClass = match($proposal->status) {
                                'pending' => 'bg-warning text-dark',
                                'awarded' => 'bg-success',
                                default   => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $pClass }}">{{ ucfirst($proposal->status) }}</span>
                    </div>
                    <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-outline-secondary btn-sm flex-shrink-0 align-self-center">View</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endauth

        {{-- Submit Proposal Form --}}
        @auth
        @if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && !$alreadyApplied && $job->poster_id !== auth()->id())
        <div class="card" id="proposal-form">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0"><i class="fa fa-paper-plane me-2 text-primary"></i>Submit Your Proposal</h5>
                <div class="text-muted small mt-1">Tell the client why you are the perfect fit for this project.</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('proposals.store', $job) }}">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Bid Amount (Nu.) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Nu.</span>
                                <input type="number" name="bid_amount" class="form-control" required min="1" placeholder="0" value="{{ old('bid_amount') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Delivery Time (days)</label>
                            <div class="input-group">
                                <input type="number" name="delivery_days" class="form-control" min="1" placeholder="e.g. 7" value="{{ old('delivery_days') }}">
                                <span class="input-group-text">days</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cover Letter <span class="text-danger">*</span></label>
                        <textarea name="cover_letter" class="form-control" rows="6" required
                                  placeholder="Describe your relevant experience and why you are the best fit...">{{ old('cover_letter') }}</textarea>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane me-1"></i> Submit Proposal
                        </button>
                        <span class="text-muted small"><i class="fa fa-info-circle me-1"></i>Your identity is shared with the client.</span>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endauth

    </div>

    {{--  Sidebar  --}}
    <div class="col-lg-4">

        {{-- Budget Card --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-1">Project Budget</div>
                <div class="display-6 fw-bold text-primary mb-2">{{ $job->budgetRange }}</div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light text-dark border"><i class="fa fa-briefcase me-1"></i>{{ ucfirst($job->type) }}</span>
                    @if($job->experience_level)
                    <span class="badge bg-light text-dark border"><i class="fa fa-layer-group me-1"></i>{{ ucfirst($job->experience_level) }} Level</span>
                    @endif
                </div>

                @if($job->deadline)
                <div class="alert alert-warning py-2 px-3 d-flex align-items-center gap-2 mb-3">
                    <i class="fa fa-calendar-alt"></i>
                    <span class="small">Deadline: <strong>{{ $job->deadline->format('d M Y') }}</strong></span>
                </div>
                @endif

                @auth
                    @if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && $job->poster_id !== auth()->id())
                        @if(!$alreadyApplied)
                        <a href="#proposal-form" class="btn btn-primary w-100">
                            <i class="fa fa-paper-plane me-1"></i> Submit Proposal
                        </a>
                        @else
                        <div class="alert alert-success py-2 px-3 d-flex align-items-center gap-2 mb-0">
                            <i class="fa fa-check-circle"></i>
                            <span class="small">You have already submitted a proposal.</span>
                        </div>
                        @endif
                    @elseif(auth()->user()->id === $job->poster_id)
                    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline-secondary w-100">
                        <i class="fa fa-edit me-1"></i> Edit Job
                    </a>
                    @endif
                @else
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    <i class="fa fa-sign-in-alt me-1"></i> Login to Apply
                </a>
                @endauth
            </div>
        </div>

        {{-- Client Card --}}
        <div class="card">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-3">About the Client</div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $job->poster->avatar_url }}" class="rounded-circle object-fit-cover flex-shrink-0" width="48" height="48" alt="">
                    <div class="overflow-hidden">
                        <div class="fw-bold text-truncate">{{ $job->poster->name }}</div>
                        @if($job->poster->profile?->company_name)
                        <div class="text-muted small text-truncate">{{ $job->poster->profile->company_name }}</div>
                        @endif
                    </div>
                </div>

                @if($job->poster->profile?->dzongkhag)
                <div class="text-muted small mb-2">
                    <i class="fa fa-map-marker-alt me-1 text-danger"></i>{{ $job->poster->profile->dzongkhag }}, Bhutan
                </div>
                @endif

                @if($job->poster->verification_status === 'verified')
                <div class="mb-3">
                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Verified Client</span>
                </div>
                @endif

                <a href="{{ route('profile.show', $job->poster) }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fa fa-user me-1"></i> View Profile
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
