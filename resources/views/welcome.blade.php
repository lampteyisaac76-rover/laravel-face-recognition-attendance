<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GCTU Premium System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Premium Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/gctu-premium.css') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .landing-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            transition: all 0.3s ease;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #0f172a;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #154734, #0f172a);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(21, 71, 52, 0.3);
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-btn {
            padding: 10px 24px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-btn-outline {
            color: #154734;
            border: 2px solid #eef6f1;
            background: transparent;
        }

        .nav-btn-outline:hover {
            background: #eef6f1;
        }

        .nav-btn-solid {
            background: linear-gradient(135deg, #154734, #0f172a);
            color: #fff;
            box-shadow: 0 4px 12px rgba(21, 71, 52, 0.25);
        }

        .nav-btn-solid:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(21, 71, 52, 0.35);
            color: #fff;
        }

        .hero-section {
            padding: 180px 5% 100px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(to bottom, #f8fafc, #fff);
            overflow: hidden;
        }

        .hero-bg-blob {
            position: absolute;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(21,71,52,0.04) 0%, rgba(21,71,52,0) 70%);
            top: -200px;
            right: -200px;
            z-index: 0;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: #eef6f1;
            color: #154734;
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            animation: fadeInDown 0.6s ease-out;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1.1;
            color: #0f172a;
            margin-bottom: 24px;
            letter-spacing: -0.03em;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }

        .hero-title span {
            background: linear-gradient(135deg, #154734, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 600px;
            margin-inline: auto;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }

        .hero-btn {
            padding: 16px 36px;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, #154734, #0f172a);
            color: #fff;
            box-shadow: 0 10px 25px rgba(21, 71, 52, 0.3);
        }

        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(21, 71, 52, 0.4);
            color: #fff;
        }

        .hero-btn-secondary {
            background: #fff;
            color: #0f172a;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .hero-btn-secondary:hover {
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 80px;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .feature-card {
            background: #fff;
            padding: 32px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.03);
            text-align: left;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            border-color: rgba(21, 71, 52, 0.2);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .icon-green { background: #eef6f1; color: #154734; }
        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-amber { background: #fffbeb; color: #d97706; }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .feature-desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 992px) {
            .features-grid { grid-template-columns: 1fr; }
            .hero-section { padding-top: 140px; }
            .hero-actions { flex-direction: column; }
            .hero-btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    <header class="landing-header">
        <a href="/" class="brand-logo">
            <div class="brand-icon">
                <i class="fas fa-fingerprint"></i>
            </div>
            <span class="brand-text">GCTU Identity</span>
        </a>

        @if (Route::has('login'))
            <nav class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-btn nav-btn-solid">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">Sign In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-btn nav-btn-solid">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <section class="hero-section">
        <div class="hero-bg-blob"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-bolt text-warning"></i> Advanced biometric system
            </div>
            <h1 class="hero-title">
                Next-Gen <span>Face Recognition</span> Attendance
            </h1>
            <p class="hero-subtitle">
                A seamless, AI-powered platform for academic institutions. Streamline student enrollment, course management, and live classroom attendance with unparalleled accuracy and premium design.
            </p>
            
            <div class="hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="hero-btn hero-btn-primary">
                        Enter Workspace <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hero-btn hero-btn-primary">
                        Get Started <i class="fas fa-arrow-right"></i>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="hero-btn hero-btn-secondary">
                            Create Account
                        </a>
                    @endif
                @endauth
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3 class="feature-title">Live Face Scanning</h3>
                    <p class="feature-desc">Utilize cutting-edge TensorFlow.js models for real-time facial recognition directly from any device's browser.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon icon-blue">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="feature-title">Real-time Analytics</h3>
                    <p class="feature-desc">Monitor student attendance patterns, track session progress, and generate comprehensive academic reports instantly.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon icon-amber">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure & Scalable</h3>
                    <p class="feature-desc">Built with robust role-based access control, ensuring data privacy for students, lecturers, and administrators.</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
