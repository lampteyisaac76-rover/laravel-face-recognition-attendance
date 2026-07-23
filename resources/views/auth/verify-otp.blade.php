<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Verify OTP - GCTU Face Recognition System</title>
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
<body class="auth-body" style="grid-template-columns:1fr; background:radial-gradient(circle at 75% 10%, rgba(79,70,229,0.12), transparent 32%), radial-gradient(circle at 20% 85%, rgba(14,165,233,0.1), transparent 28%), #f1f5f9;">

    <!-- Single Center Panel for OTP -->
    <div style="display:flex; justify-content:center; align-items:center; min-height:100vh; padding:2rem;">
        <div class="auth-form-box text-center" style="background:#fff; padding:48px 40px; border-radius:24px; box-shadow:0 24px 64px rgba(15,23,42,0.12); max-width:440px; width:100%;">
            
            <img src="{{ asset('assets/img/gctu_logo_official.jpg') }}" alt="GCTU" class="auth-logo mx-auto">
            <h2 class="auth-title">Verify Identity</h2>
            <p class="auth-subtitle mb-4">Enter the 6-digit code sent to your email</p>

            @if($errors->any())
                <div class="alert alert-danger mb-4 shadow-sm text-start" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-4 shadow-sm text-start" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success mb-4 shadow-sm text-start" style="border-radius:12px; font-size:0.85rem; font-weight:500;">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('verify.otp.post') }}" method="POST">
                @csrf
                <input type="hidden" name="staff_id" value="{{ $staff_id }}">

                <div class="auth-field mb-4">
                    <input type="text" name="code"
                           class="form-control otp-input-large"
                           maxlength="6" placeholder="000000"
                           required autofocus autocomplete="off">
                </div>

                <div class="auth-notice text-start mb-4">
                    <i class="fas fa-clock"></i>
                    <div>Code expires in <strong>10 minutes</strong>. Please check your spam folder if you don't see it.</div>
                </div>

                <button type="submit" class="btn-gctu" style="padding:14px;">
                    <i class="fas fa-shield-alt me-2"></i> Verify &amp; Activate
                </button>
            </form>

            <div class="mt-4 pt-3 border-top">
                <p class="text-muted-gctu mb-0" style="font-size:0.82rem; font-weight:500;">
                    Didn't receive a code?
                    <a href="{{ route('resend.otp', ['staff_id' => $staff_id]) }}" class="link-gctu ms-1">
                        Resend Code
                    </a>
                </p>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>