<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SPEEDWASH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sw-blue: #0EA5E9; --sw-navy: #0F172A; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0C2340 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }
        .auth-header {
            background: linear-gradient(135deg, var(--sw-navy), #1E3A5F);
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .brand-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--sw-blue), #38BDF8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            flex-shrink: 0;
        }
        .brand-name { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; font-weight: 800; color: white; margin: 0; }
        .brand-name span { color: var(--sw-blue); }
        .auth-body { padding: 2rem; }
        .form-label { font-size: 0.8rem; font-weight: 600; color: #334155; margin-bottom: 0.4rem; }
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
        .form-control.is-invalid { border-color: #EF4444; }
        .invalid-feedback { font-size: 0.75rem; }
        .input-icon { position: relative; }
        .input-icon i { position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.9rem; }
        .input-icon .form-control { padding-left: 2.5rem; }
        .btn-register {
            background: linear-gradient(135deg, var(--sw-blue), #38BDF8);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-register:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(14,165,233,0.4); }
        .alert { border-radius: 10px; border: none; font-size: 0.85rem; }
        .alert-danger { background: #FEF2F2; color: #991B1B; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="brand-logo" style="background:white; padding:6px;">
                <img src="{{ asset('images/Logo.png') }}" alt="SPEEDWASH" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div>
                <h1 class="brand-name">SPEED<span>WASH</span></h1>
                <div style="font-size:0.75rem; color:rgba(255,255,255,0.5);">Daftar akun baru</div>
            </div>
        </div>

        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Ada kesalahan:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h5 class="fw-bold mb-1" style="font-family:'Space Grotesk',sans-serif; color:#0F172A;">Buat Akun Baru</h5>
            <p class="text-muted mb-4" style="font-size:0.85rem;">Isi data di bawah untuk mendaftar sebagai pelanggan</p>

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nama lengkap Anda" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class="bi bi-telephone"></i>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 karakter" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" rows="2"
                            placeholder="Alamat lengkap (opsional)">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input @error('agree_terms') is-invalid @enderror"
                                type="checkbox" name="agree_terms" id="agree_terms" value="1"
                                {{ old('agree_terms') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="agree_terms" style="font-size:0.82rem;">
                                Saya setuju dengan <a href="#" class="text-decoration-none" style="color:var(--sw-blue);">Syarat & Ketentuan</a>
                                dan <a href="#" class="text-decoration-none" style="color:var(--sw-blue);">Kebijakan Privasi</a>
                            </label>
                            @error('agree_terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-register w-100 text-white mt-4">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </button>

                <div class="text-center mt-3">
                    <span style="font-size:0.85rem; color:#64748B;">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold ms-1"
                        style="font-size:0.85rem; color:var(--sw-blue);">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
