@extends('layouts.app')
@section('title', $user->name . ' - Reviews')
@section('content')
<div class="row g-4">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex align-items-center gap-3 mb-4">
            <img src="{{ $user->avatarUrl }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover" alt="">
            <div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <div class="text-muted small">
                    @if($user->profile && $user->profile->rating)
                        <i class="fa fa-star text-warning"></i>
                        {{ number_format($user->profile->rating, 1) }}
                        ({{ $user->profile->total_reviews ?? $reviews->total() }} reviews)
                    @else
                        No reviews yet
                    @endif
                </div>
            </div>
            <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="fa fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>

        @forelse($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <img src="{{ $review->reviewer->avatarUrl }}" class="rounded-circle flex-shrink-0" style="width:38px;height:38px;object-fit:cover" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold small">
                                @if($review->is_anonymous)
                                    Anonymous
                                @else
                                    {{ $review->reviewer->name }}
                                @endif
                            </span>
                            <span class="text-muted" style="font-size:11px">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $review->overall_rating ? 'text-warning' : 'text-muted' }}" style="font-size:13px"></i>
                            @endfor
                            <span class="ms-1 small text-muted">{{ number_format($review->overall_rating, 1) }}/5</span>
                        </div>

                        @if($review->comment)
                        <p class="mb-2 small">{{ $review->comment }}</p>
                        @endif

                        @if($review->communication_rating || $review->quality_rating || $review->timeliness_rating || $review->professionalism_rating)
                        <div class="row g-2 mt-1">
                            @foreach([
                                'communication_rating' => 'Communication',
                                'quality_rating' => 'Quality',
                                'timeliness_rating' => 'Timeliness',
                                'professionalism_rating' => 'Professionalism',
                            ] as $field => $label)
                                @if($review->$field)
                                <div class="col-6 col-sm-3">
                                    <div class="text-muted" style="font-size:10px">{{ $label }}</div>
                                    <div class="d-flex align-items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star {{ $i <= $review->$field ? 'text-warning' : 'text-muted' }}" style="font-size:10px"></i>
                                        @endfor
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-star-half-alt fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No reviews yet</h6>
                <p class="text-muted small">{{ $user->name }} hasn't received any reviews yet.</p>
            </div>
        </div>
        @endforelse

        {{ $reviews->links() }}
    </div>
</div>
@endsection
