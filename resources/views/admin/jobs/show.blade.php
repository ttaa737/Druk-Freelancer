@extends('layouts.admin')
@section('title', Str::limit($job->title, 60))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Job Detail</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i> Back</a>
        <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="fa fa-eye me-1"></i> View Public</a>
        @if(!$job->trashed())
        <form method="POST" action="{{ route('admin.jobs.feature', $job) }}">
            @csrf
            <button class="btn btn-sm {{ $job->is_featured ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fa fa-star me-1"></i>{{ $job->is_featured ? 'Unfeature' : 'Feature' }}
            </button>
        </form>
        @if($job->status !== 'moderated')
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#moderateModal">
            <i class="fa fa-ban me-1"></i>Moderate
        </button>
        @else
        <form method="POST" action="{{ route('admin.jobs.restore', $job->id) }}">
            @csrf
            <button class="btn btn-sm btn-success"><i class="fa fa-check me-1"></i> Restore</button>
        </form>
        @endif
        @else
        <form method="POST" action="{{ route('admin.jobs.restore', $job->id) }}">
            @csrf
            <button class="btn btn-sm btn-success"><i class="fa fa-undo me-1"></i> Restore</button>
        </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-semibold">{{ $job->title }}</span>
                @if($job->is_featured)<span class="badge bg-warning text-dark ms-auto">Featured</span>@endif
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">Status</div>
                        <span class="badge bg-{{ match($job->status){ 'open'=>'success','in_progress'=>'info','completed'=>'primary','cancelled','moderated'=>'danger', default=>'secondary'} }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Type</div>
                        <div class="small fw-semibold">{{ ucfirst($job->type) }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Budget</div>
                        <div class="small fw-semibold">Nu. {{ number_format($job->budget_min) }} – {{ number_format($job->budget_max) }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Duration</div>
                        <div class="small fw-semibold">{{ $job->duration_days ? $job->duration_days . ' days' : 'Not specified' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Experience Level</div>
                        <div class="small fw-semibold">{{ ucfirst($job->experience_level ?? 'Any') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Location</div>
                        <div class="small fw-semibold">{{ $job->dzongkhag ?? 'All Bhutan' }} {{ $job->remote_ok ? '(Remote OK)' : '' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Posted</div>
                        <div class="small fw-semibold">{{ $job->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Expires</div>
                        <div class="small fw-semibold">{{ $job->expires_at ? $job->expires_at->format('d M Y') : 'No expiry' }}</div>
                    </div>
                </div>

                @if($job->description)
                <div class="mb-3">
                    <div class="text-muted small mb-1">Description</div>
                    <div class="small">{{ $job->description }}</div>
                </div>
                @endif

                @if($job->requirements)
                <div>
                    <div class="text-muted small mb-1">Requirements</div>
                    <div class="small">{{ $job->requirements }}</div>
                </div>
                @endif

                @if($job->skills->count())
                <div class="mt-3">
                    <div class="text-muted small mb-1">Required Skills</div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($job->skills as $skill)
                        <span class="badge bg-light text-dark border">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Proposals -->
        <div class="card">
            <div class="card-header fw-semibold">Proposals ({{ $job->proposals->count() }})</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Freelancer</th><th>Bid</th><th>Status</th><th>Submitted</th></tr>
                    </thead>
                    <tbody>
                        @forelse($job->proposals()->with('freelancer')->latest()->get() as $proposal)
                        <tr>
                            <td class="small">{{ $proposal->freelancer?->name }}</td>
                            <td class="small">Nu. {{ number_format($proposal->bid_amount) }}</td>
                            <td><span class="badge bg-{{ match($proposal->status){ 'pending'=>'warning','accepted'=>'success','rejected','withdrawn'=>'secondary', default=>'light text-dark'} }}" style="font-size:10px">{{ ucfirst($proposal->status) }}</span></td>
                            <td class="small text-muted">{{ $proposal->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3 small">No proposals yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-semibold">Job Poster</div>
            <div class="card-body text-center">
                <img src="{{ $job->poster?->avatarUrl }}" class="rounded-circle mb-2" style="width:56px;height:56px;object-fit:cover" alt="">
                <div class="fw-semibold">{{ $job->poster?->name }}</div>
                <div class="text-muted small">{{ $job->poster?->email }}</div>
                @if($job->poster)
                <a href="{{ route('admin.users.show', $job->poster) }}" class="btn btn-sm btn-outline-primary mt-2">View User</a>
                @endif
            </div>
        </div>

        @if($job->category)
        <div class="card mt-3">
            <div class="card-header fw-semibold">Category</div>
            <div class="card-body">
                <span class="badge bg-light text-dark border">{{ $job->category->name }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Moderation Modal -->
<div class="modal fade" id="moderateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title"><i class="fa fa-ban me-2 text-danger"></i>Moderate Job Posting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.jobs.moderate', $job) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger small">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will close and hide this job from all listings. This action is permanent.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Moderation Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Provide clear details about why this job is being moderated (e.g., policy violation, inappropriate content)..."></textarea>
                        <small class="text-muted">The reason will be logged in the audit trail for record keeping.</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="moderateConfirm" required>
                        <label class="form-check-label small" for="moderateConfirm">
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
@endsection
