@extends('layouts.admin')
@section('title', 'Verify Completion')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Completion Review</h4>
        <div class="text-muted small">Contract {{ $submission->contract->contract_number }}</div>
    </div>
    <a href="{{ route('admin.completions.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Submission Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Status</div>
                        <div class="mt-1">
                            @if($submission->isPending())
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($submission->isVerified())
                                <span class="badge bg-info">Verified</span>
                            @elseif($submission->isPaymentProcessed())
                                <span class="badge bg-success">Payment Processed</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Submitted At</div>
                        <div class="fw-semibold mt-1">{{ $submission->submitted_at?->format('d M Y, h:i A') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Freelancer</div>
                        <a href="{{ route('admin.users.show', $submission->freelancer) }}" class="fw-semibold text-decoration-none mt-1 d-inline-block">
                            {{ $submission->freelancer->name }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Job Poster</div>
                        <a href="{{ route('admin.users.show', $submission->contract->poster) }}" class="fw-semibold text-decoration-none mt-1 d-inline-block">
                            {{ $submission->contract->poster->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Freelancer Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted" style="white-space: pre-wrap;">{{ $submission->submission_notes }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Evidence Files</h6>
                <span class="badge bg-light text-dark border">{{ $submission->attachments->count() }} files</span>
            </div>
            <div class="card-body">
                @forelse($submission->attachments as $attachment)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $attachment->file_name }}</div>
                            <div class="text-muted small">
                                {{ $attachment->getDocumentTypeLabel() }} • {{ number_format($attachment->file_size / 1024, 1) }} KB
                            </div>
                            @if($attachment->description)
                            <div class="text-muted small mt-1">{{ $attachment->description }}</div>
                            @endif
                        </div>
                        <a href="{{ route('completion.download-attachment', $attachment) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-download me-1"></i>Download
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-muted">No evidence files uploaded.</div>
                @endforelse
            </div>
        </div>

        @if($submission->isRejected())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Rejection Reason</div>
            <div>{{ $submission->rejection_reason }}</div>
            <div class="small mt-2">Rejected at {{ $submission->rejected_at?->format('d M Y, h:i A') }}</div>
        </div>
        @endif

        @if($submission->isPaymentProcessed())
        <div class="alert alert-success">
            <div class="fw-semibold mb-1">Payment Processed</div>
            <div class="small">Verified at {{ $submission->verified_at?->format('d M Y, h:i A') }}</div>
            <div class="small">Processed at {{ $submission->payment_processed_at?->format('d M Y, h:i A') }}</div>
        </div>
        @endif
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Payment Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Contract Total</span>
                    <strong>{{ config('platform.currency') }} {{ number_format($submission->contract->total_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Freelancer Receives</span>
                    <strong class="text-success">{{ config('platform.currency') }} {{ number_format($submission->contract->freelancer_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Platform Fee</span>
                    <strong class="text-primary">{{ config('platform.currency') }} {{ number_format($submission->contract->platform_fee, 2) }}</strong>
                </div>
                <hr>
                <small class="text-muted">On approval, settlement is processed and all involved parties are notified.</small>
            </div>
        </div>

        @if($submission->isPending())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Verification Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fa fa-check me-1"></i>Verify & Process Payment
                </button>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fa fa-ban me-1"></i>Reject Submission
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Completion & Process Settlement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success small">
                        This will verify completion, release payment to freelancer, and post platform fee transaction.
                    </div>
                    <label class="form-label">Verification Notes (optional)</label>
                    <textarea name="verification_notes" rows="4" class="form-control" placeholder="Internal verification notes..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Completion Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" rows="4" class="form-control" required minlength="20" placeholder="Explain what needs correction..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function submitAction(url, formData) {
    const response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    const data = await response.json();
    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Request failed');
    }

    alert(data.message);
    window.location.reload();
}

document.getElementById('approveForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    try {
        await submitAction('{{ route("admin.completions.verify", $submission) }}', new FormData(this));
    } catch (error) {
        alert(error.message);
    }
});

document.getElementById('rejectForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    try {
        await submitAction('{{ route("admin.completions.reject", $submission) }}', new FormData(this));
    } catch (error) {
        alert(error.message);
    }
});
</script>
@endpush
@endsection
