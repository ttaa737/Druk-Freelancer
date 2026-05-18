@extends('layouts.admin')
@section('title', 'Completion Submissions')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Completion Submissions</h4>
        <div class="text-muted small">Review completed work evidence, verify quality, and release payment settlement.</div>
    </div>
    <a href="{{ route('admin.completions.stats') }}" class="btn btn-outline-primary btn-sm">
        <i class="fa fa-chart-line me-1"></i>Statistics
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Pending Review</div>
                <div class="fs-3 fw-bold text-warning">{{ $submissions->where('status', 'pending')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Verified</div>
                <div class="fs-3 fw-bold text-info">{{ $submissions->where('status', 'verified')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Payment Processed</div>
                <div class="fs-3 fw-bold text-success">{{ $submissions->where('status', 'payment_processed')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Rejected</div>
                <div class="fs-3 fw-bold text-danger">{{ $submissions->where('status', 'rejected')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-lg-8">
                <label class="form-label small text-muted text-uppercase fw-semibold">Search</label>
                <input id="searchInput" type="text" class="form-control" placeholder="Contract number, job title, or freelancer name">
            </div>
            <div class="col-lg-4">
                <label class="form-label small text-muted text-uppercase fw-semibold">Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="payment_processed">Payment Processed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    @if($submissions->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="completionTable">
            <thead class="table-light">
                <tr>
                    <th>Contract</th>
                    <th>Freelancer</th>
                    <th>Amount</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $submission->contract->contract_number }}</div>
                        <div class="text-muted small">{{ Str::limit($submission->contract->job->title, 42) }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $submission->freelancer) }}" class="text-decoration-none fw-semibold">
                            {{ $submission->freelancer->name }}
                        </a>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</div>
                        <div class="text-muted small">Fee {{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }}</div>
                    </td>
                    <td class="text-muted small">{{ $submission->submitted_at?->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($submission->isPending())
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($submission->isVerified())
                            <span class="badge bg-info">Verified</span>
                        @elseif($submission->isPaymentProcessed())
                            <span class="badge bg-success">Payment Processed</span>
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
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body border-top py-3">
        {{ $submissions->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="card-body py-5 text-center text-muted">
        <i class="fa fa-clipboard-check fa-2x mb-2 d-block"></i>
        No completion submissions available.
    </div>
    @endif
</div>

@push('scripts')
<script>
(function () {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('completionTable');

    if (!searchInput || !statusFilter || !table) {
        return;
    }

    function filterRows() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value.toLowerCase();
        table.querySelectorAll('tbody tr').forEach((row) => {
            const rowText = row.innerText.toLowerCase();
            const statusText = row.cells[4]?.innerText.toLowerCase() || '';
            const matchSearch = search === '' || rowText.includes(search);
            const matchStatus = status === '' || statusText.includes(status.replace('_', ' '));
            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterRows);
    statusFilter.addEventListener('change', filterRows);
})();
</script>
@endpush
@endsection
