@extends('layouts.app')
@section('title', 'Raise a Dispute')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1"><i class="fa fa-gavel me-2 text-danger"></i>Raise a Dispute</h5>
                <p class="text-muted small mb-4">Disputes are reviewed by our admin team. Please provide clear evidence to support your claim.</p>

                <div class="alert alert-warning small mb-4">
                    <i class="fa fa-info-circle me-1"></i>This will freeze the escrow funds until the dispute is resolved. Use this only if you cannot resolve the issue directly with the other party.
                </div>

                <form method="POST" action="{{ route('disputes.store', $contract) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @if($contract->milestones->count())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Related Milestone</label>
                        <select name="milestone_id" class="form-select">
                            <option value="">None / Entire Contract</option>
                            @foreach($contract->milestones as $ms)
                            <option value="{{ $ms->id }}" @selected(old('milestone_id') == $ms->id)>{{ $ms->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Evidence Files <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="file" name="evidence_files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.zip">
                        <div class="form-text">Max 5 files, 10MB each. Accepted: PDF, JPG, PNG, ZIP</div>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-gavel me-1"></i>Submit Dispute</button>
                    <a href="{{ route('contracts.show', $contract) }}" class="btn btn-link text-muted">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
