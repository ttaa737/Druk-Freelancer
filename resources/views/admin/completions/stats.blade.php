@extends('layouts.admin')
@section('title', 'Completion Statistics')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Completion Statistics</h4>
        <div class="text-muted small">Overview of completion verification and settlement outcomes.</div>
    </div>
    <a href="{{ route('admin.completions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i>Back to Submissions
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Pending Review</div>
                <div class="fs-3 fw-bold text-warning">{{ $pending }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Verified</div>
                <div class="fs-3 fw-bold text-info">{{ $verified }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Verified</div>
                <div class="fs-3 fw-bold text-info">{{ $payment_processed }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Rejected</div>
                <div class="fs-3 fw-bold text-danger">{{ $rejected }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Recent Submissions</h6>
        <span class="badge bg-light text-dark border">{{ $recent->count() }} records</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Contract</th>
                    <th>Freelancer</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $submission)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $submission->contract->contract_number }}</div>
                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($submission->contract->job->title, 50) }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $submission->freelancer) }}" class="text-decoration-none fw-semibold">
                            {{ $submission->freelancer->name }}
                        </a>
                    </td>
                    <td class="text-muted small">{{ $submission->submitted_at?->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($submission->status === \App\Models\CompletionSubmission::STATUS_PENDING)
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($submission->status === \App\Models\CompletionSubmission::STATUS_VERIFIED)
                            <span class="badge bg-info">Verified</span>
                        @elseif($submission->status === \App\Models\CompletionSubmission::STATUS_PAYMENT_PROCESSED)
                            <span class="badge bg-info">Verified</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.completions.show', $submission) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No submissions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
