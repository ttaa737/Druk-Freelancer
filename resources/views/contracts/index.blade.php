@extends('layouts.app')
@section('title', 'My Contracts')
@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Contracts</li>
    </ol>
</nav>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="mb-1"><i class="fa fa-file-contract me-2"></i>My Contracts</h3>
        <p class="text-muted small mb-0">View and manage all your active and past contracts</p>
    </div>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ request('status') == null ? 'active' : '' }}" href="{{ route('contracts.index') }}">
            All <span class="badge bg-secondary ms-1">{{ $contracts->total() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('contracts.index', ['status' => 'pending']) }}">
            Pending <span class="badge bg-warning text-dark ms-1">{{ $contracts->where('status', 'pending')->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}" href="{{ route('contracts.index', ['status' => 'active']) }}">
            Active <span class="badge bg-success ms-1">{{ $contracts->where('status', 'active')->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('contracts.index', ['status' => 'completed']) }}">
            Completed <span class="badge bg-primary ms-1">{{ $contracts->where('status', 'completed')->count() }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'disputed' ? 'active' : '' }}" href="{{ route('contracts.index', ['status' => 'disputed']) }}">
            Disputed <span class="badge bg-danger ms-1">{{ $contracts->where('status', 'disputed')->count() }}</span>
        </a>
    </li>
</ul>

@forelse($contracts as $contract)
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="row align-items-start">
            <div class="col-lg-7">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="fa fa-file-contract fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">
                            <a href="{{ route('contracts.show', $contract) }}" class="text-dark text-decoration-none">
                                {{ $contract->job?->title }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-2">Contract #{{ $contract->contract_number }}</p>
                        
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            @if(auth()->user()->id === $contract->poster_id)
                                <span class="text-muted small">
                                    <i class="fa fa-user me-1"></i>
                                    <strong>Freelancer:</strong> {{ $contract->freelancer->name }}
                                </span>
                            @else
                                <span class="text-muted small">
                                    <i class="fa fa-building me-1"></i>
                                    <strong>Client:</strong> {{ $contract->poster->name }}
                                </span>
                            @endif
                            <span class="text-muted small">
                                <i class="fa fa-calendar me-1"></i>
                                {{ $contract->start_date ? $contract->start_date->format('d M Y') : 'Not started' }}
                            </span>
                            @if($contract->deadline)
                            <span class="text-muted small">
                                <i class="fa fa-clock me-1"></i>
                                Due: {{ $contract->deadline->format('d M Y') }}
                            </span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-2 align-items-center">
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
                            <span class="badge bg-{{ $statusConfig['class'] }}">
                                <i class="fa fa-{{ $statusConfig['icon'] }} me-1"></i>
                                {{ ucfirst($contract->status) }}
                            </span>
                            
                            @if($contract->poster_signed && $contract->freelancer_signed)
                            <span class="badge bg-success">
                                <i class="fa fa-check-double me-1"></i>Both Signed
                            </span>
                            @elseif($contract->poster_signed || $contract->freelancer_signed)
                            <span class="badge bg-info">
                                <i class="fa fa-signature me-1"></i>Partially Signed
                            </span>
                            @endif

                            @if($contract->milestones_count > 0)
                            <span class="badge bg-light text-dark border">
                                <i class="fa fa-tasks me-1"></i>
                                {{ $contract->milestones->where('status', 'paid')->count() }}/{{ $contract->milestones_count }} Milestones
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-5 mt-3 mt-lg-0">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Contract Value</small>
                            <strong class="text-primary">Nu. {{ number_format($contract->total_amount) }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <small class="text-muted d-block">Freelancer Gets</small>
                            <strong class="text-success">Nu. {{ number_format($contract->freelancer_amount) }}</strong>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3 justify-content-lg-end">
                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-eye me-1"></i>View Contract
                    </a>
                    @if($contract->status === 'active')
                    <a href="{{ route('messages.start') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-comments me-1"></i>Message
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="fa fa-file-contract text-muted" style="font-size: 4rem;"></i>
        </div>
        <h5 class="mb-2">No Contracts Found</h5>
        <p class="text-muted mb-4">
            {{ request('status') ? 'No ' . request('status') . ' contracts at the moment.' : 'You don\'t have any contracts yet. Start by browsing jobs or reviewing proposals.' }}
        </p>
        @if(auth()->user()->isJobPoster())
        <a href="{{ route('jobs.my') }}" class="btn btn-primary me-2">
            <i class="fa fa-briefcase me-1"></i>My Jobs
        </a>
        @endif
        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">
            <i class="fa fa-search me-1"></i>Browse Jobs
        </a>
    </div>
</div>
@endforelse

<div class="mt-4">
    {{ $contracts->links() }}
</div>

@endsection
