<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Lecturer Login - GCTU Face Recognition System</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            custom: {
                "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function () { sessionStorage.fonts = true; }
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gctu-premium.css') }}">
</head>
<body class="auth-body">

    <!-- Left Panel -->
    <div class="auth-panel-left" style="background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 55%, #0284c7 130%);">
        <div class="content">
            <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-brand-logo">
            <h1 class="auth-panel-title">Lecturer<br>Dashboard</h1>
            <p class="auth-panel-desc">
                Access your classes, manage students, and take attendance using advanced facial recognition.
            </p>
            
            <ul class="auth-feature-list">
                <li><i class="fas fa-chalkboard-teacher" style="color:#60a5fa;"></i> <span>Manage assigned courses</span></li>
                <li><i class="fas fa-camera-retro" style="color:#60a5fa;"></i> <span>Take AI-powered attendance</span></li>
                <li><i class="fas fa-file-excel" style="color:#60a5fa;"></i> <span>Export attendance reports</span></li>
            </ul>
        </div>
        
        <div class="auth-panel-badge">
            <span class="dot"></span> System Operational
        </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-panel-right">
        <div class="auth-form-box">
            
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-logo d-md-none">
                <h2 class="auth-title">Lecturer Portal</h2>
                <p class="auth-subtitle">Sign in to manage your classes</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4 shadow-sm" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success mb-4 shadow-sm" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4 shadow-sm" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="auth-field">
                    <label for="staff_id">Lecturer ID</label>
                    <div class="field-inner">
                        <i class="fas fa-id-badge"></i>
                        <input type="text" id="staff_id" name="staff_id"
                               class="form-control" value="{{ old('staff_id') }}"
                               placeholder="e.g. GCTU-LEC-001" required>
                    </div>
                </div>
                
                <div class="auth-field mb-4">
                    <label for="password">Password</label>
                    <div class="field-inner">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                               class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-gctu">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <div class="auth-divider">or</div>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="auth-switch-link">
                    <i class="fas fa-user-shield text-danger"></i> Switch to Admin Login
                </a>
                
                <div class="mt-4 pt-4 border-top">
                    <p class="text-muted-gctu mb-0" style="font-size:0.75rem;">
                        First time here? 
                        <a href="{{ route('register.name') }}" class="link-gctu ms-1">Activate your account</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>