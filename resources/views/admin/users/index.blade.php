@extends('layouts.admin')
@section('title', 'Manage Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Users</h4>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-4 col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-sm-3 col-md-2">
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    <option value="admin" @selected(request('role')=='admin')>Admin</option>
                    <option value="job_poster" @selected(request('role')=='job_poster')>Job Poster</option>
                    <option value="freelancer" @selected(request('role')=='freelancer')>Freelancer</option>
                </select>
            </div>
            <div class="col-sm-3 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status')=='active')>Active</option>
                    <option value="suspended" @selected(request('status')=='suspended')>Suspended</option>
                    <option value="banned" @selected(request('status')=='banned')>Banned</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr>
                <th>User</th><th>Role</th><th>Status</th><th>Verified</th><th>Joined</th><th class="text-end">Actions</th>
            </tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                            <div>
                                <div class="small fw-semibold">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border" style="font-size:11px">{{ $user->getRoleNames()->first() }}</span></td>
                    <td><span class="badge bg-{{ match($user->status){ 'active'=>'success','suspended'=>'warning','banned'=>'danger', default=>'secondary'} }}">{{ ucfirst($user->status) }}</span></td>
                    <td><span class="badge bg-{{ $user->verification_status === 'verified' ? 'success' : 'secondary' }}" style="font-size:10px">{{ ucfirst($user->verification_status) }}</span></td>
                    <td><small class="text-muted">{{ $user->created_at->format('d M Y') }}</small></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fa fa-eye"></i></a>
                            @if($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}">@csrf
                                <input type="hidden" name="reason" value="Suspended by admin" />
                                <button class="btn btn-sm btn-outline-warning" title="Suspend"><i class="fa fa-pause"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="d-inline-block user-action-form">@csrf
                                <input type="hidden" name="reason" value="" />
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Ban" onclick="openReasonModal(this.form, 'Ban user: {{ addslashes($user->name) }}')"><i class="fa fa-ban"></i></button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.users.activate', $user) }}">@csrf <button class="btn btn-sm btn-outline-success" title="Activate"><i class="fa fa-check"></i></button></form>
                            @endif
                            @if($user->verification_status !== 'verified')
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}">@csrf <button class="btn btn-sm btn-outline-info" title="Mark Verified"><i class="fa fa-id-card"></i></button></form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $users->withQueryString()->links() }}</div>
@include('admin.partials.reason-modal')
</div>
@endsection
