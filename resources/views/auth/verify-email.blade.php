@extends('layouts.guest')
@section('title', 'Verify Email')
@section('content')
<div class="text-center mb-3">
    <i class="fa fa-envelope-open-text fa-3x" style="color:var(--druk-orange)"></i>
</div>
<h5 class="fw-bold text-center mb-2">Verify Your Email Address</h5>
<p class="text-muted text-center small mb-4">
    Thanks for signing up! Please verify your email address by clicking the link we just emailed you.
</p>

@if(session('status') == 'verification-link-sent')
    <div class="alert alert-success small text-center">A new verification link has been sent.</div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="btn btn-primary w-100 fw-semibold">Resend Verification Email</button>
</form>

<form method="POST" action="{{ route('logout') }}" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-outline-secondary w-100 btn-sm">Log Out</button>
</form>
@endsection
