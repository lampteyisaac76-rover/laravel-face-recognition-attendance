<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Registration - GCTU Face Recognition System</title>
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
    <div class="auth-panel-left">
        <div class="content">
            <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-brand-logo">
            <h1 class="auth-panel-title">System Administration<br>Access Setup</h1>
            <p class="auth-panel-desc">
                Authorized personnel only. You must have a Master Admin Code to register a new administrative account.
            </p>
            
            <ul class="auth-feature-list">
                <li><i class="fas fa-users-cog"></i> <span>Manage all faculty and students</span></li>
                <li><i class="fas fa-cogs"></i> <span>Configure global system settings</span></li>
                <li><i class="fas fa-lock"></i> <span>High-level security clearance</span></li>
            </ul>
        </div>
        
        <div class="auth-panel-badge">
            <span class="dot" style="background:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.3);"></span> Restricted Area
        </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-panel-right">
        <div class="auth-form-box" style="max-width:440px;">
            
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-logo d-md-none">
                <h2 class="auth-title">Admin Registration</h2>
                <p class="auth-subtitle">Create a new administrative account</p>
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

            @if(session('success'))
                <div class="alert alert-success mb-4 shadow-sm" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.register.post') }}" method="POST">
                @csrf
                
                <div class="auth-field">
                    <label for="name">Full Name</label>
                    <div class="field-inner">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name"
                               class="form-control" value="{{ old('name') }}"
                               placeholder="e.g. John Mensah" required>
                    </div>
                </div>

                <div class="auth-field">
                    <label for="email">Institutional Email</label>
                    <div class="field-inner">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               class="form-control" value="{{ old('email') }}"
                               placeholder="admin@gctu.edu.gh" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="auth-field">
                            <label for="staff_id">Staff ID</label>
                            <div class="field-inner">
                                <i class="fas fa-id-badge"></i>
                                <input type="text" id="staff_id" name="staff_id"
                                       class="form-control" value="{{ old('staff_id') }}"
                                       placeholder="GCTU-ADM-001" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="auth-field">
                            <label for="phone_number">Phone Number</label>
                            <div class="field-inner">
                                <i class="fas fa-phone"></i>
                                <input type="text" id="phone_number" name="phone_number"
                                       class="form-control" value="{{ old('phone_number') }}"
                                       placeholder="+233..." required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="auth-notice">
                    <i class="fas fa-key"></i>
                    <div>Registration requires the master admin code provided by IT services.</div>
                </div>

                <div class="auth-field">
                    <label for="admin_code">Master Admin Code</label>
                    <div class="field-inner">
                        <i class="fas fa-shield-alt" style="color:#eab308;"></i>
                        <input type="password" id="admin_code" name="admin_code"
                               class="form-control" placeholder="Enter security code" required>
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

                <button type="submit" class="btn-gctu" style="background:linear-gradient(135deg,#0f172a,#334155); box-shadow:0 8px 24px rgba(15,23,42,0.25);">
                    <i class="fas fa-user-shield me-2"></i> Create Admin Account
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="{{ route('admin.login') }}" class="text-muted-gctu d-inline-block"
                   style="font-size: 0.82rem; text-decoration: none; font-weight:600; color:#64748b; transition:color 0.2s;">
                    <i class="fas fa-arrow-left me-1"></i> Back to Admin Login
                </a>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>