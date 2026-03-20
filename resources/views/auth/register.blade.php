@extends('layouts.guest')
@section('title', 'Create Account')
@section('content')
<h4 class="fw-bold mb-1 text-center">Join Druk Freelancer</h4>
<p class="text-muted text-center small mb-4">Bhutan's premier freelancing platform</p>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Full Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Phone Number <span class="text-muted">(optional)</span></label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+975-17XXXXXX">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">I want to</label>
        <div class="row g-2">
            <div class="col-6">
                <input type="radio" name="role" id="role_freelancer" value="freelancer" class="btn-check" {{ old('role','freelancer') === 'freelancer' ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary w-100" for="role_freelancer">
                    <i class="fa fa-laptop me-1"></i> Work as Freelancer
                </label>
            </div>
            <div class="col-6">
                <input type="radio" name="role" id="role_poster" value="job_poster" class="btn-check" {{ old('role') === 'job_poster' ? 'checked' : '' }}>
                <label class="btn btn-outline-secondary w-100" for="role_poster">
                    <i class="fa fa-building me-1"></i> Hire Freelancers
                </label>
            </div>
        </div>
        @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <div class="mb-3">
        <div class="form-check">
            <input type="checkbox" name="terms" class="form-check-input @error('terms') is-invalid @enderror" id="terms" value="1">
            <label class="form-check-label small" for="terms">
                I agree to the <a href="#" style="color:var(--druk-orange)">Terms of Service</a> and <a href="#" style="color:var(--druk-orange)">Privacy Policy</a>
            </label>
            @error('terms') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 fw-semibold">Create Account</button>
</form>

<hr class="my-3">
<p class="text-center small mb-0">
    Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:var(--druk-orange)">Sign in</a>
</p>
@endsection
