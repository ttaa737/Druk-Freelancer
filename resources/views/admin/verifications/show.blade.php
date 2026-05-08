@extends('layouts.admin')
@section('title', 'Verification Review - ' . $user->name)

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Verification Review</h4>
    <a href="{{ route('admin.verifications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i>Back
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4">
    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- User Info Card --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <div class="row align-items-center g-3">
            <div class="col-auto">
                <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" 
                     class="rounded-circle border" 
                     style="width:64px;height:64px;object-fit:cover" 
                     alt="User avatar">
            </div>
            <div class="col">
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                <div class="gap-2 d-flex flex-wrap">
                    <span class="badge bg-{{ $user->role === 'freelancer' ? 'info' : 'warning' }} text-white">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="badge bg-{{ match($user->verification_status ?? 'unverified'){ 'verified'=>'success','pending'=>'warning', default=>'secondary'} }} text-white">
                        {{ ucfirst($user->verification_status ?? 'Unverified') }}
                    </span>
                </div>
            </div>
            <div class="col-lg-auto ms-lg-auto">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fa fa-user me-1"></i>Profile
                </a>
                <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                    <i class="fa fa-external-link-alt me-1"></i>Public
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Documents Grid --}}
<div class="row g-3">
    @php
        $documentsByType = $user->verificationDocuments->groupBy('document_type');
    @endphp
    
    @forelse($documentsByType as $docType => $documents)
        @php
            $docTypeLabel = match($docType) {
                'cid' => 'Citizenship ID (CID)',
                'license' => 'Professional License',
                'brn' => 'Business Registration Number',
                'education' => 'Education Certificate',
                'tax_certificate' => 'Tax Clearance Certificate',
                default => ucfirst(str_replace('_', ' ', $docType))
            };
        @endphp
        
        @foreach($documents as $document)
        <div class="col-lg-6">
            <div class="card">
                {{-- Header --}}
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <span class="fw-semibold small">{{ $docTypeLabel }}</span>
                    <span class="badge bg-{{ $document->status === 'approved' ? 'success' : ($document->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                        {{ ucfirst($document->status) }}
                    </span>
                </div>

                {{-- Document Preview --}}
                @php
                    $ext = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION));
                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                @endphp
                
                <div style="height:200px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">
                    @if($document->file_path)
                        @if(in_array($ext, $imageExts))
                        <img src="{{ asset('storage/' . $document->file_path) }}" 
                             class="img-fluid" 
                             style="max-height:100%; max-width:100%; object-fit:contain;"
                             alt="Document preview">
                        @else
                        <div class="text-center">
                            <i class="fa fa-file-pdf fa-3x text-danger mb-2"></i>
                            <p class="text-muted small mb-0">{{ strtoupper($ext) }}</p>
                        </div>
                        @endif
                    @endif
                </div>

                {{-- Info --}}
                <div class="card-body p-3 border-top">
                    <div class="row g-2 small">
                        @if($document->document_number)
                        <div class="col-6">
                            <div class="text-muted small mb-1">Doc Number</div>
                            <div class="fw-semibold font-monospace small">{{ $document->document_number }}</div>
                        </div>
                        @endif
                        <div class="col-6">
                            <div class="text-muted small mb-1">Submitted</div>
                            <div class="fw-semibold small">{{ $document->created_at->format('d M Y') }}</div>
                        </div>
                        @if($document->rejection_reason)
                        <div class="col-12">
                            <div class="text-muted small mb-1">Reason</div>
                            <div class="p-2 bg-danger bg-opacity-10 rounded text-danger small">
                                {{ $document->rejection_reason }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                @if($document->status === 'pending')
                <div class="card-footer bg-light border-top p-2">
                    <div class="d-flex gap-2">
                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                           class="btn btn-sm btn-outline-secondary flex-grow-1" 
                           target="_blank">
                            <i class="fa fa-external-link-alt me-1"></i>Open
                        </a>
                        <form method="POST" action="{{ route('admin.verifications.approve', $document) }}" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Approve?')">
                                <i class="fa fa-check me-1"></i>Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger flex-grow-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal{{ $document->id }}">
                            <i class="fa fa-times me-1"></i>Reject
                        </button>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header py-2">
                                <h6 class="modal-title">Reject Document</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.verifications.reject', $document) }}">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label small fw-semibold">Reason</label>
                                    <textarea name="reason" class="form-control form-control-sm" rows="3" required 
                                              placeholder="Be specific..."></textarea>
                                </div>
                                <div class="modal-footer py-2">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="card-footer text-center text-muted small p-2">
                    {{ $document->status === 'approved' ? '✓ Approved' : '✗ Rejected' }} • {{ $document->reviewed_at?->format('d M Y') }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fa fa-info-circle me-2"></i>No verification documents submitted yet.
        </div>
    </div>
    @endforelse
</div>

@endsection
