@extends('layouts.app')
@section('title', 'Leave a Review')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1"><i class="fa fa-star me-2 text-warning"></i>Leave a Review</h5>
                <p class="text-muted small mb-4">Your feedback helps build a trusted Druk Freelancer community.</p>

                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-4">
                    <img src="{{ $reviewee->avatar_url }}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                    <div>
                        <div class="fw-semibold">{{ $reviewee->name }}</div>
                        <small class="text-muted">Contract: {{ $contract->contract_number }} - {{ $contract->job?->title ?? 'Project' }}</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('reviews.store', $contract) }}">
                    @csrf
                    @php
                    $dimensions = [
                        'rating_overall' => ['label' => 'Overall Experience', 'icon' => 'star', 'required' => true],
                    ];

                    if ($reviewerRole === 'poster') {
                        $dimensions['rating_communication'] = ['label' => 'Communication', 'icon' => 'comments', 'required' => true];
                        $dimensions['rating_quality'] = ['label' => 'Work Quality', 'icon' => 'trophy', 'required' => true];
                        $dimensions['rating_professionalism'] = ['label' => 'Professionalism', 'icon' => 'briefcase', 'required' => true];
                        $dimensions['rating_timeliness'] = ['label' => 'Delivery Time', 'icon' => 'clock', 'required' => true];
                    } else {
                        $dimensions['rating_payment_behavior'] = ['label' => 'Payment Behavior', 'icon' => 'money-bill-wave', 'required' => true];
                        $dimensions['rating_project_clarity'] = ['label' => 'Project Clarity', 'icon' => 'clipboard-list', 'required' => true];
                        $dimensions['rating_communication'] = ['label' => 'Communication', 'icon' => 'comments', 'required' => true];
                    }
                    @endphp

                    @foreach($dimensions as $field => $dim)
                    <div class="mb-4">
                        <label class="form-label fw-semibold small"><i class="fa fa-{{ $dim['icon'] }} me-1 text-warning"></i>{{ $dim['label'] }} @if($dim['required'])<span class="text-danger">*</span>@endif</label>
                        <div class="star-rating d-flex gap-2" data-field="{{ $field }}">
                            @for($i=1;$i<=5;$i++)
                            <label class="star-label" style="cursor:pointer;font-size:1.5rem;color:#dee2e6">
                                <input type="radio" name="{{ $field }}" value="{{ $i }}" class="d-none" @checked(old($field)==$i)>
                                <i class="fa fa-star"></i>
                            </label>
                            @endfor
                        </div>
                        @error($field)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    @endforeach

                    <div class="alert alert-light border small mb-3">
                        @if($reviewerRole === 'poster')
                            You are reviewing freelancer performance for hiring quality and accountability.
                        @else
                            You are reviewing client behavior to support fair and transparent collaboration.
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Comment <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience...">{{ old('comment') }}</textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonCheck" value="1" @checked(old('is_anonymous'))>
                        <label class="form-check-label small" for="anonCheck">Post anonymously</label>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i>Submit Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.star-rating').forEach(group => {
    const labels = group.querySelectorAll('.star-label');
    labels.forEach((label, idx) => {
        label.addEventListener('mouseenter', () => {
            labels.forEach((l, i) => l.querySelector('i').style.color = i <= idx ? '#F4A823' : '#dee2e6');
        });
        label.addEventListener('click', () => {
            label.querySelector('input').checked = true;
            labels.forEach((l, i) => l.querySelector('i').style.color = i <= idx ? '#F4A823' : '#dee2e6');
        });
    });
    group.addEventListener('mouseleave', () => {
        const checked = group.querySelector('input:checked');
        const val = checked ? parseInt(checked.value) - 1 : -1;
        labels.forEach((l, i) => l.querySelector('i').style.color = i <= val ? '#F4A823' : '#dee2e6');
    });
});
</script>
@endsection
