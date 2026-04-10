@extends('layouts.app')
@section('title', 'Dispute #' . $dispute->id)
@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Dispute Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $dispute->subject }}</h5>
                        <small class="text-muted">Contract: {{ $dispute->contract?->title }} · Raised {{ $dispute->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge fs-6 bg-{{ match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'} }}">{{ ucfirst(str_replace('_',' ',$dispute->status)) }}</span>
                </div>
                <hr>
                <p class="text-dark mb-0" style="white-space:pre-line">{{ $dispute->description }}</p>
                @if($dispute->resolution_note)
                <div class="alert alert-info mt-3 mb-0"><strong>Resolution:</strong> {{ $dispute->resolution_note }}</div>
                @endif
            </div>
        </div>

        <!-- Evidence Files -->
        @if($dispute->evidence && $dispute->evidence->count())
        <div class="card mb-4">
            <div class="card-header fw-bold">Evidence Files</div>
            <ul class="list-group list-group-flush">
                @foreach($dispute->evidence as $file)
                <li class="list-group-item d-flex align-items-center gap-3">
                    <i class="fa fa-file text-secondary"></i>
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">{{ $file->original_name ?? basename($file->file_path) }}</div>
                        <div class="text-muted" style="font-size:11px">Uploaded by {{ $file->submittedBy?->name }} · {{ $file->created_at->format('d M Y') }}</div>
                    </div>
                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Add Evidence -->
        @if(in_array($dispute->status, ['open','under_review']))
        <div class="card mb-4">
            <div class="card-header fw-bold">Add Evidence</div>
            <div class="card-body">
                <form method="POST" action="{{ route('disputes.evidence', $dispute) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <input type="file" name="files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.zip" required>
                    </div>
                    <button class="btn btn-sm btn-primary">Upload</button>
                </form>
            </div>
        </div>
        @endif

        <!-- Comment Thread -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Comments</div>
            @forelse($dispute->comments as $comment)
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="{{ $comment->user?->avatar_url ? Storage::url($comment->user->avatar_url) : asset('img/default-avatar.png') }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
                    <div>
                        <div class="d-flex gap-2 align-items-center mb-1">
                            <span class="fw-semibold small">{{ $comment->user?->name }}</span>
                            @if($comment->user?->hasRole('admin'))<span class="badge bg-danger" style="font-size:9px">Admin</span>@endif
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 small">{{ $comment->comment }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="card-body text-muted small text-center">No comments yet.</div>
            @endforelse
            @if(in_array($dispute->status, ['open','under_review']))
            <div class="card-body pt-0">
                <form method="POST" action="{{ route('disputes.comment', $dispute) }}">
                    @csrf
                    <div class="input-group mt-3">
                        <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                        <button class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Dispute Info</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Status</span><span class="badge bg-{{ match($dispute->status) { 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'} }}">{{ ucfirst(str_replace('_',' ',$dispute->status)) }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Raised By</span><span class="small">{{ $dispute->raisedBy?->name }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Assigned To</span><span class="small">{{ $dispute->assignedAdmin?->name ?? '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Contract</span><a href="{{ route('contracts.show', $dispute->contract) }}" class="small text-primary">View</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
