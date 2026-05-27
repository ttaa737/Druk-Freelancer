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
                                <span class="badge bg-info">Verified</span>
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
                <div class="row g-3">
                @forelse($submission->attachments as $attachment)
                    <div class="col-12">
                        <div class="d-flex gap-3 align-items-center border rounded p-3">
                            <div style="width:96px; flex:0 0 96px;">
                                @if(str_starts_with($attachment->file_type, 'image'))
                                    <img src="{{ $attachment->file_url }}?inline=1" alt="{{ $attachment->file_name }}" class="img-fluid rounded" style="max-height:92px; object-fit:cover; width:96px;" />
                                @elseif(str_starts_with($attachment->file_type, 'video'))
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:92px; width:96px;"><i class="fa fa-video fa-2x text-muted"></i></div>
                                @elseif($attachment->file_type === 'application/pdf')
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:92px; width:96px;"><i class="fa fa-file-pdf fa-2x text-danger"></i></div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:92px; width:96px;"><i class="fa fa-file fa-2x text-muted"></i></div>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $attachment->file_name }}</div>
                                <div class="text-muted small">
                                    {{ $attachment->getDocumentTypeLabel() }} • {{ number_format($attachment->file_size / 1024, 1) }} KB
                                </div>
                                @if($attachment->description)
                                    <div class="text-muted small mt-1">{{ $attachment->description }}</div>
                                @endif
                            </div>

                            <div class="text-end">
                                <button class="btn btn-sm btn-outline-secondary mb-2" onclick="showPreview('{{ $attachment->file_url }}?inline=1', '{{ $attachment->file_type }}', '{{ addslashes($attachment->file_name) }}')">
                                    <i class="fa fa-eye me-1"></i>Preview
                                </button>
                                <a href="{{ route('completion.download-attachment', $attachment) }}" class="btn btn-sm btn-outline-primary d-block">
                                    <i class="fa fa-download me-1"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-muted">No evidence files uploaded.</div>
                @endforelse
                </div>
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

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent" style="min-height:300px; display:flex; align-items:center; justify-content:center;">
                <!-- dynamic content -->
            </div>
            <div class="modal-footer">
                <a id="previewDownload" href="#" class="btn btn-primary" target="_blank"><i class="fa fa-download me-1"></i>Download</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showPreview(url, mime, name) {
    const content = document.getElementById('previewContent');
    const title = document.getElementById('previewTitle');
    const download = document.getElementById('previewDownload');
    content.innerHTML = '';
    title.textContent = name || 'Preview';
    // Provide a direct download link (non-inline)
    download.href = url.replace('?inline=1', '') + '';

    if (mime && mime.startsWith('image')) {
        const img = document.createElement('img');
        img.src = url;
        img.className = 'img-fluid rounded';
        img.style.maxHeight = '70vh';
        content.appendChild(img);
    } else if (mime && mime.startsWith('video')) {
        const video = document.createElement('video');
        video.controls = true;
        video.style.maxHeight = '70vh';
        video.style.width = '100%';
        const src = document.createElement('source');
        src.src = url;
        src.type = mime;
        video.appendChild(src);
        content.appendChild(video);
    } else if (mime === 'application/pdf') {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '70vh';
        iframe.frameBorder = 0;
        content.appendChild(iframe);
    } else {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '70vh';
        iframe.frameBorder = 0;
        content.appendChild(iframe);
    }

    const modalEl = document.getElementById('previewModal');
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
}

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
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn?.disabled) {
        return;
    }

    try {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Processing...';
        }

        await submitAction('{{ route("admin.completions.verify", $submission) }}', new FormData(this));
    } catch (error) {
        alert(error.message);
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.dataset.originalText || 'Confirm Verification';
        }
    }
});

document.getElementById('rejectForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn?.disabled) {
        return;
    }

    try {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Processing...';
        }

        await submitAction('{{ route("admin.completions.reject", $submission) }}', new FormData(this));
    } catch (error) {
        alert(error.message);
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.dataset.originalText || 'Confirm Rejection';
        }
    }
});
</script>
@endpush
@endsection
