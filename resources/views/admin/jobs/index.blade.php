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
                            <form method="POST" action="{{ route('admin.jobs.moderate', $job) }}" class="d-inline-block moderate-job-form">@csrf
                                <input type="hidden" name="reason" class="moderate-reason-input">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Moderate" onclick="event.preventDefault(); moderateJob(this.form, '{{ addslashes($job->title) }}')"><i class="fa fa-ban"></i></button>
                            </form>
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
<script>
function moderateJob(form, title){
    const reason = prompt('Enter reason for moderating (closing) the job "' + title + '" (required):');
    if(!reason || !reason.trim()){ alert('A reason is required.'); return; }
    form.querySelector('.moderate-reason-input').value = reason.trim();
    form.submit();
}
</script>
@endsection
