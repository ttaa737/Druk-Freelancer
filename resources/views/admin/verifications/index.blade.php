@extends('layouts.admin')
@section('title', 'Verifications')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa fa-shield-alt text-primary me-2"></i>Verification Queue</h4>
        <p class="text-muted small mb-0">Review and approve user verification documents</p>
    </div>
    <div class="d-flex gap-2">
        @php
            $pendingCount = \App\Models\VerificationDocument::where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
            <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fa fa-clock me-1"></i>{{ $pendingCount }} Pending
            </span>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card mb-4 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status')==='pending' || !request('status'))>⏱️ Pending</option>
                    <option value="approved" @selected(request('status')==='approved')>✅ Approved</option>
                    <option value="rejected" @selected(request('status')==='rejected')>❌ Rejected</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Document Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="cid" @selected(request('type')==='cid')>🆔 CID / Passport</option>
                    <option value="license" @selected(request('type')==='license')>📜 Professional License</option>
                    <option value="brn" @selected(request('type')==='brn')>🏢 BRN</option>
                    <option value="education" @selected(request('type')==='education')>🎓 Education</option>
                    <option value="tax_certificate" @selected(request('type')==='tax_certificate')>💼 Tax Certificate</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="fa fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.verifications.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-redo me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:5%">#</th>
                    <th style="width:25%">User</th>
                    <th style="width:15%">Document Type</th>
                    <th style="width:12%">Document #</th>
                    <th style="width:15%">Submitted</th>
                    <th style="width:10%">Status</th>
                    <th class="text-end" style="width:18%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr class="{{ $doc->status === 'pending' ? 'table-warning table-warning-subtle' : '' }}">
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $doc->user?->avatar_url ?? asset('images/default-avatar.png') }}" 
                                 class="rounded-circle" 
                                 style="width:36px;height:36px;object-fit:cover;"
                                 alt="User avatar">
                            <div>
                                <div class="fw-semibold">{{ $doc->user?->name }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $doc->user?->email }}</div>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $doc->user?->role === 'freelancer' ? 'info' : 'warning' }}" style="font-size:9px">
                                        {{ ucfirst($doc->user?->role ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1">
                            @switch($doc->document_type)
                                @case('cid') 🆔 CID @break
                                @case('license') 📜 License @break
                                @case('brn') 🏢 BRN @break
                                @case('education') 🎓 Education @break
                                @case('tax_certificate') 💼 Tax Cert @break
                                @default {{ ucfirst($doc->document_type) }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <span class="font-monospace small text-muted">{{ $doc->document_number ?? '—' }}</span>
                    </td>
                    <td>
                        <div class="small">{{ $doc->created_at->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:10px">{{ $doc->created_at->format('h:i A') }}</div>
                        <div class="text-muted" style="font-size:10px">{{ $doc->created_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                            <i class="fa fa-{{ $doc->status === 'approved' ? 'check-circle' : ($doc->status === 'rejected' ? 'times-circle' : 'clock') }} me-1"></i>
                            {{ ucfirst($doc->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.verifications.show', $doc) }}" 
                               class="btn btn-outline-primary"
                               title="View Details">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" 
                               target="_blank" 
                               class="btn btn-outline-secondary"
                               title="View Document">
                                <i class="fa fa-file-alt"></i>
                            </a>
                        </div>
                        @if($doc->status === 'pending')
                        <div class="btn-group btn-group-sm ms-1">
                            <form method="POST" action="{{ route('admin.verifications.approve', $doc) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-success" title="Approve" onclick="return confirm('Approve this document?')">
                                    <i class="fa fa-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $doc->id }}"
                                    title="Reject">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        {{-- Reject Modal --}}
                        <div class="modal fade" id="rejectModal{{ $doc->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.verifications.reject', $doc) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted small">Provide a clear reason for rejection. The user will see this message.</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                                <textarea name="reason" class="form-control" rows="3" required 
                                                          placeholder="e.g., Document is blurred, expired, or doesn't match profile information"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times me-1"></i>Reject Document
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                            @if($doc->rejection_reason)
                                <button class="btn btn-sm btn-outline-info ms-1" 
                                        data-bs-toggle="tooltip" 
                                        title="{{ $doc->rejection_reason }}">
                                    <i class="fa fa-info-circle"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fa fa-inbox fa-3x mb-3 opacity-25"></i>
                        <div>No verification documents found.</div>
                        <small>Documents will appear here when users submit them for verification.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents->hasPages())
    <div class="card-footer bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} documents
            </div>
            <div>{{ $documents->withQueryString()->links() }}</div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection
