<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPEEDWASH') - Cuci Motor Cepat</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sw-blue: #0EA5E9;
            --sw-blue-dark: #0284C7;
            --sw-navy: #0F172A;
            --sw-slate: #1E293B;
            --sw-surface: #F8FAFC;
            --sw-border: #E2E8F0;
            --sw-text: #334155;
            --sw-muted: #94A3B8;
            --sw-success: #10B981;
            --sw-warning: #F59E0B;
            --sw-danger: #EF4444;
            --sw-sidebar-width: 260px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--sw-surface);
            color: var(--sw-text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .brand-text {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sw-sidebar-width);
            height: 100vh;
            background: var(--sw-navy);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--sw-blue), #38BDF8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.5px;
        }

        .sidebar-brand-text span {
            color: var(--sw-blue);
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.35);
            padding: 1rem 1.25rem 0.4rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.15s ease;
            position: relative;
        }

        .sidebar-link:hover {
            color: white;
            background: rgba(255,255,255,0.06);
        }

        .sidebar-link.active {
            color: white;
            background: rgba(14, 165, 233, 0.15);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--sw-blue);
            border-radius: 0 2px 2px 0;
        }

        .sidebar-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        /* MAIN CONTENT */
        .main-wrapper {
            margin-left: var(--sw-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid var(--sw-border);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .page-content {
            padding: 1.75rem 1.5rem;
            flex: 1;
        }

        /* CARDS */
        .card {
            border: 1px solid var(--sw-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--sw-border);
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border: 1px solid var(--sw-border);
            border-radius: 14px;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.blue { background: #EFF6FF; color: var(--sw-blue); }
        .stat-icon.green { background: #ECFDF5; color: var(--sw-success); }
        .stat-icon.yellow { background: #FFFBEB; color: var(--sw-warning); }
        .stat-icon.red { background: #FEF2F2; color: var(--sw-danger); }
        .stat-icon.purple { background: #F5F3FF; color: #7C3AED; }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--sw-navy);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--sw-muted);
            font-weight: 500;
        }

        /* BUTTONS */
        .btn-primary {
            background: var(--sw-blue);
            border-color: var(--sw-blue);
        }
        .btn-primary:hover {
            background: var(--sw-blue-dark);
            border-color: var(--sw-blue-dark);
        }

        /* BADGES */
        .badge {
            font-weight: 500;
            font-size: 0.7rem;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
        }

        /* TABLES */
        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--sw-muted);
            border-bottom: 1px solid var(--sw-border);
            padding: 0.875rem 1rem;
        }

        .table td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
            border-color: var(--sw-border);
            font-size: 0.875rem;
        }

        .table tbody tr:hover {
            background-color: #F8FAFC;
        }

        /* FORMS */
        .form-control, .form-select {
            border-color: var(--sw-border);
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.55rem 0.875rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--sw-blue);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--sw-slate);
            margin-bottom: 0.4rem;
        }

        /* QUEUE DISPLAY */
        .queue-number-display {
            font-size: 4rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            color: var(--sw-navy);
            line-height: 1;
        }

        /* PACKAGE CARDS */
        .package-card {
            border: 2px solid var(--sw-border);
            border-radius: 14px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .package-card:hover {
            border-color: var(--sw-blue);
            box-shadow: 0 4px 20px rgba(14, 165, 233, 0.15);
        }

        .package-card.selected {
            border-color: var(--sw-blue);
            background: #F0F9FF;
        }

        /* NOTIFICATIONS */
        .notification-dot {
            width: 8px;
            height: 8px;
            background: var(--sw-danger);
            border-radius: 50%;
            position: absolute;
            top: 2px;
            right: 2px;
        }

        /* MOBILE TOGGLE */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--sw-text);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }

        /* ALERTS */
        .alert {
            border-radius: 10px;
            border: none;
            font-size: 0.875rem;
        }

        .alert-success { background: #ECFDF5; color: #065F46; }
        .alert-danger { background: #FEF2F2; color: #991B1B; }
        .alert-warning { background: #FFFBEB; color: #92400E; }
        .alert-info { background: #EFF6FF; color: #1E40AF; }

        /* USER AVATAR */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* PROGRESS STEPS */
        .step-indicator {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .step-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--sw-border);
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            font-size: 0.85rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .step.active .step-icon {
            border-color: var(--sw-blue);
            background: var(--sw-blue);
            color: white;
        }

        .step.done .step-icon {
            border-color: var(--sw-success);
            background: var(--sw-success);
            color: white;
        }

        .step::after {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(50% + 18px);
            right: calc(-50% + 18px);
            height: 2px;
            background: var(--sw-border);
        }

        .step.done::after {
            background: var(--sw-success);
        }

        .step:last-child::after { display: none; }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.show {
                display: block;
            }
            .sidebar-toggle {
                display: block;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .page-content {
                padding: 1rem;
            }
            .stat-value {
                font-size: 1.3rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : route('customer.dashboard') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon" style="background:white; padding:5px;">
            <img src="{{ asset('images/Logo.png') }}" alt="SPEEDWASH" style="width:100%; height:100%; object-fit:contain;">
        </div>
        <div class="sidebar-brand-text">SPEED<span>WASH</span></div>
    </a>

    <nav class="sidebar-nav">
        @if(auth()->user()?->isAdmin())
            <div class="nav-section-label">Menu Admin</div>

            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="{{ route('admin.queue') }}" class="sidebar-link {{ request()->routeIs('admin.queue') ? 'active' : '' }}">
                <i class="bi bi-list-ol"></i> Monitor Antrian
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Kelola Booking
            </a>

            <div class="nav-section-label">Manajemen</div>
            <a href="{{ route('admin.packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Paket Layanan
            </a>
            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Pelanggan
            </a>
            <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Laporan
            </a>

        @else
            <div class="nav-section-label">Menu</div>

            <a href="{{ route('customer.dashboard') }}" class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="{{ route('customer.bookings.create') }}" class="sidebar-link {{ request()->routeIs('customer.bookings.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Booking Cuci
            </a>
            <a href="{{ route('customer.bookings.index') }}" class="sidebar-link {{ request()->routeIs('customer.bookings.index') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Riwayat Booking
            </a>
            <a href="{{ route('customer.queue') }}" class="sidebar-link {{ request()->routeIs('customer.queue') ? 'active' : '' }}">
                <i class="bi bi-list-ol"></i> Monitor Antrian
            </a>

            <div class="nav-section-label">Akun</div>
            <a href="{{ route('customer.profile') }}" class="sidebar-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
        @endif
    </nav>

    <!-- Sidebar Footer -->
    <div style="padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.08);">
        <div class="d-flex align-items-center gap-2 mb-3">
            <img src="{{ auth()->user()?->avatar_url }}" class="user-avatar" alt="Avatar">
            <div style="min-width:0">
                <div style="font-size:0.8rem; font-weight:600; color:white; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ auth()->user()?->name }}
                </div>
                <div style="font-size:0.7rem; color:rgba(255,255,255,0.4);">
                    {{ auth()->user()?->isAdmin() ? 'Administrator' : 'Pelanggan' }}
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm w-100"
                style="background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.65); border:none; font-size:0.8rem;">
                <i class="bi bi-box-arrow-left me-1"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h6 class="mb-0 fw-semibold" style="font-size:0.9rem;">@yield('page-title', 'Dashboard')</h6>
                <div style="font-size:0.75rem; color:var(--sw-muted);">@yield('page-subtitle', '')</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Notifications -->
            <button class="btn btn-sm btn-light position-relative" style="width:36px; height:36px; border-radius:10px; border:1px solid var(--sw-border);">
                <i class="bi bi-bell" style="font-size:0.9rem;"></i>
                @if(auth()->user()?->unreadNotifications->count() > 0)
                    <span class="notification-dot"></span>
                @endif
            </button>

            <!-- Time -->
            <div class="d-none d-md-block text-end">
                <div style="font-size:0.8rem; font-weight:600;" id="topbar-time"></div>
                <div style="font-size:0.7rem; color:var(--sw-muted);">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <strong>Ada kesalahan:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Real-time clock
    function updateTime() {
        const el = document.getElementById('topbar-time');
        if (el) {
            el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.style.transition = 'opacity 0.5s';
            a.style.opacity = '0';
            setTimeout(() => a.remove(), 500);
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
