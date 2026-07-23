<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Dashboard') - GCTU Face Recognition System</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Load FontAwesome and other custom fonts used by KaiAdmin -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ["{{ asset('assets/css/fonts.min.css') }}"]},
            active: function() { sessionStorage.fonts = true; }
        });
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gctu-premium.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-compat.css') }}">

    @stack('styles')
</head>

<body>
<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <div class="logo-header">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('lecturer.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="sidebar-logo-img">
                    <span class="sidebar-logo-text">GCTU Portal</span>
                </a>
            </div>
        </div>

        <div class="sidebar-wrapper scrollbar">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">

                    <li class="nav-item {{ request()->is('*/dashboard') ? 'active' : '' }}">
                        <a href="{{ Auth::user()->role === 'admin'
                                    ? route('admin.dashboard')
                                    : route('lecturer.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if(Auth::user()->role === 'admin')
                        
                        <li class="nav-section">
                            <h4 class="text-section">Academic Management</h4>
                        </li>

                        <li class="nav-item {{ request()->is('admin/lecturers*') ? 'active' : '' }}">
                            <a href="{{ route('admin.lecturers') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <p>Lecturers</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('admin/students*') ? 'active' : '' }}">
                            <a href="{{ route('admin.students') }}">
                                <i class="fas fa-user-graduate"></i>
                                <p>Students</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('admin/courses*') ? 'active' : '' }}">
                            <a href="{{ route('admin.courses') }}">
                                <i class="fas fa-book-open"></i>
                                <p>Courses</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('admin/academic*') ? 'active' : '' }}">
                            <a href="{{ route('admin.academic.faculties') }}">
                                <i class="fas fa-sitemap"></i>
                                <p>Faculties & Programs</p>
                            </a>
                        </li>

                    @endif

                    <li class="nav-section">
                        <h4 class="text-section">System</h4>
                    </li>

                    <li class="nav-item">
                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST">
                            @csrf
                        </form>
                        <a href="#"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <!-- END SIDEBAR -->

    <!-- MAIN PANEL -->
    <div class="main-panel">

        <!-- HEADER -->
        <header class="main-header sticky-top">
            <nav class="navbar navbar-header navbar-expand-lg">
                <div class="container-fluid d-flex align-items-center justify-content-between w-100 gap-3">

                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <button class="header-mobile-menu d-lg-none" type="button" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="min-w-0">
                            <h1 class="header-page-title text-truncate">@yield('title', 'Dashboard')</h1>
                            <p class="header-page-subtitle">
                                {{ Auth::user()->role === 'admin'
                                    ? 'Administrative control center'
                                    : 'Lecturer attendance workspace' }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-md-3 ms-auto">
                        <div class="status-indicator d-none d-md-inline-flex">
                            <span class="status-pulse"></span>
                            <span>System Online</span>
                        </div>

                        <div class="header-clock d-none d-lg-inline-flex">
                            <i class="fas fa-clock"></i>
                            <span class="system-clock">00:00:00</span>
                        </div>

                        <a href="{{ Auth::user()->role === 'admin'
                                    ? route('admin.dashboard')
                                    : route('lecturer.dashboard') }}"
                           class="header-quick-action d-none d-xl-inline-flex">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>

                        <div class="dropdown">
                            <div class="user-profile-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="gctu-avatar" style="width:34px; height:34px; font-size:0.78rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="d-none d-sm-block text-start px-1">
                                    <p class="mb-0 fw-bold" style="font-size:0.78rem; line-height:1.2;">
                                        {{ explode(' ', Auth::user()->name)[0] }}
                                    </p>
                                    <p class="mb-0 text-gctu-faint" style="font-size:0.65rem; text-transform:capitalize;">
                                        {{ Auth::user()->role }}
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down text-gctu-faint pe-2" style="font-size:0.6rem;"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end gctu-dropdown border-0">
                                <li>
                                    <a class="dropdown-item" href="{{ Auth::user()->role === 'admin'
                                                ? route('admin.dashboard')
                                                : route('lecturer.dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form-header">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" style="background:transparent;border:none;width:100%;text-align:left;">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <!-- CONTENT -->
        <main class="container-fluid p-0 m-0 flex-grow-1">
            <div class="main-content-area">
                @stack('header')
                <div class="pb-4">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="gctu-footer">
            <div class="container-fluid px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="footer-copyright d-flex align-items-center gap-2">
                        <span>&copy; {{ date('Y') }} Ghana Communication Technology University.</span>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-alt text-gctu-gold" style="font-size: 0.85rem;"></i>
                            <span class="footer-copyright">Secure Face Recognition System</span>
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <span class="footer-clock system-clock"></span>
                            <span class="footer-version">v3.0.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

       <script>
    function updateClock() {
        const now = new Date();

        const hours = String(now.getHours()).padStart(2,'0');
        const minutes = String(now.getMinutes()).padStart(2,'0');
        const seconds = String(now.getSeconds()).padStart(2,'0');

        document.querySelectorAll('#system-clock').forEach(el=>{
            el.textContent=`${hours}:${minutes}:${seconds} GMT`;
        });
    }

    setInterval(updateClock,1000);

    updateClock();

    document.querySelector('.header-mobile-menu')?.addEventListener('click',function(){

        document.body.classList.toggle('nav_open');

    });
</script>


<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>

<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>

<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
{{-- VERY IMPORTANT --}}
@stack('scripts')

</body>

</html>
    