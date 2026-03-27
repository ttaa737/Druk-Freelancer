<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin – @yield('title', 'Druk Freelancer')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --druk-orange: #FF6B35; --druk-blue: #1A3A5C; }
        body { font-family: 'Segoe UI', sans-serif; background: #f1f3f6; }
        .sidebar { width: 240px; min-height: 100vh; background: var(--druk-blue); position: fixed; top: 0; left: 0; z-index: 100; overflow-y: auto; transition: transform 0.3s ease; }
        .sidebar .brand { color: #fff; padding: 1.2rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); font-size: 1.1rem; font-weight: 600; }
        .sidebar .brand span { color: var(--druk-orange); }
        .sidebar .nav-link { color: rgba(255,255,255,.75); padding: .6rem 1rem; border-radius: .25rem; margin: .1rem .5rem; font-size: .9rem; transition: all 0.2s ease; text-decoration: none; display: block; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.1); color: #fff; transform: translateX(3px); }
        .sidebar .nav-link i { width: 22px; }
        .sidebar .section-label { color: rgba(255,255,255,.4); font-size: .7rem; text-transform: uppercase; letter-spacing: 1px; padding: .8rem 1rem .3rem; }
        .main-content { margin-left: 240px; padding: 1.5rem; min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #dee2e6; padding: .75rem 1.5rem; margin: -1.5rem -1.5rem 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,.05); }
        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,.08); border-radius: .75rem; transition: box-shadow 0.3s ease; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.12); }
        .stat-card { border-left: 4px solid var(--druk-orange); }
        .btn { transition: all 0.2s ease; }
        .btn:hover { transform: translateY(-1px); }
        /* Mobile responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); box-shadow: 2px 0 16px rgba(0,0,0,.3); }
            .main-content { margin-left: 0; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,.5); z-index: 99; }
            .sidebar-overlay.show { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body>
<!-- Mobile sidebar overlay -->
<div class="sidebar-overlay" id="adminSidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="adminSidebar">
    <button class="btn btn-sm btn-light d-lg-none position-absolute end-0 top-0 m-2" id="closeSidebar">
        <i class="fa fa-times"></i>
    </button>
    <div class="brand fw-bold">Druk Freelancer <span>Admin</span></div>
    <nav class="mt-2">
        <div class="section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt"></i> Dashboard
        </a>
        <div class="section-label">Management</div>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa fa-users"></i> Users
        </a>
        <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
            <i class="fa fa-briefcase"></i> Jobs
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fa fa-tags"></i> Categories
        </a>
        <div class="section-label">Finance</div>
        <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
            <i class="fa fa-money-bill-wave"></i> Transactions
        </a>
        <div class="section-label">Support</div>
        <a href="{{ route('admin.disputes.index') }}" class="nav-link {{ request()->routeIs('admin.disputes.*') ? 'active' : '' }}">
            <i class="fa fa-gavel"></i> Disputes
        </a>
        <a href="{{ route('admin.verifications.index') }}" class="nav-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
            <i class="fa fa-id-card"></i> Verifications
        </a>
        <div class="section-label">Account</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                <i class="fa fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="toggleAdminSidebar">
                <i class="fa fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-inline">{{ auth()->user()->name }}</span>
            <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;" alt="{{ auth()->user()->name }}">
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fa fa-times me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile sidebar toggle for admin
    const toggleBtn = document.getElementById('toggleAdminSidebar');
    const closeBtn = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminSidebarOverlay');
    
    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        });
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
        
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Close on nav link click on mobile
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });
        });
    }
    
    // Auto-dismiss alerts
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            }, 5000);
        });
    });
</script>
@stack('scripts')
</body>
</html>
