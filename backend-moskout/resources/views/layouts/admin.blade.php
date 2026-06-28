<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - WaspadaDBD Admin Panel</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #f43f5e;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e1b4b 0%, #0f0728 100%);
            color: #ffffff;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand span {
            color: var(--secondary-color);
        }

        .sidebar-role-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            color: #818cf8;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.2em 0.6em;
            border-radius: 6px;
            margin-left: 0.5rem;
            vertical-align: middle;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0.75rem;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu a i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
            transition: transform 0.2s ease;
        }

        .sidebar-menu a:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu a:hover i {
            transform: scale(1.1);
        }

        .sidebar-menu li.active a {
            color: #ffffff;
            background-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Navbar top */
        .top-navbar {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Glassmorphism card utility */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        /* Status & Level Badges Customization */
        .badge-pill {
            padding: 0.4em 0.8em;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.2px;
        }

        /* Page titles */
        h1, h2, h3, h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Footer */
        .footer {
            margin-top: 3rem;
            color: var(--text-light);
            font-size: 0.85rem;
        }

        /* Custom buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
        }

        .btn-danger {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2);
        }

        .btn-danger:hover {
            background-color: #e11d48;
            border-color: #e11d48;
            transform: translateY(-1px);
        }

        /* Form Controls Custom */
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.6rem 1rem;
            border-color: var(--border-color);
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        /* Table custom styling */
        .table {
            --bs-table-hover-bg: rgba(99, 102, 241, 0.04);
        }

        .table th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--text-light);
            border-bottom: 2px solid var(--border-color);
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-shield-exclamation text-danger me-2"></i>MOSK<span>OUT</span>
            <span class="sidebar-role-badge">Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ str_starts_with(Route::currentRouteName(), 'admin.titik-risiko') ? 'active' : '' }}">
                <a href="{{ route('admin.titik-risiko.index') }}">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Titik Risiko</span>
                </a>
            </li>
            <li class="{{ str_starts_with(Route::currentRouteName(), 'admin.rekap') ? 'active' : '' }}">
                <a href="{{ route('admin.rekap') }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Rekap & Laporan</span>
                </a>
            </li>
            <li class="mt-4 pt-3 border-top border-secondary">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger-emphasis">
                    <i class="bi bi-box-arrow-right text-danger"></i>
                    <span class="text-danger">Keluar</span>
                </a>
            </li>
        </ul>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-2" id="sidebar-toggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-md-block">
                    <span class="text-muted">Halo, Selamat Datang</span>
                    <h5 class="mb-0 fw-bold">{{ Auth::user()->name }}</h5>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="/frontend/index.php" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-globe me-1"></i> Lihat Publik Frontend
                </a>
                <div class="vr"></div>
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle text-dark fw-bold d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><h6 class="dropdown-header">Petugas Admin</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Dynamic Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="footer text-center">
            <p class="mb-0">&copy; {{ date('Y') }} WaspadaDBD - Pemantauan Risiko DBD Terintegrasi. All rights reserved.</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    @yield('scripts')
</body>
</html>
