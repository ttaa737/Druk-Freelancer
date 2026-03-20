<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Druk Freelancer')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --druk-orange: #FF6B35; --druk-blue: #1A3A5C; }
        body { background: linear-gradient(135deg, #1A3A5C 0%, #0d2340 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .auth-card { border: none; border-radius: 1rem; box-shadow: 0 8px 32px rgba(0,0,0,.25); }
        .auth-logo span { color: var(--druk-orange); }
        .btn-primary { background: var(--druk-orange); border-color: var(--druk-orange); }
        .btn-primary:hover { background: #e55a27; border-color: #e55a27; }
        .form-control:focus { border-color: var(--druk-orange); box-shadow: 0 0 0 .2rem rgba(255,107,53,.25); }
    </style>
    @stack('styles')
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="auth-logo text-white text-decoration-none display-6 fw-bold">
                    Druk <span>Freelancer</span>
                </a>
                <p class="text-white-50 small mt-1">Bhutan's Digital Marketplace for Talent</p>
            </div>
            <div class="auth-card card p-4">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
