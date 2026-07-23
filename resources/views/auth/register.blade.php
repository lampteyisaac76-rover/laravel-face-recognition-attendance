<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Lecturer Account Setup - GCTU Face Recognition System</title>
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
            <h1 class="auth-panel-title">Lecturer Account<br>Activation</h1>
            <p class="auth-panel-desc">
                Welcome to the GCTU Face Recognition Attendance System. Please activate your account using the credentials provided by your administrator.
            </p>
            
            <ul class="auth-feature-list">
                <li><i class="fas fa-check-circle" style="color:#60a5fa;"></i> <span>Verify your identity</span></li>
                <li><i class="fas fa-key" style="color:#60a5fa;"></i> <span>Set a secure password</span></li>
                <li><i class="fas fa-envelope-open-text" style="color:#60a5fa;"></i> <span>Confirm via email OTP</span></li>
            </ul>
        </div>
        
        <div class="auth-panel-badge">
            <span class="dot"></span> System Operational
        </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-panel-right">
        <div class="auth-form-box" style="max-width:440px;">
            
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-logo d-md-none">
                <h2 class="auth-title">Account Setup</h2>
                <p class="auth-subtitle">Confirm details & set a password to activate</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4 shadow-sm" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-exclamation-circle me-2 float-start mt-1"></i>
                    <ul class="mb-0 ps-4 text-start">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="auth-notice">
                <i class="fas fa-info-circle"></i>
                <div>Use the <strong>exact email and Staff ID</strong> your administrator registered you with.</div>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="auth-field">
                    <label for="email">Institutional Email</label>
                    <div class="field-inner">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               class="form-control" value="{{ old('email') }}"
                               placeholder="you@gctu.edu.gh" required>
                    </div>
                </div>

                <div class="auth-field">
                    <label for="staff_id">Staff / Lecturer ID</label>
                    <div class="field-inner">
                        <i class="fas fa-id-badge"></i>
                        <input type="text" id="staff_id" name="staff_id"
                               class="form-control" value="{{ old('staff_id') }}"
                               placeholder="GCTU-LEC-0001" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="auth-field mb-4">
                            <label for="password">Password</label>
                            <div class="field-inner">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password"
                                       class="form-control" placeholder="Min. 8 chars" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="auth-field mb-4">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="field-inner">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" placeholder="Repeat password" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-gctu">
                    <i class="fas fa-check-circle me-2"></i> Verify &amp; Continue
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="{{ route('lecturer.login') }}" class="text-muted-gctu d-inline-block"
                   style="font-size: 0.82rem; text-decoration: none; font-weight:600; color:#64748b; transition:color 0.2s;">
                    <i class="fas fa-arrow-left me-1"></i> Back to Lecturer Login
                </a>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>