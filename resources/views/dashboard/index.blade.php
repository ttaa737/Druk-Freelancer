@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">Welcome back, {{ auth()->user()->name }}! 👋</h4>
        <p class="text-muted">Here's what's happening on your account.</p>
    </div>
</div>

{{-- ── Freelancer Dashboard ── --}}
@if(auth()->user()->hasRole('freelancer'))
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Active Contracts','value'=>$stats['active_contracts'],'icon'=>'fa-file-contract','color'=>'primary'],
        ['label'=>'Pending Proposals','value'=>$stats['pending_proposals'],'icon'=>'fa-paper-plane','color'=>'warning'],
        ['label'=>'Completed','value'=>$stats['completed_contracts'],'icon'=>'fa-check-circle','color'=>'success'],
        ['label'=>'Total Earned','value'=>'Nu. '.number_format($stats['total_earned']),'icon'=>'fa-coins','color'=>'info'],
    ] as $s)
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ $s['color'] }} bg-opacity-10 p-3">
                    <i class="fa {{ $s['icon'] }} text-{{ $s['color'] }} fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $s['value'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-wallet me-2 text-warning"></i>Wallet</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Available</span>
                    <span class="fw-semibold">Nu. {{ number_format($stats['available_balance']) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">In Escrow</span>
                    <span class="fw-semibold text-warning">Nu. {{ number_format($stats['escrow_balance']) }}</span>
                </div>
                <a href="{{ route('wallet.index') }}" class="btn btn-outline-primary btn-sm w-100">Manage Wallet</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-star me-2 text-warning"></i>Rating</h6>
                <div class="display-6 fw-bold">{{ number_format($stats['average_rating'] ?? 0, 1) }}</div>
                <div class="text-muted small">out of 5.0</div>
                <div class="mt-2">
                    @for($i=1; $i<=5; $i++)
                        <i class="fa fa-star {{ $i <= round($stats['average_rating'] ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                </div>
                <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-secondary btn-sm w-100 mt-3">View Profile</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-search me-2 text-primary"></i>Find Work</h6>
                <p class="text-muted small">Browse the latest job postings and submit your proposals.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm w-100">Browse Jobs</a>
                <a href="{{ route('proposals.my') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">My Proposals</a>
            </div>
        </div>
    </div>
</div>

{{-- ── Job Poster Dashboard ── --}}
@elseif(auth()->user()->hasRole('job_poster'))
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Active Jobs','value'=>$stats['active_jobs'],'icon'=>'fa-briefcase','color'=>'primary'],
        ['label'=>'Pending Proposals','value'=>$stats['pending_proposals'],'icon'=>'fa-inbox','color'=>'warning'],
        ['label'=>'Active Contracts','value'=>$stats['active_contracts'],'icon'=>'fa-file-contract','color'=>'info'],
        ['label'=>'Total Spent','value'=>'Nu. '.number_format($stats['total_spent']),'icon'=>'fa-coins','color'=>'danger'],
    ] as $s)
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ $s['color'] }} bg-opacity-10 p-3">
                    <i class="fa {{ $s['icon'] }} text-{{ $s['color'] }} fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $s['value'] }}</div>
                    <div class="text-muted small">{{ $s['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-plus me-2 text-primary"></i>Post a New Job</h6>
                <p class="text-muted small">Find the right talent for your project across Bhutan.</p>
                <a href="{{ route('jobs.create') }}" class="btn btn-primary">Post a Job</a>
                <a href="{{ route('jobs.my') }}" class="btn btn-outline-secondary ms-2">My Jobs</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-wallet me-2 text-warning"></i>Wallet</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Available Balance</span>
                    <span class="fw-semibold">Nu. {{ number_format(auth()->user()->wallet?->available_balance ?? 0) }}</span>
                </div>
                <a href="{{ route('wallet.deposit.form') }}" class="btn btn-success btn-sm me-2">Deposit Funds</a>
                <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary btn-sm">View Wallet</a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
