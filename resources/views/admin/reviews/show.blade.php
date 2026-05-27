@extends('layouts.admin')
@section('title', 'Review Moderation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">Review Moderation</h4>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header fw-bold">Feedback Details</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="small text-muted">Contract</div>
                        <div class="fw-semibold">{{ $review->contract->contract_number ?? '-' }} - {{ $review->contract->job->title ?? 'N/A' }}</div>
                    </div>
                    <span class="badge bg-dark">{{ ucfirst($review->reviewer_role) }} Review</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="small text-muted">Reviewer</div>
                            <div class="fw-semibold">{{ $review->reviewer->name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $review->reviewer->email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="small text-muted">Reviewee</div>
                            <div class="fw-semibold">{{ $review->reviewee->name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $review->reviewee->email ?? '' }}</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4"><div class="small text-muted">Overall</div><div class="fw-semibold">{{ number_format($review->rating_overall, 1) }}/5</div></div>
                    @if($review->rating_communication)<div class="col-md-4"><div class="small text-muted">Communication</div><div class="fw-semibold">{{ number_format($review->rating_communication, 1) }}/5</div></div>@endif
                    @if($review->rating_quality)<div class="col-md-4"><div class="small text-muted">Quality</div><div class="fw-semibold">{{ number_format($review->rating_quality, 1) }}/5</div></div>@endif
                    @if($review->rating_professionalism)<div class="col-md-4"><div class="small text-muted">Professionalism</div><div class="fw-semibold">{{ number_format($review->rating_professionalism, 1) }}/5</div></div>@endif
                    @if($review->rating_timeliness)<div class="col-md-4"><div class="small text-muted">Timeliness</div><div class="fw-semibold">{{ number_format($review->rating_timeliness, 1) }}/5</div></div>@endif
                    @if($review->rating_payment_behavior)<div class="col-md-4"><div class="small text-muted">Payment Behavior</div><div class="fw-semibold">{{ number_format($review->rating_payment_behavior, 1) }}/5</div></div>@endif
                    @if($review->rating_project_clarity)<div class="col-md-4"><div class="small text-muted">Project Clarity</div><div class="fw-semibold">{{ number_format($review->rating_project_clarity, 1) }}/5</div></div>@endif
                </div>

                <div class="border rounded p-3 bg-light">
                    <div class="small text-muted mb-1">Comment</div>
                    <div>{{ $review->comment ?: 'No comment provided.' }}</div>
                </div>
            </div>
        </div>

        @if($review->is_flagged)
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning bg-opacity-10 fw-bold">Reported Feedback</div>
            <div class="card-body">
                <div class="small text-muted">Reported Reason</div>
                <div class="mb-3">{{ $review->flag_reason }}</div>
                <div class="small text-muted">Reported By</div>
                <div class="mb-3">{{ $review->reporter->name ?? 'Unknown' }}</div>

                <form method="POST" action="{{ route('admin.reviews.resolve', $review) }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Resolution Note</label>
                        <textarea name="note" class="form-control" rows="2" required placeholder="How was this report resolved?"></textarea>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="keepPublic" name="keep_public" value="1" checked>
                            <label for="keepPublic" class="form-check-label small">Keep public</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning w-100" type="submit">Resolve</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Visibility</div>
            <div class="card-body">
                <div class="small text-muted mb-2">Current Status</div>
                @if(!$review->is_public)
                    <div class="badge bg-danger mb-3">Hidden from public profiles</div>
                @else
                    <div class="badge bg-success mb-3">Visible on public profiles</div>
                @endif

                @if($review->is_public)
                <form method="POST" action="{{ route('admin.reviews.hide', $review) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small text-muted">Moderation Note</label>
                        <textarea name="note" class="form-control" rows="3" required placeholder="Why is this feedback being hidden?"></textarea>
                    </div>
                    <button class="btn btn-danger w-100" type="submit">Hide Review</button>
                </form>
                @else
                <form method="POST" action="{{ route('admin.reviews.unhide', $review) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small text-muted">Restoration Note</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Optional note for restoring visibility"></textarea>
                    </div>
                    <button class="btn btn-success w-100" type="submit">Restore Review</button>
                </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Moderation History</div>
            <div class="card-body small">
                <div class="mb-2"><span class="text-muted">Moderated by:</span> {{ $review->moderator->name ?? 'N/A' }}</div>
                <div class="mb-2"><span class="text-muted">Moderated at:</span> {{ $review->moderated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                <div><span class="text-muted">Notes:</span> {{ $review->moderation_notes ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
