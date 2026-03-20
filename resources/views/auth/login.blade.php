@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<h4 class="fw-bold mb-1 text-center">Welcome Back</h4>
<p class="text-muted text-center small mb-4">Sign in to your Druk Freelancer account</p>

@if(session('status'))
    <div class="alert alert-success small">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label small fw-semibold">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        @if(Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color:var(--druk-orange)">Forgot password?</a>
        @endif
    </div>
    <button type="submit" class="btn btn-primary w-100 fw-semibold">Sign In</button>
</form>

<hr class="my-3">
<p class="text-center small mb-0">
    Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:var(--druk-orange)">Create one</a>
</p>
@endsection
