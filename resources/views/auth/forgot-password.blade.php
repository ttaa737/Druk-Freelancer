@extends('layouts.guest')
@section('title', 'Forgot Password')
@section('content')
<h4 class="fw-bold mb-1 text-center">Reset Your Password</h4>
<p class="text-muted text-center small mb-4">Enter your email and we will send you a password reset link.</p>

@if(session('status'))
    <div class="alert alert-success small mb-3">
        <i class="fa fa-check-circle me-1"></i>{{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold">
        <i class="fa fa-paper-plane me-1"></i>Send Reset Link
    </button>
</form>

<hr class="my-3">
<p class="text-center small mb-0">
    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:var(--druk-orange)">
        <i class="fa fa-arrow-left me-1"></i>Back to Login
    </a>
</p>
@endsection
