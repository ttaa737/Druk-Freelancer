@extends('layouts.admin')
@section('title', 'Manage Jobs')
@section('content')
<h4 class="fw-bold mb-4">Jobs</h4>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search jobs..." value="{{ request('search') }}"></div>
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['open','in_progress','completed','cancelled','moderated'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>Title</th><th>Poster</th><th>Budget</th><th>Status</th><th>Featured</th><th>Posted</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($jobs as $job)
                <tr class="{{ $job->trashed() ? 'table-danger' : '' }}">
                    <td><div class="small fw-semibold">{{ Str::limit($job->title, 50) }}</div></td>
                    <td><small class="text-muted">{{ $job->poster?->name }}</small></td>
                    <td><small>Nu. {{ number_format($job->budget_min) }}–{{ number_format($job->budget_max) }}</small></td>
                    <td><span class="badge bg-{{ match($job->status){ 'open'=>'success','in_progress'=>'info','completed'=>'primary','cancelled','moderated'=>'danger', default=>'secondary'} }}" style="font-size:10px">{{ ucfirst(str_replace('_',' ',$job->status)) }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.jobs.feature', $job) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $job->is_featured ? 'btn-warning' : 'btn-outline-secondary' }}" title="{{ $job->is_featured ? 'Unfeature' : 'Feature' }}"><i class="fa fa-star" style="font-size:11px"></i></button>
                        </form>
                    </td>
                    <td><small class="text-muted">{{ $job->created_at->format('d M Y') }}</small></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fa fa-eye"></i></a>
                            @if(!$job->trashed())
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Moderate" data-bs-toggle="modal" data-bs-target="#moderateJobModal" data-job-id="{{ $job->id }}" data-job-title="{{ $job->title }}" data-job-route="{{ route('admin.jobs.moderate', $job) }}"><i class="fa fa-ban"></i></button>
                            @else
                            <form method="POST" action="{{ route('admin.jobs.restore', $job) }}">@csrf <button class="btn btn-sm btn-outline-success" title="Restore"><i class="fa fa-undo"></i></button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No jobs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $jobs->withQueryString()->links() }}</div>
</div>

<!-- Moderation Modal -->
<div class="modal fade" id="moderateJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title"><i class="fa fa-ban me-2 text-danger"></i>Moderate Job Posting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="moderateJobForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger small">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will close and hide this job from all listings. This action is permanent.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Job Title</label>
                        <div class="alert alert-light mb-0" id="jobTitleDisplay"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Moderation Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="moderationReason" class="form-control" rows="3" required placeholder="Provide clear details about why this job is being moderated (e.g., policy violation, inappropriate content)..."></textarea>
                        <small class="text-muted">The reason will be logged in the audit trail for record keeping.</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="moderateJobConfirm" required>
                        <label class="form-check-label small" for="moderateJobConfirm">
                            I understand this will permanently close and hide this job
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you absolutely sure you want to moderate this job?')">
                        <i class="fa fa-ban me-1"></i>Moderate Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set up modal when opener button is clicked
document.getElementById('moderateJobModal').addEventListener('show.bs.modal', function (e) {
    const button = e.relatedTarget;
    const jobId = button.getAttribute('data-job-id');
    const jobTitle = button.getAttribute('data-job-title');
    const jobRoute = button.getAttribute('data-job-route');
    
    document.getElementById('jobTitleDisplay').textContent = jobTitle;
    document.getElementById('moderateJobForm').action = jobRoute;
    document.getElementById('moderationReason').value = '';
    document.getElementById('moderateJobConfirm').checked = false;
});
</script>
@endsection
