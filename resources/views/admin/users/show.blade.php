@extends('layouts.admin')
@section('title', $user->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
    <div class="d-flex gap-2">
        @if($user->status === 'active')
        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
            <i class="fa fa-pause me-1"></i>Suspend
        </button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#banModal">
            <i class="fa fa-ban me-1"></i>Ban
        </button>
        @else
        <form method="POST" action="{{ route('admin.users.activate', $user) }}">@csrf <button class="btn btn-sm btn-success"><i class="fa fa-check me-1"></i>Activate</button></form>
        @endif
    </div>

    <!-- Suspend Modal -->
    <div class="modal fade" id="suspendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning bg-opacity-10">
                    <h5 class="modal-title"><i class="fa fa-pause me-2 text-warning"></i>Suspend User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning small">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action will temporarily disable the user's account and freeze their wallet. The user can be reactivated later.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Suspension Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Be specific about why this user is being suspended..."></textarea>
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

    <!-- Ban Modal -->
    <div class="modal fade" id="banModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-opacity-10">
                    <h5 class="modal-title"><i class="fa fa-ban me-2 text-danger"></i>Ban User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger small">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action will permanently ban the user and freeze all their funds. This cannot be easily reversed.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ban Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Provide clear details about terms of service violation or reason for ban..."></textarea>
                            <small class="text-muted">The user will be notified of this reason.</small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="banConfirm" required>
                            <label class="form-check-label small" for="banConfirm">
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
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-4 text-center">
            <div class="card-body pt-4">
                <img src="{{ $user->avatar_url ? Storage::url($user->avatar_url) : asset('img/default-avatar.png') }}" class="rounded-circle mb-2" style="width:80px;height:80px;object-fit:cover;">
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <small class="text-muted">{{ $user->email }}</small>
                <div class="mt-2 d-flex justify-content-center gap-1">
                    <span class="badge bg-{{ match($user->status){ 'active'=>'success','suspended'=>'warning','banned'=>'danger', default=>'secondary'} }}">{{ ucfirst($user->status) }}</span>
                    <span class="badge bg-light text-dark border">{{ $user->getRoleNames()->first() }}</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-bold">Wallet</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Available</span><span class="fw-semibold">Nu. {{ number_format($user->wallet?->available_balance ?? 0) }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Escrow</span><span>Nu. {{ number_format($user->wallet?->escrow_balance ?? 0) }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Total Earned</span><span>Nu. {{ number_format($user->wallet?->total_earned ?? 0) }}</span></li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <!-- Verification Documents -->
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
                <span><i class="fa fa-file-alt me-2 text-primary"></i>Verification Documents</span>
                @php
                    $pendingDocs = $user->verificationDocuments->where('status', 'pending')->count();
                    $approvedDocs = $user->verificationDocuments->where('status', 'approved')->count();
                @endphp
                @if($pendingDocs > 0)
                    <span class="badge bg-warning">{{ $pendingDocs }} Pending</span>
                @endif
                @if($approvedDocs > 0)
                    <span class="badge bg-success">{{ $approvedDocs }} Approved</span>
                @endif
            </div>
            @forelse($user->verificationDocuments as $doc)
            <div class="list-group-item d-md-flex align-items-center justify-content-between gap-3 p-3 border-bottom {{ $doc->status === 'pending' ? 'bg-warning bg-opacity-5' : '' }}">
                <div class="mb-2 mb-md-0">
                    <div class="small fw-semibold">
                        @switch($doc->document_type)
                            @case('cid') 🆔 Citizenship ID (CID) @break
                            @case('brn') 🏢 Business Registration Number @break
                            @case('license') 📜 Professional License @break
                            @case('education') 🎓 Education Certificate @break
                            @case('tax_certificate') 💼 Tax Clearance @break
                            @default {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                        @endswitch
                    </div>
                    <div class="text-muted" style="font-size:11px">
                        Submitted {{ $doc->created_at->format('d M Y, h:i A') }}
                        @if($doc->document_number)
                            • Doc #: {{ $doc->document_number }}
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                        <i class="fa fa-{{ $doc->status === 'approved' ? 'check-circle' : ($doc->status === 'rejected' ? 'times-circle' : 'clock') }} me-1"></i>
                        {{ ucfirst($doc->status) }}
                    </span>
                    @if($doc->status === 'pending')
                        <a href="{{ route('admin.verifications.show', $doc) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>Review
                        </a>
                        <form method="POST" action="{{ route('admin.verifications.approve', $doc) }}" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Approve this document?')">
                                <i class="fa fa-check me-1"></i>Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $doc->id }}" title="Reject">
                            <i class="fa fa-times me-1"></i>Reject
                        </button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $doc->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger bg-opacity-10">
                                        <h5 class="modal-title">Reject Document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.verifications.reject', $doc) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted small mb-3">Provide a clear, specific reason for rejection. The user will see this message.</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                                <textarea name="reason" class="form-control" rows="3" required placeholder="e.g., Document is blurred, expired, or doesn't match profile information"></textarea>
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
                    @elseif($doc->status === 'rejected')
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View Document">
                            <i class="fa fa-file me-1"></i>View
                        </a>
                        <small class="text-danger">{{ $doc->rejection_reason }}</small>
                    @else
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-file me-1"></i>View
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="card-body text-muted small text-center py-4">
                <i class="fa fa-inbox fa-2x mb-2 opacity-50"></i>
                <div>No documents uploaded yet.</div>
            </div>
            @endforelse
        </div>

        <!-- Recent Contracts -->
        <div class="card">
            <div class="card-header fw-bold">Recent Contracts</div>
            @php
                $contracts = $user->contractsAsPoster->merge($user->contractsAsFreelancer)->sortByDesc('created_at');
            @endphp
            @forelse($contracts->take(5) as $contract)
            <div class="list-group-item d-flex justify-content-between align-items-center p-3 border-bottom">
                <div>
                    <div class="small fw-semibold">{{ $contract->title }}</div>
                    <div class="text-muted" style="font-size:11px">{{ $contract->created_at->format('d M Y') }}</div>
                </div>
                <span class="badge bg-{{ match($contract->status){ 'active'=>'success','completed'=>'primary','cancelled'=>'danger','disputed'=>'warning', default=>'secondary'} }}">{{ ucfirst($contract->status) }}</span>
            </div>
            @empty
            <div class="card-body text-muted small text-center">No contracts.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips for better UX
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el));
    });
</script>
@endpush
