@extends('layouts.app')
@section('title', 'My Proposals')
@section('content')
<h4 class="fw-bold mb-4">My Proposals</h4>
@forelse($proposals as $proposal)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="fw-bold mb-1">
                    <a href="{{ route('jobs.show', $proposal->job->slug) }}" class="text-dark text-decoration-none">{{ $proposal->job->title }}</a>
                </h6>
                <div class="text-muted small mb-2">
                    <span class="me-3"><i class="fa fa-user me-1"></i>{{ $proposal->job->poster->name }}</span>
                    <span class="me-3"><i class="fa fa-clock me-1"></i>{{ $proposal->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-muted small">{{ Str::limit($proposal->cover_letter, 100) }}</p>
            </div>
            <div class="text-end ms-3" style="min-width:140px">
                <div class="fw-bold text-primary mb-1">Nu. {{ number_format($proposal->bid_amount) }}</div>
                <span class="badge bg-{{ $proposal->status === 'pending' ? 'warning text-dark' : ($proposal->status === 'awarded' ? 'success' : ($proposal->status === 'rejected' ? 'danger' : 'secondary')) }}">
                    {{ ucfirst($proposal->status) }}
                </span>
                <div class="mt-2">
                    <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-sm btn-outline-primary">View</a>
                    @if($proposal->status === 'pending')
                    <form method="POST" action="{{ route('proposals.withdraw', $proposal) }}" class="d-inline" onsubmit="return confirm('Withdraw this proposal?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger ms-1">Withdraw</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card text-center py-5">
    <div class="card-body">
        <i class="fa fa-paper-plane fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">You haven't submitted any proposals yet.</h6>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-2">Browse Jobs</a>
    </div>
</div>
@endforelse
{{ $proposals->links() }}
@endsection
