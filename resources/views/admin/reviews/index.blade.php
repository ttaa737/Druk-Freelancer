@extends('layouts.admin')
@section('title', 'Feedback Moderation')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h4 class="fw-bold mb-0">Feedback Moderation</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Total Reviews</div>
                <div class="fs-4 fw-bold">{{ number_format($summary['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-warning">
            <div class="card-body">
                <div class="text-muted small">Flagged Reports</div>
                <div class="fs-4 fw-bold text-warning">{{ number_format($summary['flagged']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-danger">
            <div class="card-body">
                <div class="text-muted small">Hidden Reviews</div>
                <div class="fs-4 fw-bold text-danger">{{ number_format($summary['hidden']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Average Rating</div>
                <div class="fs-4 fw-bold">{{ number_format($summary['avg_rating'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Comment, user name, contract #">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="flagged" @selected(request('status') === 'flagged')>Flagged</option>
                    <option value="hidden" @selected(request('status') === 'hidden')>Hidden</option>
                    <option value="visible" @selected(request('status') === 'visible')>Visible</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Reviewer Role</label>
                <select name="role" class="form-select">
                    <option value="">All</option>
                    <option value="poster" @selected(request('role') === 'poster')>Poster</option>
                    <option value="freelancer" @selected(request('role') === 'freelancer')>Freelancer</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="fa fa-filter me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Contract</th>
                    <th>Reviewer</th>
                    <th>Reviewee</th>
                    <th class="text-center">Rating</th>
                    <th>Status</th>
                    <th>Reported Feedback</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="small fw-semibold">{{ $review->contract->contract_number ?? '-' }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $review->contract->job->title ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="small fw-semibold">{{ $review->reviewer->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:11px">{{ ucfirst($review->reviewer_role) }}</div>
                    </td>
                    <td>
                        <div class="small fw-semibold">{{ $review->reviewee->name ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center fw-semibold">{{ number_format($review->rating_overall, 1) }}</td>
                    <td>
                        @if($review->is_flagged)
                            <span class="badge bg-warning text-dark">Flagged</span>
                        @elseif(!$review->is_public)
                            <span class="badge bg-danger">Hidden</span>
                        @else
                            <span class="badge bg-success">Visible</span>
                        @endif
                    </td>
                    <td>
                        @if($review->flag_reason)
                            <div class="small text-danger">{{ \Illuminate\Support\Str::limit($review->flag_reason, 70) }}</div>
                        @else
                            <span class="text-muted small">None</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No feedback entries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $reviews->links() }}</div>
</div>
@endsection
