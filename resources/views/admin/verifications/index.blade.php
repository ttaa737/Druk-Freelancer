@extends('layouts.admin')
@section('title', 'Verifications')
@section('content')
<h4 class="fw-bold mb-4">Verifications</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2">
            <div class="col-sm-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status')==='pending' || !request('status'))>Pending Review</option>
                    <option value="approved" @selected(request('status')==='approved')>Approved</option>
                    <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.verifications.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Pending</th>
                    <th>Approved</th>
                    <th>Rejected</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $userDocs = $user->verificationDocuments;
                    $pendingDocs = $userDocs->where('status', 'pending')->count();
                    $approvedDocs = $userDocs->where('status', 'approved')->count();
                    $rejectedDocs = $userDocs->where('status', 'rejected')->count();
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 class="rounded-circle" 
                                 style="width:32px;height:32px;object-fit:cover;"
                                 alt="User avatar">
                            <span class="small fw-semibold">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td><small class="text-muted">{{ $user->email }}</small></td>
                    <td><small>{{ ucfirst($user->role) }}</small></td>
                    <td>
                        @if($pendingDocs > 0)
                            <span class="badge bg-warning text-dark" style="font-size:10px">{{ $pendingDocs }}</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($approvedDocs > 0)
                            <span class="badge bg-success" style="font-size:10px">{{ $approvedDocs }}</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($rejectedDocs > 0)
                            <span class="badge bg-danger" style="font-size:10px">{{ $rejectedDocs }}</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.verifications.show', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No verification submissions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
