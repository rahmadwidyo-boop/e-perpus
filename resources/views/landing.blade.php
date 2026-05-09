@extends('layouts.public')

@section('title', 'Beranda')

@push('styles')
<style>
    /* Hero */
    .hero-section {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 60%, #1e3a5f 100%);
        padding: 5rem 0 4rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 400px; height: 400px;
        background: rgba(240,165,0,.12);
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -60px;
        width: 300px; height: 300px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }
    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255,255,255,.8);
        max-width: 520px;
    }
    .btn-hero-primary {
        background: #f0a500;
        color: #fff;
        border: none;
        padding: .8rem 2rem;
        font-weight: 700;
        border-radius: 10px;
        font-size: 1rem;
        transition: background .2s, transform .15s;
    }
    .btn-hero-primary:hover {
        background: #d4920a;
        color: #fff;
        transform: translateY(-1px);
    }
    .btn-hero-outline {
        background: transparent;
        color: #fff;
        border: 2px solid rgba(255,255,255,.5);
        padding: .8rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        font-size: 1rem;
        transition: all .2s;
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,.1);
        border-color: #fff;
        color: #fff;
    }
    .hero-badge {
        background: rgba(240,165,0,.2);
        border: 1px solid rgba(240,165,0,.4);
        color: #f0a500;
        padding: .35rem .9rem;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }
    /* Features */
    .features-section {
        padding: 5rem 0;
        background: #fff;
    }
    .feature-card {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 2rem 1.5rem;
        height: 100%;
        transition: transform .2s, box-shadow .2s;
        border: 1px solid #e9ecef;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,.1);
    }
    .feature-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #1e3a5f;
    }
    .section-subtitle {
        color: #6c757d;
        font-size: 1rem;
        max-width: 500px;
        margin: 0 auto;
    }
    /* Pricing */
    .pricing-section {
        padding: 5rem 0;
        background: #f4f6f9;
    }
    .pricing-card {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,.1);
        border: 2px solid #1e3a5f;
        max-width: 420px;
        margin: 0 auto;
        position: relative;
    }
    .pricing-badge {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: #f0a500;
        color: #fff;
        padding: .3rem 1.2rem;
        border-radius: 20px;
        font-size: .8rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .pricing-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e3a5f;
    }
    .pricing-period {
        font-size: .9rem;
        color: #6c757d;
    }
    .pricing-feature {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .4rem 0;
        font-size: .9rem;
        color: #495057;
    }
    .pricing-feature i {
        color: #2e7d32;
        font-size: 1rem;
    }
    .btn-pricing {
        background: #1e3a5f;
        color: #fff;
        border: none;
        padding: .85rem;
        font-weight: 700;
        border-radius: 10px;
        font-size: 1rem;
        transition: background .2s;
        width: 100%;
    }
    .btn-pricing:hover {
        background: #2d5a8e;
        color: #fff;
    }
    /* Stats */
    .stats-section {
        background: #1e3a5f;
        padding: 3rem 0;
        color: #fff;
    }
    .stat-item .stat-num {
        font-size: 2.2rem;
        font-weight: 800;
        color: #f0a500;
    }
    .stat-item .stat-desc {
        font-size: .9rem;
        color: rgba(255,255,255,.7);
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge">
                    <i class="bi bi-stars me-1"></i>Trial 30 Hari Gratis
                </div>
                <h1 class="hero-title mb-3">
                    E-Perpustakaan<br>
                    <span style="color:#f0a500;">Sekolah</span> Digital
                </h1>
                <p class="hero-subtitle mb-4">
                    Kelola perpustakaan sekolah Anda dengan mudah dan efisien. Manajemen buku, siswa, peminjaman, dan laporan dalam satu platform.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-hero-primary">
                        <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-hero-outline">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                </div>
                <div class="mt-4 d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,.7);font-size:.85rem;">
                        <i class="bi bi-check-circle-fill" style="color:#f0a500;"></i>
                        Tanpa kartu kredit
                    </div>
                    <div class="d-flex align-items-center gap-1" style="color:rgba(255,255,255,.7);font-size:.85rem;">
                        <i class="bi bi-check-circle-fill" style="color:#f0a500;"></i>
                        Setup dalam 2 menit
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="width:280px;height:280px;background:rgba(255,255,255,.08);border-radius:24px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.15);">
                    <i class="bi bi-book-fill" style="font-size:8rem;color:rgba(240,165,0,.6);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="stats-section">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">500+</div>
                <div class="stat-desc">Sekolah Terdaftar</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">50K+</div>
                <div class="stat-desc">Buku Terkelola</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">200K+</div>
                <div class="stat-desc">Transaksi Peminjaman</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-num">99.9%</div>
                <div class="stat-desc">Uptime Layanan</div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Fitur Lengkap untuk Perpustakaan Anda</h2>
            <p class="section-subtitle mt-2">Semua yang Anda butuhkan untuk mengelola perpustakaan sekolah secara digital.</p>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f0fe;">
                        <i class="bi bi-journal-bookmark-fill" style="color:#1e3a5f;"></i>
                    </div>
                    <h5 class="fw-700 mb-2" style="font-weight:700;color:#1e3a5f;">Manajemen Buku</h5>
                    <p class="text-muted small mb-0">Kelola koleksi buku dengan mudah. Tambah, edit, dan cari buku berdasarkan judul, pengarang, atau kategori.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f5e9;">
                        <i class="bi bi-people-fill" style="color:#2e7d32;"></i>
                    </div>
                    <h5 class="fw-700 mb-2" style="font-weight:700;color:#1e3a5f;">Manajemen Siswa</h5>
                    <p class="text-muted small mb-0">Data siswa terorganisir rapi. Import massal via Excel, kelola kelas, dan pantau riwayat peminjaman per siswa.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fff8e1;">
                        <i class="bi bi-arrow-left-right" style="color:#f57f17;"></i>
                    </div>
                    <h5 class="fw-700 mb-2" style="font-weight:700;color:#1e3a5f;">Peminjaman</h5>
                    <p class="text-muted small mb-0">Proses peminjaman dan pengembalian buku dengan cepat. Notifikasi otomatis untuk buku yang terlambat dikembalikan.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fce4ec;">
                        <i class="bi bi-file-earmark-spreadsheet-fill" style="color:#c62828;"></i>
                    </div>
                    <h5 class="fw-700 mb-2" style="font-weight:700;color:#1e3a5f;">Laporan Excel</h5>
                    <p class="text-muted small mb-0">Export laporan peminjaman ke Excel dengan satu klik. Analisis data perpustakaan secara mendalam dan akurat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Pricing Section --}}
<section class="pricing-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Harga Terjangkau</h2>
            <p class="section-subtitle mt-2">Mulai gratis selama 30 hari, lanjutkan dengan harga yang sangat terjangkau.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="pricing-card">
                    <div class="pricing-badge">
                        <i class="bi bi-stars me-1"></i>Paling Populer
                    </div>
                    <div class="text-center mb-4">
                        <h4 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">Paket Sekolah</h4>
                        <div class="mb-2">
                            <span class="badge" style="background:#d1e7dd;color:#0f5132;font-size:.8rem;">
                                <i class="bi bi-gift-fill me-1"></i>Trial 30 Hari Gratis
                            </span>
                        </div>
                        <div class="pricing-price">Rp 1.500.000</div>
                        <div class="pricing-period">per tahun setelah trial</div>
                    </div>
                    <div class="mb-4">
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Manajemen buku tidak terbatas
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Manajemen siswa tidak terbatas
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Transaksi peminjaman & pengembalian
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Import siswa via Excel
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Export laporan ke Excel
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Dashboard statistik real-time
                        </div>
                        <div class="pricing-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            Support teknis via email
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-pricing">
                        <i class="bi bi-person-plus me-2"></i>Mulai Trial Gratis
                    </a>
                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="bi bi-shield-check me-1"></i>Tanpa kartu kredit. Batalkan kapan saja.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section style="background:#1e3a5f;padding:4rem 0;">
    <div class="container text-center">
        <h2 style="color:#fff;font-weight:800;font-size:2rem;" class="mb-3">Siap Digitalisasi Perpustakaan Anda?</h2>
        <p style="color:rgba(255,255,255,.75);font-size:1rem;" class="mb-4">Bergabung dengan ratusan sekolah yang sudah menggunakan E-Perpustakaan.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-hero-primary">
                <i class="bi bi-person-plus me-2"></i>Daftar Gratis Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn btn-hero-outline">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sudah Punya Akun?
            </a>
        </div>
    </div>
</section>

@endsection
