@extends('layouts.app')
@section('title', 'Submit Completion - ' . $contract->contract_number)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Submit Project Completion</h4>
            <div class="text-muted small">Contract {{ $contract->contract_number }} • {{ $contract->job->title }}</div>
        </div>
        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left me-1"></i>Back to Contract
        </a>
    </div>

    @if($existingSubmission && $existingSubmission->isRejected())
    <div class="alert alert-warning border mb-4">
        <div class="fw-semibold mb-1"><i class="fa fa-exclamation-triangle me-1"></i>Previous Submission Rejected</div>
        <div class="small"><strong>Admin feedback:</strong> {{ $existingSubmission->rejection_reason }}</div>
    </div>
    @endif

    <form id="completionForm" method="POST" action="{{ route('completion.store', $contract) }}" enctype="multipart/form-data">
        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Completion Notes</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Describe what was completed and how deliverables satisfy contract scope.</p>
                <textarea id="submission_notes" name="submission_notes" rows="6" class="form-control" minlength="20" maxlength="2000" required placeholder="Write a clear completion summary..."></textarea>
                <div class="form-text">Minimum 20 characters, maximum 2000 characters.</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Evidence & Documents</h6>
                <button type="button" id="addAttachment" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus me-1"></i>Add File
                </button>
            </div>
            <div class="card-body">
                <div id="attachmentsContainer" class="d-grid gap-3"></div>
                <div class="form-text mt-2">Upload up to 10 files. Max 10MB each (PDF, Office, Images, ZIP, MP4, WEBM).</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Settlement Summary</h6>
            </div>
            <div class="card-body">
                <div class="row g-3 small">
                    <div class="col-md-3">
                        <div class="text-muted">Contract Total</div>
                        <div class="fw-semibold">{{ config('platform.currency') }} {{ number_format($contract->total_amount, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Platform Fee</div>
                        <div class="fw-semibold">{{ config('platform.currency') }} {{ number_format($contract->platform_fee, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Freelancer Receives</div>
                        <div class="fw-semibold text-success">{{ config('platform.currency') }} {{ number_format($contract->freelancer_amount, 2) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted">Deadline</div>
                        <div class="fw-semibold">{{ $contract->deadline?->format('d M Y') ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('contracts.show', $contract) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <i class="fa fa-paper-plane me-1"></i>Submit Completion Evidence
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const container = document.getElementById('attachmentsContainer');
    const addBtn = document.getElementById('addAttachment');
    const form = document.getElementById('completionForm');
    const submitBtn = document.getElementById('submitBtn');
    const maxAttachments = 10;
    let count = 0;

    function createAttachmentRow(index) {
        return `
            <div class="border rounded p-3 attachment-item" data-index="${index}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Document Type <span class="text-danger">*</span></label>
                        <select name="attachments[${index}][document_type]" class="form-select" required>
                            <option value="">Select type...</option>
                            <option value="evidence">Evidence</option>
                            <option value="report">Report</option>
                            <option value="deliverable">Deliverable</option>
                            <option value="screenshot">Screenshot</option>
                            <option value="video">Video</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">File <span class="text-danger">*</span></label>
                        <input type="file" name="attachments[${index}][file]" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.zip,.mp4,.webm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Description (Optional)</label>
                        <input type="text" name="attachments[${index}][description]" class="form-control" maxlength="500" placeholder="Short description">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-attachment" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function addAttachment() {
        if (container.querySelectorAll('.attachment-item').length >= maxAttachments) {
            alert(`Maximum ${maxAttachments} attachments allowed.`);
            return;
        }
        container.insertAdjacentHTML('beforeend', createAttachmentRow(count));
        count += 1;
        refreshRemoveButtons();
    }

    function refreshRemoveButtons() {
        const rows = container.querySelectorAll('.attachment-item');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-attachment');
            btn.style.visibility = rows.length > 1 ? 'visible' : 'hidden';
        });
    }

    addBtn.addEventListener('click', addAttachment);

    container.addEventListener('click', (e) => {
        const button = e.target.closest('.remove-attachment');
        if (!button) return;
        button.closest('.attachment-item').remove();
        refreshRemoveButtons();
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = new FormData(form);
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Submitting...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: data,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Submission failed.');
            }

            alert(payload.message);
            window.location.href = payload.redirect;
        } catch (error) {
            alert(error.message || 'Submission failed.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa fa-paper-plane me-1"></i>Submit Completion Evidence';
        }
    });

    addAttachment();
})();
</script>
@endpush
@endsection
