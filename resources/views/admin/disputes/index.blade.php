@extends('layouts.admin')
@section('title', 'Disputes')
@section('content')
<h4 class="fw-bold mb-4">Disputes</h4>

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['open','under_review','resolved','closed'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select name="assigned" class="form-select form-select-sm">
                    <option value="">All Assignments</option>
                    <option value="mine" @selected(request('assigned')==='mine')>Assigned to Me</option>
                    <option value="unassigned" @selected(request('assigned')==='unassigned')>Unassigned</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.disputes.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>Subject</th><th>Contract</th><th>Raised By</th><th>Assigned To</th><th>Status</th><th>Raised</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($disputes as $dispute)
                <tr>
                    <td><div class="small fw-semibold">{{ Str::limit($dispute->subject, 50) }}</div></td>
                    <td><small class="text-muted">{{ $dispute->contract?->title }}</small></td>
                    <td><small class="text-muted">{{ $dispute->raisedBy?->name }}</small></td>
                    <td><small class="{{ $dispute->assignedAdmin ? 'text-dark' : 'text-muted' }}">{{ $dispute->assignedAdmin?->name ?? 'Unassigned' }}</small></td>
                    <td><span class="badge bg-{{ match($dispute->status){ 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'} }}" style="font-size:10px">{{ ucfirst(str_replace('_',' ',$dispute->status)) }}</span></td>
                    <td><small class="text-muted">{{ $dispute->created_at->diffForHumans() }}</small></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.disputes.show', $dispute) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>
                            @if(!$dispute->assigned_admin_id)
                            <form method="POST" action="{{ route('admin.disputes.assign', $dispute) }}">@csrf <button class="btn btn-sm btn-outline-secondary" title="Assign to Me">Claim</button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No disputes found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $disputes->withQueryString()->links() }}</div>
</div>
@endsection
