@extends('layouts.app')
@section('title', 'Contract ' . $contract->contract_number)
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}" class="text-decoration-none">Contracts</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $contract->contract_number }}</li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Professional Contract Header --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body p-4" style="border-bottom: 3px solid var(--bs-primary);">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">FREELANCE SERVICE CONTRACT</h3>
                    <p class="text-muted mb-0">Contract No: #{{ $contract->contract_number }}</p>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>CLIENT (Job Poster)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $contract->poster->avatar_url }}" class="rounded-circle" width="40" height="40">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $contract->poster->name }}</p>
                                    <p class="mb-0 text-muted small">{{ $contract->poster->email }}</p>
                                </div>
                            </div>
                            @if($contract->poster_signed)
                            <div class="mt-2 text-success small">
                                <i class="fa fa-check-circle me-1"></i>Signed on {{ $contract->updated_at->format('d M Y, h:i A') }}
                            </div>
                            @else
                            <div class="mt-2 text-warning small">
                                <i class="fa fa-clock me-1"></i>Signature Pending
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="text-muted small mb-2"><strong>FREELANCER (Service Provider)</strong></p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $contract->freelancer->avatar_url }}" class="rounded-circle" width="40" height="40">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $contract->freelancer->name }}</p>
                                    <p class="mb-0 text-muted small">{{ $contract->freelancer->email }}</p>
                                </div>
                            </div>
                            @if($contract->freelancer_signed)
                            <div class="mt-2 text-success small">
                                <i class="fa fa-check-circle me-1"></i>Signed on {{ $contract->updated_at->format('d M Y, h:i A') }}
                            </div>
                            @else
                            <div class="mt-2 text-warning small">
                                <i class="fa fa-clock me-1"></i>Signature Pending
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border">
                    <div class="row g-3 text-center">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Contract Status</small>
                            @php
                                $statusConfig = match($contract->status) {
                                    'pending' => ['class' => 'warning text-dark', 'icon' => 'clock'],
                                    'active' => ['class' => 'success', 'icon' => 'play-circle'],
                                    'completed' => ['class' => 'primary', 'icon' => 'check-circle'],
                                    'disputed' => ['class' => 'danger', 'icon' => 'exclamation-triangle'],
                                    'cancelled' => ['class' => 'secondary', 'icon' => 'times-circle'],
                                    default => ['class' => 'secondary', 'icon' => 'circle'],
                                };
                            @endphp
                            <span class="badge bg-{{ $statusConfig['class'] }} mt-1">
                                <i class="fa fa-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($contract->status) }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Total Contract Value</small>
                            <strong class="text-primary d-block mt-1">Nu. {{ number_format($contract->total_amount) }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Platform Fee (10%)</small>
                            <strong class="d-block mt-1">Nu. {{ number_format($contract->platform_fee) }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Freelancer Receives</small>
                            <strong class="text-success d-block mt-1">Nu. {{ number_format($contract->freelancer_amount) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row g-3 text-center small">
                    <div class="col-md-4">
                        <i class="fa fa-calendar text-primary me-1"></i>
                        <strong>Start Date:</strong> {{ $contract->start_date?->format('d M Y') ?? 'TBD' }}
                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-flag-checkered text-primary me-1"></i>
                        <strong>Deadline:</strong> {{ $contract->deadline?->format('d M Y') ?? 'Flexible' }}
                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-tasks text-primary me-1"></i>
                        <strong>Milestones:</strong> {{ $contract->milestones->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Project Description / Scope of Work --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-file-alt me-2"></i>Scope of Work</h6>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold mb-2">{{ $contract->job?->title }}</h6>
                @if($contract->job?->description)
                <p class="text-muted mb-3">{!! nl2br(e($contract->job->description)) !!}</p>
                @endif
                
                @if($contract->terms)
                <hr>
                <h6 class="fw-semibold mb-2">Special Terms & Conditions</h6>
                <p class="text-muted small mb-0">{!! nl2br(e($contract->terms)) !!}</p>
                @endif
            </div>
        </div>

        {{-- Milestones & Deliverables --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-tasks me-2"></i>Project Milestones</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">#</th>
                                <th>Milestone Description</th>
                                <th width="120">Amount</th>
                                <th width="120">Due Date</th>
                                <th width="120">Status</th>
                                <th width="140">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->milestones as $index => $ms)
                            <tr>
                                <td class="text-center">
                                    @if($ms->status === 'paid')
                                        <i class="fa fa-check-circle fa-lg text-success"></i>
                                    @elseif($ms->status === 'approved')
                                        <i class="fa fa-check fa-lg text-primary"></i>
                                    @elseif($ms->status === 'submitted')
                                        <i class="fa fa-clock fa-lg text-warning"></i>
                                    @elseif($ms->status === 'disputed')
                                        <i class="fa fa-exclamation-circle fa-lg text-danger"></i>
                                    @else
                                        <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $ms->title }}</strong>
                                    @if($ms->description)
                                    <p class="text-muted small mb-0">{{ $ms->description }}</p>
                                    @endif
                                    @if($ms->escrow_held)
                                    <span class="badge bg-success-subtle text-success small mt-1">
                                        <i class="fa fa-lock me-1"></i>Funds in Escrow
                                    </span>
                                    @endif
                                </td>
                                <td><strong class="text-primary">Nu. {{ number_format($ms->amount) }}</strong></td>
                                <td class="small">{{ $ms->due_date?->format('d M Y') ?? 'Flexible' }}</td>
                                <td>
                                    @php
                                        $msStatus = match($ms->status) {
                                            'paid' => ['class' => 'success', 'label' => 'Paid'],
                                            'approved' => ['class' => 'primary', 'label' => 'Approved'],
                                            'submitted' => ['class' => 'warning text-dark', 'label' => 'Review'],
                                            'disputed' => ['class' => 'danger', 'label' => 'Disputed'],
                                            'in_progress' => ['class' => 'info', 'label' => 'In Progress'],
                                            default => ['class' => 'secondary', 'label' => 'Pending'],
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $msStatus['class'] }}">{{ $msStatus['label'] }}</span>
                                </td>
                                <td>
                                    @if(auth()->user()->id === $contract->freelancer_id && $ms->status === 'pending')
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submitMilestone{{ $ms->id }}">
                                        <i class="fa fa-upload me-1"></i>Submit
                                    </button>
                                    @endif
                                    @if(auth()->user()->id === $contract->poster_id && $ms->status === 'submitted')
                                    <div class="btn-group btn-group-sm">
                                        <form method="POST" action="{{ route('milestones.approve', $ms) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Release payment for this milestone?')">
                                                <i class="fa fa-check me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('milestones.revision', $ms) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fa fa-redo me-1"></i>Revision
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                    @if($ms->status === 'paid')
                                    <small class="text-success"><i class="fa fa-check-double me-1"></i>Complete</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td><strong class="text-primary">Nu. {{ number_format($contract->milestones->sum('amount')) }}</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Payment Terms --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-money-bill-wave me-2"></i>Payment Terms</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small">
                    <li>All payments shall be held in escrow until milestone approval</li>
                    <li>Platform fee of 10% is deducted from total contract value</li>
                    <li>Freelancer receives {{ number_format(($contract->freelancer_amount / $contract->total_amount) * 100, 1) }}% of contract value after fees</li>
                    <li>Payment released within 24-48 hours of milestone approval</li>
                    <li>Refund policy applies as per platform terms for cancelled contracts</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Contract Actions --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-cog me-2"></i>Contract Actions</h6>
            </div>
            <div class="card-body">
                @if($contract->status === 'pending' && auth()->user()->id === $contract->poster_id)
                <div class="alert alert-warning small mb-3">
                    <i class="fa fa-info-circle me-1"></i>
                    Please fund escrow to activate this contract
                </div>
                <form method="POST" action="{{ route('contracts.fund', $contract) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Fund escrow with Nu. {{ number_format($contract->total_amount) }}?')">
                        <i class="fa fa-lock me-1"></i> Fund Escrow (Nu. {{ number_format($contract->total_amount) }})
                    </button>
                </form>
                @endif

                @if($contract->status === 'pending')
                <form method="POST" action="{{ route('contracts.sign', $contract) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 mb-2" {{ (auth()->user()->id === $contract->poster_id && $contract->poster_signed) || (auth()->user()->id === $contract->freelancer_id && $contract->freelancer_signed) ? 'disabled' : '' }}>
                        <i class="fa fa-signature me-1"></i>
                        @if(auth()->user()->id === $contract->poster_id)
                            {{ $contract->poster_signed ? 'Contract Signed ✓' : 'Sign Contract' }}
                        @else
                            {{ $contract->freelancer_signed ? 'Contract Signed ✓' : 'Sign Contract' }}
                        @endif
                    </button>
                </form>
                @endif

                @if(in_array($contract->status, ['pending', 'active']))
                <hr>
                <form method="POST" action="{{ route('contracts.cancel', $contract) }}" onsubmit="return confirm('Cancel this contract? Escrow will be refunded.')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 mb-2">
                        <i class="fa fa-times-circle me-1"></i>Cancel Contract
                    </button>
                </form>
                @endif

                @if($contract->status === 'active')
                <hr>
                <a href="{{ route('disputes.create', $contract) }}" class="btn btn-outline-warning w-100 mb-2">
                    <i class="fa fa-gavel me-1"></i> Raise Dispute
                </a>
                @endif

                @if($contract->status === 'completed' && !$contract->reviews()->where('reviewer_id', auth()->id())->exists())
                <hr>
                <a href="{{ route('reviews.create', $contract) }}" class="btn btn-success w-100">
                    <i class="fa fa-star me-1"></i> Leave Review
                </a>
                @endif
            </div>
        </div>

        {{-- Escrow Status --}}
        @if($contract->status !== 'cancelled')
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-shield-alt me-2"></i>Escrow Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progress</small>
                        <small class="text-muted">{{ $contract->milestones->where('status', 'paid')->count() }}/{{ $contract->milestones->count() }} Milestones</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $contract->milestones->count() > 0 ? ($contract->milestones->where('status', 'paid')->count() / $contract->milestones->count()) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Contract:</span>
                        <strong>Nu. {{ number_format($contract->total_amount) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Released:</span>
                        <strong class="text-success">Nu. {{ number_format($contract->milestones->where('status', 'paid')->sum('amount')) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">In Escrow:</span>
                        <strong class="text-warning">Nu. {{ number_format($contract->milestones->where('escrow_held', true)->sum('amount')) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Communication --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-comments me-2"></i>Communication</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Stay in touch with your {{ auth()->user()->id === $contract->poster_id ? 'freelancer' : 'client' }}</p>
                <a href="{{ route('messages.start') }}" class="btn btn-outline-primary btn-sm w-100"
                   onclick="event.preventDefault(); document.getElementById('contract-msg-form').submit();">
                    <i class="fa fa-envelope me-1"></i>Send Message
                </a>
                <form id="contract-msg-form" method="POST" action="{{ route('messages.start') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ auth()->user()->id === $contract->poster_id ? $contract->freelancer_id : $contract->poster_id }}">
                    <input type="hidden" name="job_id" value="{{ $contract->job_id }}">
                </form>
            </div>
        </div>

        {{-- Contract Info --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fa fa-info-circle me-2"></i>Contract Information</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <strong>Contract ID:</strong><br>
                    <span class="text-muted">{{ $contract->contract_number }}</span>
                </div>
                <div class="mb-2">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $contract->created_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($contract->start_date)
                <div class="mb-2">
                    <strong>Started:</strong><br>
                    <span class="text-muted">{{ $contract->start_date->format('d M Y') }}</span>
                </div>
                @endif
                @if($contract->completed_at)
                <div class="mb-2">
                    <strong>Completed:</strong><br>
                    <span class="text-muted">{{ $contract->completed_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                @if($contract->job)
                <div class="mb-0">
                    <strong>Related Job:</strong><br>
                    <a href="{{ route('jobs.show',$contract->job->slug) }}" class="text-decoration-none">View Job Posting</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
