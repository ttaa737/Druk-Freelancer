<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Druk Freelancer') &ndash; Bhutan&apos;s Digital Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --druk-orange:#FF6B35; --druk-blue:#1A3A5C; --druk-gold:#F4A823; --sidebar-w:230px; }
        body { background:#f1f3f6; font-family:'Segoe UI',sans-serif; }
        /*  Topbar  */
        .topbar { background:var(--druk-blue); height:54px; position:sticky; top:0; z-index:200; box-shadow:0 2px 8px rgba(0,0,0,.1); }
        .topbar .navbar-brand span { color:var(--druk-orange); }
        .topbar .nav-link:hover { color:#fff !important; }
        /*  Sidebar  */
        .app-sidebar { width:var(--sidebar-w); min-height:calc(100vh - 54px); background:#fff; border-right:1px solid #e5e7eb; position:sticky; top:54px; height:calc(100vh - 54px); overflow-y:auto; flex-shrink:0; transition:transform 0.3s ease, box-shadow 0.3s ease; }
        .app-sidebar .sidebar-section { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; padding:.9rem 1.1rem .3rem; }
        .app-sidebar .nav-link { color:#374151; font-size:.85rem; padding:.45rem 1.1rem; border-radius:.4rem; margin:.05rem .4rem; display:flex; align-items:center; gap:.6rem; transition:all 0.2s ease; text-decoration:none; }
        .app-sidebar .nav-link i { width:16px; text-align:center; color:#6b7280; transition:color 0.2s ease; }
        .app-sidebar .nav-link:hover { background:#f3f4f6; color:var(--druk-blue); transform:translateX(2px); }
        .app-sidebar .nav-link.active { background:#eff6ff; color:var(--druk-blue); font-weight:600; }
        .app-sidebar .nav-link.active i { color:var(--druk-orange); }
        /*  Content  */
        .app-content { flex:1; min-width:0; padding:1.5rem; }
        .app-wrapper { display:flex; }
        /*  Cards  */
        .card { border:none; box-shadow:0 1px 4px rgba(0,0,0,.07); border-radius:.75rem; transition:box-shadow 0.3s ease; }
        .card:hover { box-shadow:0 4px 12px rgba(0,0,0,.12); }
        .stat-card { border-left:4px solid var(--druk-orange); }
        /*  Buttons  */
        .btn { transition:all 0.2s ease; }
        .btn:hover { transform:translateY(-1px); }
        .btn-primary { background:var(--druk-orange); border-color:var(--druk-orange); }
        .btn-primary:hover, .btn-primary:focus { background:#e55a27; border-color:#e55a27; box-shadow:0 4px 8px rgba(255,107,53,.3); }
        /*  Dropdowns  */
        .dropdown-menu { border:none; box-shadow:0 4px 16px rgba(0,0,0,.15); border-radius:.5rem; padding:.5rem; animation:fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-5px); } to { opacity:1; transform:translateY(0); } }
        .dropdown-item { border-radius:.375rem; padding:.5rem .75rem; transition:all 0.2s ease; }
        .dropdown-item:hover { background:#f3f4f6; transform:translateX(3px); }
        .dropdown-toggle::after { display:none; }
        /*  Footer  */
        .app-footer { background:var(--druk-blue); color:#9ca3af; padding:1.5rem 0; margin-top:2rem; font-size:.8rem; }
        .text-primary { color:var(--druk-blue) !important; }
        .badge-orange { background:var(--druk-orange); color:#fff; }
        /* Mobile sidebar */
        @media(max-width:991px){ 
            .app-sidebar { 
                display:none; 
                position:fixed; 
                top:54px; 
                left:0; 
                z-index:150; 
                height:calc(100vh - 54px); 
                transform:translateX(-100%); 
            } 
            .app-sidebar.show { 
                display:block; 
                transform:translateX(0); 
                box-shadow:2px 0 16px rgba(0,0,0,.2); 
            }
            /* Overlay for mobile sidebar */
            .sidebar-overlay { 
                display:none; 
                position:fixed; 
                top:54px; 
                left:0; 
                right:0; 
                bottom:0; 
                background:rgba(0,0,0,.5); 
                z-index:149; 
            }
            .sidebar-overlay.show { 
                display:block; 
            }
        }
        /* Smooth scrollbar */
        .app-sidebar::-webkit-scrollbar { width:6px; }
        .app-sidebar::-webkit-scrollbar-track { background:#f1f1f1; }
        .app-sidebar::-webkit-scrollbar-thumb { background:#ccc; border-radius:3px; }
        .app-sidebar::-webkit-scrollbar-thumb:hover { background:#999; }
    </style>
    @stack('styles')
</head>
<body>

{{--  Topbar  --}}
<nav class="topbar navbar navbar-expand-lg navbar-dark px-3 px-md-4">
    @auth
    <button class="btn btn-sm btn-outline-light d-lg-none me-2 border-0" id="sidebarToggle">
        <i class="fa fa-bars"></i>
    </button>
    @endauth
    <a class="navbar-brand fw-bold me-3" href="{{ url('/') }}">Druk <span>Freelancer</span></a>

    <div class="d-flex ms-auto align-items-center gap-2 gap-md-3">
        <a class="nav-link text-white-50 d-none d-md-block" href="{{ route('jobs.index') }}">
            <i class="fa fa-briefcase me-1"></i>Jobs
        </a>
        @guest
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
            <a href="{{ route('register') }}" class="btn btn-sm text-white" style="background:var(--druk-orange)">Sign Up</a>
        @else
            {{-- Notifications --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light border-0 position-relative" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                    <i class="fa fa-bell"></i>
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    @if($unread>0)<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px">{{ $unread }}</span>@endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:300px;max-height:360px;overflow-y:auto">
                    <li class="px-3 py-2 border-bottom"><strong class="small">Notifications</strong></li>
                    @forelse(auth()->user()->unreadNotifications()->take(5)->get() as $notif)
                    <li>
                        <a class="dropdown-item small py-2" href="{{ $notif->data['url'] ?? '#' }}">
                            <i class="fa fa-{{ $notif->data['icon'] ?? 'bell' }} me-2 text-muted"></i>{{ Str::limit($notif->data['message'] ?? 'Notification', 55) }}
                        </a>
                    </li>
                    @empty
                    <li><span class="dropdown-item text-muted small py-3 text-center">No new notifications</span></li>
                    @endforelse
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item text-center small text-primary" href="{{ route('notifications.index') }}">View All</a></li>
                </ul>
            </div>
            {{-- Wallet --}}
            <a class="btn btn-sm btn-outline-light border-0 d-none d-md-inline-flex align-items-center gap-1" href="{{ route('wallet.index') }}">
                <i class="fa fa-wallet"></i>
                <span class="small">Nu.{{ number_format(auth()->user()->wallet?->available_balance ?? 0) }}</span>
            </a>
            {{-- User menu --}}
            <div class="dropdown">
                <button class="btn btn-sm d-flex align-items-center gap-2 text-white border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="30" height="30" style="object-fit:cover" alt="{{ auth()->user()->name }}">
                    <span class="d-none d-md-block small">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                    <i class="fa fa-chevron-down" style="font-size:9px"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:11px">{{ auth()->user()->email }}</div>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('profile.show', auth()->user()) }}"><i class="fa fa-user me-2 text-muted"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fa fa-cog me-2 text-muted"></i>Settings</a></li>
                    <li><a class="dropdown-item" href="{{ route('wallet.index') }}"><i class="fa fa-wallet me-2 text-muted"></i>Wallet</a></li>
                    @if(auth()->user()->isAdmin())
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="{{ route('admin.dashboard') }}"><i class="fa fa-shield-alt me-2"></i>Admin Panel</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fa fa-sign-out-alt me-2 text-muted"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endguest
    </div>
</nav>

{{--  Body wrapper  --}}
@auth
<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app-wrapper">

    {{-- Sidebar --}}
    <aside class="app-sidebar" id="appSidebar">
        <div class="py-2">
            {{-- User identity strip --}}
            <div class="px-3 py-2 mb-1 d-flex align-items-center gap-2">
                <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle flex-shrink-0" width="36" height="36" style="object-fit:cover" alt="">
                <div class="overflow-hidden">
                    <div class="fw-bold small text-truncate">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size:11px">{{ ucfirst(str_replace('_',' ', auth()->user()->role ?? '')) }}</div>
                </div>
            </div>

            <hr class="my-1 mx-3">

            {{-- Dashboard --}}
            <div class="sidebar-section">Overview</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa fa-th-large"></i> Dashboard
            </a>

            {{-- Freelancer Links --}}
            @if(auth()->user()->isFreelancer())
            <div class="sidebar-section">Work</div>
            <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}">
                <i class="fa fa-search"></i> Find Jobs
            </a>
            <a href="{{ route('proposals.my') }}" class="nav-link {{ request()->routeIs('proposals.my') ? 'active' : '' }}">
                <i class="fa fa-paper-plane"></i> My Proposals
            </a>
            <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                <i class="fa fa-file-contract"></i> My Contracts
            </a>
            <div class="sidebar-section">Account</div>
            <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <i class="fa fa-comments"></i> Messages
            </a>
            <a href="{{ route('wallet.index') }}" class="nav-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                <i class="fa fa-wallet"></i> Wallet
            </a>
            <a href="{{ route('profile.show', auth()->user()) }}" class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                <i class="fa fa-user"></i> My Profile
            </a>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa fa-cog"></i> Settings
            </a>
            @endif

            {{-- Job Poster Links --}}
            @if(auth()->user()->isJobPoster())
            <div class="sidebar-section">Hiring</div>
            <a href="{{ route('jobs.create') }}" class="nav-link {{ request()->routeIs('jobs.create') ? 'active' : '' }}">
                <i class="fa fa-plus-circle"></i> Post a Job
            </a>
            <a href="{{ route('jobs.my') }}" class="nav-link {{ request()->routeIs('jobs.my') ? 'active' : '' }}">
                <i class="fa fa-briefcase"></i> My Jobs
            </a>
            <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                <i class="fa fa-file-contract"></i> Contracts
            </a>
            <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.index') ? 'active' : '' }}">
                <i class="fa fa-search"></i> Browse Talent
            </a>
            <div class="sidebar-section">Account</div>
            <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <i class="fa fa-comments"></i> Messages
            </a>
            <a href="{{ route('wallet.index') }}" class="nav-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                <i class="fa fa-wallet"></i> Wallet
            </a>
            <a href="{{ route('profile.show', auth()->user()) }}" class="nav-link">
                <i class="fa fa-user"></i> My Profile
            </a>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fa fa-cog"></i> Settings
            </a>
            @endif

            {{-- Admin quick link --}}
            @if(auth()->user()->isAdmin())
            <div class="sidebar-section">Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fa fa-shield-alt"></i> Admin Panel
            </a>
            @endif

            <hr class="mx-3 my-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Page content --}}
    <div class="app-content">

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

@else
{{-- Guest: full-width layout --}}
<div class="container-fluid py-3">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @yield('content')
</div>
@endauth

{{--  Footer  --}}
<footer class="app-footer mt-auto">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="fw-bold text-white">Druk <span style="color:var(--druk-orange)">Freelancer</span></span>
                &nbsp;&mdash;&nbsp; Bhutan&apos;s premier digital freelancing platform.
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                &copy; {{ date('Y') }} Druk Freelancer &bull; Made with &hearts; in Bhutan
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile sidebar toggle with improved UX
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('appSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (toggleBtn && sidebar && overlay) {
        // Toggle sidebar on button click
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Close sidebar when clicking a nav link on mobile
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });
        });
        
        // Close sidebar on window resize if screen becomes larger
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    }
    
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            }, 5000);
        });
    });
    
    // Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
    
    // Initialize all Bootstrap tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>
@stack('scripts')
</body>
</html>
