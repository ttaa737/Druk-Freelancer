@extends('layouts.admin')
@section('title', 'Dispute #' . $dispute->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Dispute: {{ $dispute->subject }}</h4>
    <span class="badge fs-6 bg-{{ match($dispute->status){ 'open'=>'warning','under_review'=>'info','resolved'=>'success','closed'=>'secondary', default=>'secondary'} }}">{{ ucfirst(str_replace('_',' ',$dispute->status)) }}</span>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Description -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Description</div>
            <div class="card-body"><p class="mb-0" style="white-space:pre-line">{{ $dispute->description }}</p></div>
        </div>

        <!-- Evidence -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Evidence Files</div>
            @forelse($dispute->evidence as $file)
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 border-bottom gap-3">
                <div><i class="fa fa-file text-secondary me-2"></i><span class="small">{{ $file->original_name ?? basename($file->file_path) }}</span></div>
                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
            </div>
            @empty
            <div class="card-body text-muted small text-center">No evidence files.</div>
            @endforelse
        </div>

        <!-- Comments -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Comments</div>
            @foreach($dispute->comments as $comment)
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="{{ $comment->user?->avatar_url ? Storage::url($comment->user->avatar_url) : asset('img/default-avatar.png') }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
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
            @endforeach
            <div class="card-body pt-2">
                <form method="POST" action="{{ route('disputes.comment', $dispute) }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add admin comment...">
                        <button class="btn btn-sm btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resolve Form -->
        @if(in_array($dispute->status, ['open','under_review']))
        <div class="card border-danger">
            <div class="card-header fw-bold text-danger">Resolve Dispute</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Resolution Type</label>
                        <select name="resolution" class="form-select" id="resolutionSelect" required>
                            <option value="favour_poster">In Favour of Job Poster (Refund escrow to poster)</option>
                            <option value="favour_freelancer">In Favour of Freelancer (Release escrow to freelancer)</option>
                            <option value="split">Split (Custom percentage)</option>
                        </select>
                    </div>
                    <div id="splitField" class="mb-3 d-none">
                        <label class="form-label small fw-semibold">Freelancer gets (%)</label>
                        <input type="number" name="freelancer_percent" class="form-control" min="0" max="100" value="50" placeholder="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Resolution Note</label>
                        <textarea name="resolution_note" class="form-control" rows="3" required placeholder="Explain the decision..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Confirm Resolution</button>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Dispute Info</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Raised By</span><span class="small">{{ $dispute->raisedBy?->name }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Assigned To</span><span class="small">{{ $dispute->assignedAdmin?->name ?? '—' }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted small">Contract</span><a href="{{ route('contracts.show', $dispute->contract) }}" class="small text-primary">View</a></li>
                @if($dispute->resolution_notes)<li class="list-group-item"><div class="text-muted small">Resolution</div><div class="small">{{ $dispute->resolution_notes }}</div></li>@endif
            </ul>
        </div>
        @if(!$dispute->assigned_admin_id || $dispute->status === 'open')
        <form method="POST" action="{{ route('admin.disputes.assign', $dispute) }}">@csrf <button class="btn btn-outline-secondary w-100 mb-2">Assign to Me</button></form>
        @endif
    </div>
</div>
<script>
document.getElementById('resolutionSelect')?.addEventListener('change', function(){
    document.getElementById('splitField').classList.toggle('d-none', this.value !== 'split');
});
</script>
@endsection
