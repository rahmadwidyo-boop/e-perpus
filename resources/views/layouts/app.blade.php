<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Perpustakaan') - E-Perpustakaan Sekolah</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #1e3a5f;
            --primary-light: #2d5a8e;
            --accent: #f0a500;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--primary);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1rem;
        }
        .sidebar-brand small {
            color: rgba(255,255,255,.6);
            font-size: .75rem;
        }
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }
        .sidebar-nav .nav-label {
            color: rgba(255,255,255,.4);
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .5rem 1.25rem .25rem;
        }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,.75);
            padding: .6rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .875rem;
            border-radius: 0;
            transition: all .2s;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left: 3px solid var(--accent);
            padding-left: calc(1.25rem - 3px);
        }
        .sidebar-nav .nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        /* Main content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            padding: .75rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .topbar .page-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary);
            margin: 0;
        }
        .content-area {
            padding: 1.5rem;
            flex: 1;
        }
        /* Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }
        .stat-card .icon-box {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: .8rem;
            color: #6c757d;
            margin-top: .2rem;
        }
        /* Table */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
            overflow: hidden;
        }
        .table-card .table {
            margin: 0;
        }
        .table-card .table thead th {
            background: #f8f9fa;
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6c757d;
            border-bottom: 1px solid #dee2e6;
            padding: .75rem 1rem;
        }
        .table-card .table tbody td {
            padding: .75rem 1rem;
            vertical-align: middle;
            font-size: .875rem;
        }
        /* Badge status */
        .badge-dipinjam   { background: #fff3cd; color: #856404; }
        .badge-dikembalikan { background: #d1e7dd; color: #0f5132; }
        .badge-terlambat  { background: #f8d7da; color: #842029; }
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-book-fill text-white"></i>
            </div>
            <div>
                <h5>E-Perpustakaan</h5>
                <small>Sistem Perpustakaan Sekolah</small>
            </div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-label mt-2">Manajemen Data</div>
        <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark-fill"></i> Data Buku
        </a>
        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Data Siswa
        </a>
        <a href="{{ route('students.import.form') }}" class="nav-link {{ request()->routeIs('students.import*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Siswa
        </a>

        <div class="nav-label mt-2">Transaksi</div>
        <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Peminjaman
        </a>
        <a href="{{ route('loans.create') }}" class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill"></i> Pinjam Buku
        </a>

        <div class="nav-label mt-2">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Laporan & Export
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:32px;height:32px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill text-white" style="font-size:.85rem;"></i>
            </div>
            <div>
                <div style="color:#fff;font-size:.8rem;font-weight:600;">{{ Auth::user()->name }}</div>
                <div style="color:rgba(255,255,255,.5);font-size:.7rem;">Administrator</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,.1);color:#fff;border:none;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</nav>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" id="sidebarToggle" style="border:none;background:none;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="page-title">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:#e8f0fe;color:var(--primary);font-size:.75rem;">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
