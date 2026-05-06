@extends('layouts.app')
@section('title', $job->title)
@section('content')

<div class="row g-4">

    {{--  Main Column  --}}
    <div class="col-lg-8">

        {{-- Job Header Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        @if($job->is_featured)
                        <span class="badge bg-warning text-dark mb-2"><i class="fa fa-star me-1"></i>Featured Job</span>
                        @endif
                        <h2 class="fw-bold mb-2" style="font-size:28px;">{{ $job->title }}</h2>
                    </div>
                </div>

                {{-- Job Metadata --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Category</div>
                        <div class="fw-semibold">{{ $job->category?->name ?? 'Uncategorized' }}</div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Type</div>
                        <div class="fw-semibold"><span class="badge bg-primary bg-opacity-20 text-primary">{{ ucfirst($job->type) }}</span></div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Posted</div>
                        <div class="fw-semibold">{{ $job->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Views</div>
                        <div class="fw-semibold"><i class="fa fa-eye me-1 text-primary"></i>{{ $job->views_count }}</div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <div class="text-muted small fw-semibold text-uppercase">Proposals</div>
                        <div class="fw-semibold"><i class="fa fa-paper-plane me-1 text-primary"></i>{{ $job->proposals_count ?? $job->proposals->count() }}</div>
                    </div>
                </div>

                @if($job->deadline || $job->duration_days)
                @php
                    $deadlineIsPast = $job->deadline ? ($job->deadline->isToday() || $job->deadline->isPast()) : false;
                    $daysUntilDeadline = $job->deadline ? now()->diffInDays($job->deadline, absolute: true) : null;
                    $completionDate = ($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null;
                @endphp
                <div class="alert {{ $deadlineIsPast ? 'alert-danger' : 'alert-warning' }} mb-0 d-flex align-items-center gap-3 py-3">
                    <div>
                        <i class="fa fa-calendar-alt fs-5"></i>
                    </div>
                    <div style="flex: 1;">
                        @if($job->deadline)
                        <div class="fw-semibold mb-1">Proposal Deadline</div>
                        <div class="fs-6 fw-bold text-danger">{{ $job->deadline->format('d/m/Y') }}</div>
                        @if(!$deadlineIsPast && $daysUntilDeadline > 0)
                        <small class="text-muted d-block">{{ $daysUntilDeadline }} {{ str_plural('day', $daysUntilDeadline) }} remaining</small>
                        @elseif($deadlineIsPast)
                        <small class="text-danger fw-semibold d-block">This deadline has passed</small>
                        @endif
                        @endif
                        @if($job->duration_days)
                        <small class="text-muted d-block mt-1">
                            <span class="fw-semibold text-body">Job Completion:</span>
                            {{ $completionDate ? $completionDate->format('d/m/Y') : ('within ' . (int) $job->duration_days . ' days') }}
                        </small>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Job Details Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">


                <div class="mb-4">
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-align-left me-2 text-primary"></i>Project Description</h6>
                    <div class="text-secondary" style="line-height:1.8; font-size:15px;">{!! nl2br(e($job->description)) !!}</div>
                </div>


                @if($job->attachments()->exists())
                <div class="mb-4">
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-paperclip me-2 text-primary"></i>Attachments</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($job->attachments()->get() as $attachment)
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
                            <i class="fa fa-download"></i>{{ $attachment->original_name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif


                @if($job->skills->isNotEmpty())
                <div>
                    <h6 class="fw-bold mb-3 pb-2 border-bottom"><i class="fa fa-star me-2 text-primary"></i>Required Skills</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Proposals (visible to job poster only) --}}
        @auth
        @if(auth()->user()->id === $job->poster_id)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 border-bottom">
                <span class="fw-bold fs-5">
                    <i class="fa fa-inbox me-2 text-primary"></i>Received Proposals
                    <span class="badge bg-primary ms-2">{{ $job->proposals_count }}</span>
                </span>
                <a href="{{ route('jobs.proposals', $job) }}" class="btn btn-primary btn-sm">View All</a>
            </div>
            <div class="card-body p-0">
                @foreach($job->proposals()->with('freelancer.profile')->latest()->take(10)->get() as $proposal)
                <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom hover" style="background:transparent; transition: background 0.2s;">
                    <img src="{{ $proposal->freelancer->avatar_url }}" class="rounded-circle flex-shrink-0 object-fit-cover" width="48" height="48" alt="">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-bold">{{ $proposal->freelancer->name }}</div>
                                <small class="text-muted">
                                    @if($proposal->freelancer->profile?->title)
                                        {{ $proposal->freelancer->profile->title }}
                                    @else
                                        Freelancer
                                    @endif
                                </small>
                            </div>
                            <span class="fw-bold text-primary text-nowrap">Nu. {{ number_format($proposal->bid_amount) }}</span>
                        </div>
                        <p class="text-muted small mb-2" style="line-height:1.5;">{{ Str::limit($proposal->cover_letter, 120) }}</p>
                        @php
                            if ($proposal->status === 'pending') {
                                $pClass = 'bg-warning text-dark';
                            } elseif ($proposal->status === 'awarded') {
                                $pClass = 'bg-success';
                            } else {
                                $pClass = 'bg-secondary';
                            }
                        @endphp
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge {{ $pClass }} small">{{ ucfirst($proposal->status) }}</span>
                            <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-link btn-sm p-0 text-primary">View Full →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endauth

        {{-- Submit Proposal Form --}}
        @auth
        @if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && !$alreadyApplied && $job->poster_id !== auth()->id())
            @if(auth()->user()->verification_status === 'verified')
            <div class="card border-0 shadow-sm" id="proposal-form">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-1"><i class="fa fa-paper-plane me-2 text-primary"></i>Submit Your Proposal</h5>
                    <p class="text-muted small mb-0">Write a compelling proposal that showcases your experience and understanding of the project.</p>
                    @if($job->budget_max)
                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small d-flex align-items-center gap-2">
                        <i class="fa fa-info-circle"></i>
                        <span><strong>Maximum bid for this job:</strong> Nu. {{ number_format($job->budget_max) }}</span>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('proposals.store', $job) }}">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Bid Amount (Nu.) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">Nu.</span>
                                    <input type="number" name="bid_amount" class="form-control border-start-0" required min="1" placeholder="15,000" value="{{ old('bid_amount') }}" {{ $job->budget_max ? 'max="' . $job->budget_max . '"' : '' }} style="font-size:16px;">
                                </div>
                                @if($job->budget_max)
                                <small class="text-muted d-block mt-2">Cannot exceed Nu. {{ number_format($job->budget_max) }}</small>
                                @endif
                                @error('bid_amount') <small class="text-danger d-block mt-1"><i class="fa fa-exclamation-circle me-1"></i>{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Delivery Time (days)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="delivery_days" class="form-control" min="1" placeholder="7" value="{{ old('delivery_days') }}" style="font-size:16px;">
                                    <span class="input-group-text bg-light">days</span>
                                </div>
                                @error('delivery_days') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Cover Letter <span class="text-danger">*</span></label>
                            <textarea name="cover_letter" class="form-control" rows="7" required style="font-size:15px; resize:vertical;"
                                      placeholder="Tell the client about your experience, approach, and why you're the best fit for this project...">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </button>
                            <span class="text-muted small"><i class="fa fa-info-circle me-1"></i>Your contact info will be shared with the client after submission.</span>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-info bg-opacity-10" style="width:60px;height:60px;">
                            <i class="fa fa-shield-alt text-info fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-2"><i class="fa fa-lock me-2"></i>Account Verification Required</h6>
                            <p class="text-muted mb-3">Complete your account verification to submit proposals. This helps build trust in our community.</p>
                            <a href="{{ route('profile.edit') }}#tab-docs" class="btn btn-info text-white btn-sm">
                                <i class="fa fa-arrow-right me-1"></i>Complete Verification
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
        @endauth

    </div>

    {{-- Sidebar  --}}
    <div class="col-lg-4">

        {{-- Budget Card --}}
        <div class="card border-0 shadow-sm mb-3 sticky-top" style="top: 20px;">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-2">Project Budget</div>
                <div style="font-size: 32px; font-weight: 800; color: #0d6efd; margin-bottom: 1rem;">{{ $job->budgetRange }}</div>

                <div class="mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border" style="font-size: 12px; padding: 6px 10px;">
                            <i class="fa fa-briefcase me-1"></i>{{ ucfirst($job->type) }}
                        </span>
                        @if($job->experience_level)
                        <span class="badge bg-light text-dark border" style="font-size: 12px; padding: 6px 10px;">
                            <i class="fa fa-layer-group me-1"></i>{{ ucfirst($job->experience_level) }}
                        </span>
                        @endif
                    </div>
                </div>

                @if($job->deadline || $job->duration_days)
                @php
                    $deadlineIsPast = $job->deadline ? ($job->deadline->isToday() || $job->deadline->isPast()) : false;
                    $daysUntilDeadline = $job->deadline ? now()->diffInDays($job->deadline, absolute: true) : null;
                    $completionDate = ($job->deadline && $job->duration_days) ? $job->deadline->copy()->addDays((int) $job->duration_days) : null;
                @endphp
                <div class="alert {{ $deadlineIsPast ? 'alert-danger' : 'alert-warning' }} py-3 px-3 d-flex align-items-center gap-3 mb-3">
                    <i class="fa fa-calendar-alt fs-5"></i>
                    <div style="font-size: 14px;">
                        @if($job->deadline)
                        <div class="fw-semibold mb-1">Proposal Deadline</div>
                        <div class="fs-6 fw-bold text-danger">{{ $job->deadline->format('d/m/Y') }}</div>
                        @if(!$deadlineIsPast && $daysUntilDeadline > 0)
                        <small class="text-muted d-block">{{ $daysUntilDeadline }} {{ str_plural('day', $daysUntilDeadline) }} remaining</small>
                        @elseif($deadlineIsPast)
                        <small class="text-danger fw-semibold d-block">This deadline has passed</small>
                        @endif
                        @endif
                        @if($job->duration_days)
                        <small class="text-muted d-block mt-1">
                            <span class="fw-semibold text-body">Job Completion:</span>
                            {{ $completionDate ? $completionDate->format('d/m/Y') : ('within ' . (int) $job->duration_days . ' days') }}
                        </small>
                        @endif
                    </div>
                </div>
                @endif

                <hr>

                @auth
                    @if(auth()->user()->hasRole('freelancer') && $job->status === 'open' && $job->poster_id !== auth()->id())
                        @if(!$alreadyApplied)
                            @if(auth()->user()->verification_status === 'verified')
                            <a href="#proposal-form" class="btn btn-primary w-100 btn-lg">
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </a>
                            @else
                            <button type="button" class="btn btn-outline-secondary w-100 btn-lg" disabled>
                                <i class="fa fa-paper-plane me-2"></i>Submit Proposal
                            </button>
                            <div class="border border-info rounded-2 bg-info bg-opacity-10 p-3 mt-3 small">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fa fa-lock text-info mt-1" style="font-size: 16px;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-2">Verify Your Account</div>
                                        <div class="text-muted mb-2" style="font-size: 13px;">Complete verification to submit proposals and build your profile.</div>
                                        <a href="{{ route('profile.edit') }}#tab-docs" class="btn btn-info btn-sm text-white">
                                            <i class="fa fa-arrow-right me-1"></i>Get Verified
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @else
                        <div class="alert alert-success py-3 px-3 d-flex align-items-center gap-2 mb-0">
                            <i class="fa fa-check-circle fs-5"></i>
                            <div>
                                <div class="fw-semibold small">Already Applied</div>
                                <small class="text-muted">You've already submitted a proposal for this job.</small>
                            </div>
                        </div>
                        @endif
                    @elseif(auth()->user()->id === $job->poster_id)
                    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline-secondary w-100 btn-lg">
                        <i class="fa fa-edit me-2"></i>Edit Job
                    </a>
                    @else
                    <a href="{{ route('login', ['intended' => route('jobs.show', $job)]) }}" class="btn btn-primary w-100 btn-lg">
                        <i class="fa fa-sign-in-alt me-2"></i>Login to Apply
                    </a>
                    @endif
                @else
                <a href="{{ route('login', ['intended' => route('jobs.show', $job)]) }}" class="btn btn-primary w-100 btn-lg">
                    <i class="fa fa-sign-in-alt me-2"></i>Login to Apply
                </a>
                @endauth
            </div>
        </div>

        {{-- Client Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-3 pb-3 border-bottom">About the Client</div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $job->poster->avatar_url }}" class="rounded-circle object-fit-cover flex-shrink-0 border" width="50" height="50" alt="">
                    <div class="overflow-hidden">
                        <div class="fw-bold">{{ $job->poster->name }}</div>
                        @if($job->poster->profile?->company_name)
                        <div class="text-muted small text-truncate">{{ $job->poster->profile->company_name }}</div>
                        @else
                        <div class="text-muted small">Job Poster</div>
                        @endif
                    </div>
                </div>

                @if($job->poster->profile?->dzongkhag)
                <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                    <i class="fa fa-map-marker-alt text-danger"></i>{{ $job->poster->profile->dzongkhag }}, Bhutan
                </div>
                @endif

                @if($job->poster->verification_status === 'verified')
                <div class="mb-3">
                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Verified Client</span>
                </div>
                @endif

                <a href="{{ route('profile.show', $job->poster) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fa fa-user me-1"></i>View Full Profile
                </a>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidInput = document.querySelector('input[name="bid_amount"]');
    const maxBid = {{ $job->budget_max ?? 'null' }};
    
    if (bidInput && maxBid) {
        bidInput.addEventListener('change', function() {
            const bid = parseFloat(this.value);
            if (bid > maxBid) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
});
</script>
@endpush

@endsection
