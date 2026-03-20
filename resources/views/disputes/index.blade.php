@extends('layouts.app')
@section('title', 'My Disputes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="fa fa-gavel me-2"></i>Disputes</h5>
</div>
@forelse($disputes as $dispute)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <div>
                <h6 class="fw-semibold mb-1">{{ $dispute->subject }}</h6>
                <small class="text-muted">Contract: {{ $dispute->contract?->title }} · Raised {{ $dispute->created_at->diffForHumans() }}</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-{{ match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'} }}">
                    {{ ucfirst(str_replace('_',' ',$dispute->status)) }}
                </span>
                <a href="{{ route('disputes.show', $dispute) }}" class="btn btn-sm btn-outline-primary">View</a>
            </div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="fa fa-balance-scale fa-3x mb-3 opacity-25"></i>
    <p>No disputes raised.</p>
</div>
@endforelse
{{ $disputes->links() }}
@endsection
