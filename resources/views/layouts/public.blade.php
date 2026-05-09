<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Perpustakaan') - E-Perpustakaan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a5f;
            --primary-light: #2d5a8e;
            --accent: #f0a500;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .navbar-brand-logo {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-custom {
            background: var(--primary);
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }
        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,.8) !important;
            font-size: .9rem;
            font-weight: 500;
            transition: color .2s;
        }
        .navbar-custom .nav-link:hover {
            color: #fff !important;
        }
        .btn-nav-register {
            background: var(--accent);
            color: #fff !important;
            border-radius: 8px;
            padding: .4rem 1rem;
            font-weight: 600;
        }
        .btn-nav-register:hover {
            background: #d4920a;
            color: #fff !important;
        }
        .navbar-toggler {
            border-color: rgba(255,255,255,.3);
        }
        .navbar-toggler-icon {
            filter: invert(1);
        }
        footer {
            background: var(--primary);
            color: rgba(255,255,255,.7);
            padding: 1.5rem 0;
            font-size: .875rem;
        }
        footer a {
            color: rgba(255,255,255,.7);
            text-decoration: none;
        }
        footer a:hover {
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
            <div class="navbar-brand-logo">
                <i class="bi bi-book-fill text-white"></i>
            </div>
            E-Perpustakaan
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPublic">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing') ? 'fw-600' : '' }}" href="{{ route('landing') }}">
                        <i class="bi bi-house me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('login') ? 'fw-600' : '' }}" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-nav-register {{ request()->routeIs('register') ? 'opacity-75' : '' }}" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>Daftar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="navbar-brand-logo" style="width:28px;height:28px;">
                        <i class="bi bi-book-fill text-white" style="font-size:.75rem;"></i>
                    </div>
                    <span class="text-white fw-600">E-Perpustakaan Sekolah</span>
                </div>
                <div class="mt-1">Platform manajemen perpustakaan sekolah berbasis cloud.</div>
            </div>
            <div class="col-md-6 text-md-end">
                <div>© {{ date('Y') }} E-Perpustakaan Sekolah. All rights reserved.</div>
                <div class="mt-1">
                    <a href="{{ route('login') }}" class="me-3">Login</a>
                    <a href="{{ route('register') }}">Daftar Gratis</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
