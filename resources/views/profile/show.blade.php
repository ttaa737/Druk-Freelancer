@extends('layouts.app')
@section('title', $user->name . ' – Profile')
@section('content')
<div class="row g-4">
    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card text-center mb-4">
            <div class="card-body pt-4">
                <img src="{{ $user->avatar_url }}" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;" alt="{{ $user->name }}">
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <p class="text-muted small mb-1">{{ $user->profile?->headline ?? '' }}</p>
                <p class="text-muted small mb-2"><i class="fa fa-map-marker-alt me-1"></i>{{ $user->profile?->dzongkhag ?? 'Bhutan' }}</p>
                @php $avgRating = $user->profile?->average_rating ?? 0; @endphp
                <div class="d-flex justify-content-center gap-1 mb-2">
                    @for($i=1;$i<=5;$i++)
                    <i class="fa fa-star{{ $i <= round($avgRating) ? '' : '-o' }} text-warning small"></i>
                    @endfor
                    <span class="text-muted small ms-1">{{ number_format($avgRating, 1) }} / 5.0</span>
                </div>
                <span class="badge {{ $user->verification_status === 'verified' ? 'bg-success' : 'bg-secondary' }} mb-3">
                    <i class="fa fa-{{ $user->verification_status === 'verified' ? 'check' : 'clock' }} me-1"></i>{{ ucfirst($user->verification_status) }}
                </span>
                @auth
                    @if(auth()->id() !== $user->id)
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('messages.start') }}">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="fa fa-envelope me-1"></i>Message</button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fa fa-edit me-1"></i>Edit Profile</a>
                    @endif
                @endauth
            </div>
        </div>

        @if($user->profile?->hourly_rate)
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Rate</h6>
                <p class="mb-0 fw-bold text-primary">Nu. {{ number_format($user->profile->hourly_rate) }} <small class="text-muted fw-normal">/ hr</small></p>
            </div>
        </div>
        @endif

        @if($user->skills->count())
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Skills</h6>
                <div class="d-flex flex-wrap gap-1">
                    @foreach($user->skills as $skill)
                    <span class="badge bg-light text-dark border small">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">Stats</h6>
                <div class="row text-center">
                    @php
                        $totalContracts     = $user->contractsAsFreelancer->count() + $user->contractsAsPoster->count();
                        $completedContracts = $user->contractsAsFreelancer->where('status','completed')->count() + $user->contractsAsPoster->where('status','completed')->count();
                    @endphp
                    <div class="col-4"><div class="fw-bold text-primary">{{ $totalContracts }}</div><div class="text-muted" style="font-size:11px">Jobs</div></div>
                    <div class="col-4"><div class="fw-bold text-success">{{ $completedContracts }}</div><div class="text-muted" style="font-size:11px">Done</div></div>
                    <div class="col-4"><div class="fw-bold text-warning">{{ number_format($avgRating, 1) }}</div><div class="text-muted" style="font-size:11px">Rating</div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-8">
        @if($user->profile?->bio)
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">About</h6>
                <p class="text-muted mb-0" style="white-space:pre-line">{{ $user->profile->bio }}</p>
            </div>
        </div>
        @endif

        @if($portfolioItems->count())
        <div class="card mb-4">
            <div class="card-header fw-bold">Portfolio</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($portfolioItems as $item)
                    <div class="col-sm-6 col-md-4">
                        <div class="border rounded overflow-hidden">
                            @if($item->image_path)
                            <img src="{{ Storage::url($item->image_path) }}" class="w-100" style="height:140px;object-fit:cover;" alt="{{ $item->title }}">
                            @endif
                            <div class="p-2">
                                <div class="fw-semibold small">{{ $item->title }}</div>
                                <p class="text-muted" style="font-size:11px;margin-bottom:0">{{ Str::limit($item->description, 80) }}</p>
                                @if($item->url)<a href="{{ $item->url }}" target="_blank" class="btn btn-link btn-sm p-0" style="font-size:11px">View <i class="fa fa-external-link-alt ms-1"></i></a>@endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($certifications->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header fw-bold">Certifications</div>
            <ul class="list-group list-group-flush">
                @foreach($certifications as $cert)
                <li class="list-group-item d-flex align-items-center gap-3">
                    <i class="fa fa-certificate text-warning"></i>
                    <div>
                        <div class="fw-semibold small">{{ $cert->title }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $cert->issuer }}{{ $cert->year ? ' – ' . $cert->year : '' }}</div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Reviews -->
        <div class="card mb-4">
            <div class="card-header fw-bold">Reviews <span class="badge bg-secondary ms-1">{{ $reviews->total() }}</span></div>
            @forelse($reviews as $review)
            <div class="card-body border-bottom">
                <div class="d-flex gap-3">
                    <img src="{{ $review->reviewer?->avatar_url ?? asset('images/default-avatar.png') }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold small">{{ $review->is_anonymous ? 'Anonymous' : ($review->reviewer?->name ?? 'User') }}</span>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex gap-1 mb-1">
                            @for($i=1;$i<=5;$i++)<i class="fa fa-star{{ $i <= $review->overall_rating ? '' : '-o' }} text-warning" style="font-size:11px"></i>@endfor
                        </div>
                        @if($review->comment)<p class="text-muted small mb-0">{{ $review->comment }}</p>@endif
                    </div>
                </div>
            </div>
            @empty
            <div class="card-body text-center text-muted small">No reviews yet.</div>
            @endforelse
            @if($reviews->hasPages())
            <div class="card-body pt-0">{{ $reviews->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
