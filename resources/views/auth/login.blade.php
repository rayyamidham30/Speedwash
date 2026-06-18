<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPEEDWASH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sw-blue: #0EA5E9;
            --sw-navy: #0F172A;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0C2340 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }
        .auth-header {
            background: linear-gradient(135deg, var(--sw-navy), #1E3A5F);
            padding: 2rem;
            text-align: center;
        }
        .brand-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--sw-blue), #38BDF8);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.6rem;
            color: white;
        }
        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-name span { color: var(--sw-blue); }
        .brand-tagline {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.25rem;
        }
        .auth-body { padding: 2rem; }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }
        .form-control {
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 0.65rem 0.875rem;
            font-size: 0.875rem;
        }
        .form-control:focus {
            border-color: var(--sw-blue);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        .input-icon {
            position: relative;
        }
        .input-icon i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.9rem;
        }
        .input-icon .form-control { padding-left: 2.5rem; }
        .btn-login {
            background: linear-gradient(135deg, var(--sw-blue), #38BDF8);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            transition: all 0.2s;
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(14,165,233,0.4); }
        .divider {
            text-align: center;
            position: relative;
            margin: 1.25rem 0;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #E2E8F0;
        }
        .divider span {
            background: white;
            padding: 0 0.75rem;
            position: relative;
            font-size: 0.75rem;
            color: #94A3B8;
        }
        .alert { border-radius: 10px; border: none; font-size: 0.85rem; }
        .alert-danger { background: #FEF2F2; color: #991B1B; }
        .bubbles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.06);
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
    </style>
</head>
<body>
    <div class="bubbles">
        <div class="bubble" style="width:300px;height:300px;top:-100px;right:-50px;animation-delay:0s;"></div>
        <div class="bubble" style="width:200px;height:200px;bottom:-50px;left:-50px;animation-delay:3s;"></div>
        <div class="bubble" style="width:150px;height:150px;bottom:30%;right:10%;animation-delay:5s;"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="brand-logo">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <h1 class="brand-name">SPEED<span>WASH</span></h1>
            <p class="brand-tagline">Cuci Motor Cepat Berbasis Teknologi</p>
        </div>

        <div class="auth-body">
            @if(session('success'))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            <h5 class="fw-bold mb-1" style="font-family:'Space Grotesk',sans-serif; color:#0F172A;">Masuk ke Akun</h5>
            <p class="text-muted mb-4" style="font-size:0.85rem;">Gunakan email dan password Anda untuk masuk</p>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">Password</label>
                    </div>
                    <div class="input-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.8rem;">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="divider"><span>atau</span></div>

            <div class="text-center">
                <span style="font-size:0.85rem; color:#64748B;">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold ms-1" style="font-size:0.85rem; color:var(--sw-blue);">
                    Daftar Sekarang
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
