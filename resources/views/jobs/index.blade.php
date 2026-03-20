@extends('layouts.app')
@section('title', 'Browse Jobs')
@section('content')
<div class="row g-3">
    <!-- Filters Sidebar -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fa fa-filter me-2"></i>Filter Jobs</h6>
                <form method="GET" action="{{ route('jobs.index') }}">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Keywords..." value="{{ request('search') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Job Type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="fixed" {{ request('type')=='fixed'?'selected':'' }}>Fixed Price</option>
                            <option value="hourly" {{ request('type')=='hourly'?'selected':'' }}>Hourly</option>
                            <option value="milestone" {{ request('type')=='milestone'?'selected':'' }}>Milestone</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Location (Dzongkhag)</label>
                        <select name="dzongkhag" class="form-select form-select-sm">
                            <option value="">All Dzongkhags</option>
                            @foreach(\App\Models\Profile::DZONGKHAGS as $dz)
                            <option value="{{ $dz }}" {{ request('dzongkhag')==$dz?'selected':'' }}>{{ $dz }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Budget Range (Nu.)</label>
                        <div class="row g-1">
                            <div class="col-6"><input type="number" name="budget_min" class="form-control form-control-sm" placeholder="Min" value="{{ request('budget_min') }}"></div>
                            <div class="col-6"><input type="number" name="budget_max" class="form-control form-control-sm" placeholder="Max" value="{{ request('budget_max') }}"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1">Clear</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Job Listings -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">{{ $jobs->total() }} Jobs Found</h5>
            @auth
            @if(auth()->user()->hasRole('job_poster'))
            <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i>Post a Job</a>
            @endif
            @endauth
        </div>

        @forelse($jobs as $job)
        <div class="card mb-3 {{ $job->is_featured ? 'border-warning' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        @if($job->is_featured) <span class="badge bg-warning text-dark mb-1"><i class="fa fa-star me-1"></i>Featured</span> @endif
                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="text-dark text-decoration-none">{{ $job->title }}</a>
                        </h6>
                        <div class="text-muted small mb-2">
                            <span class="me-3"><i class="fa fa-user me-1"></i>{{ $job->poster->name }}</span>
                            @if($job->profile?->dzongkhag ?? $job->location)
                            <span class="me-3"><i class="fa fa-map-marker-alt me-1"></i>{{ $job->location ?? 'Bhutan' }}</span>
                            @endif
                            <span class="me-3"><i class="fa fa-clock me-1"></i>{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-muted small mb-2">{{ Str::limit($job->description, 150) }}</p>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($job->skills->take(5) as $skill)
                            <span class="badge bg-light text-dark border">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-end ms-3" style="min-width:120px">
                        <div class="fw-bold text-primary">{{ $job->budgetRange }}</div>
                        <div class="badge bg-secondary mb-1">{{ ucfirst($job->type) }}</div>
                        <div class="text-muted small">{{ $job->proposals_count }} proposals</div>
                        <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-outline-primary btn-sm mt-2 d-block">View Job</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-search fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">No jobs found matching your criteria.</h6>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary mt-2">Clear Filters</a>
            </div>
        </div>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection
