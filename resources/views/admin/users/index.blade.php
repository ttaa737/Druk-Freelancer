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
                            <button type="button" class="btn btn-sm btn-outline-warning" title="Suspend" data-bs-toggle="modal" data-bs-target="#suspendUserModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-route="{{ route('admin.users.suspend', $user) }}"><i class="fa fa-pause"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Ban" data-bs-toggle="modal" data-bs-target="#banUserModal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-route="{{ route('admin.users.ban', $user) }}"><i class="fa fa-ban"></i></button>
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
</div>

<!-- Suspend User Modal -->
<div class="modal fade" id="suspendUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10">
                <h5 class="modal-title"><i class="fa fa-pause me-2 text-warning"></i>Suspend User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="suspendUserForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will temporarily disable the user's account and freeze their wallet. The user can be reactivated later.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">User</label>
                        <div class="alert alert-light mb-0" id="suspendUserName"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Suspension Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="suspendReason" class="form-control" rows="3" placeholder="Be specific about why this user is being suspended..." required></textarea>
                        <small class="text-muted">The user will be notified of this reason.</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="suspendConfirm" required>
                        <label class="form-check-label small" for="suspendConfirm">
                            I understand this will temporarily suspend the user
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to suspend this user?')">
                        <i class="fa fa-pause me-1"></i>Suspend Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ban User Modal -->
<div class="modal fade" id="banUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title"><i class="fa fa-ban me-2 text-danger"></i>Ban User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="banUserForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger small">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action will permanently ban the user and freeze all their funds. This cannot be easily reversed.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">User</label>
                        <div class="alert alert-light mb-0" id="banUserName"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ban Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="banReason" class="form-control" rows="3" placeholder="Provide clear details about terms of service violation or reason for ban..." required></textarea>
                        <small class="text-muted">The user will be notified of this reason.</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="banConfirmCheckbox" required>
                        <label class="form-check-label small" for="banConfirmCheckbox">
                            I understand this will ban the user permanently
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you absolutely sure you want to ban this user? This action is severe.')">
                        <i class="fa fa-ban me-1"></i>Ban User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set up suspend modal when opener button is clicked
document.getElementById('suspendUserModal').addEventListener('show.bs.modal', function (e) {
    const button = e.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const userName = button.getAttribute('data-user-name');
    const userRoute = button.getAttribute('data-user-route');

    document.getElementById('suspendUserName').textContent = userName;
    document.getElementById('suspendUserForm').action = userRoute;
    document.getElementById('suspendReason').value = '';
    document.getElementById('suspendConfirm').checked = false;
});

// Set up ban modal when opener button is clicked
document.getElementById('banUserModal').addEventListener('show.bs.modal', function (e) {
    const button = e.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const userName = button.getAttribute('data-user-name');
    const userRoute = button.getAttribute('data-user-route');

    document.getElementById('banUserName').textContent = userName;
    document.getElementById('banUserForm').action = userRoute;
    document.getElementById('banReason').value = '';
    document.getElementById('banConfirmCheckbox').checked = false;
});
</script>
@endsection
