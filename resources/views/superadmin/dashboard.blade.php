@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#e8f0fe;">
                <i class="bi bi-building" style="color:#1e3a5f;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#1e3a5f;">{{ number_format($totalSchools) }}</div>
                <div class="stat-label">Total Sekolah</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#d1e7dd;">
                <i class="bi bi-check-circle-fill" style="color:#0f5132;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0f5132;">{{ number_format($schoolsByStatus->get('active', 0)) }}</div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#fff3cd;">
                <i class="bi bi-clock-fill" style="color:#856404;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#856404;">{{ number_format($schoolsByStatus->get('trial', 0)) }}</div>
                <div class="stat-label">Trial</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#f8d7da;">
                <i class="bi bi-x-circle-fill" style="color:#842029;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#842029;">{{ number_format($schoolsByStatus->get('expired', 0)) }}</div>
                <div class="stat-label">Expired</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#e2e3e5;">
                <i class="bi bi-pause-circle-fill" style="color:#41464b;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#41464b;">{{ number_format($schoolsByStatus->get('suspended', 0)) }}</div>
                <div class="stat-label">Suspended</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#fff8e1;">
                <i class="bi bi-cash-stack" style="color:#f57f17;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#f57f17;font-size:1.1rem;">
                    Rp {{ number_format($totalRevenue / 1000000, 1) }}jt
                </div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="table-card p-3 h-100">
            <h6 class="fw-600 mb-3" style="font-weight:600;color:#1e3a5f;">
                <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                    <i class="bi bi-building me-2"></i>Kelola Sekolah
                </a>
                <a href="{{ route('superadmin.payments.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-credit-card me-2"></i>Verifikasi Pembayaran
                </a>
            </div>
        </div>
    </div>

    {{-- Revenue Summary --}}
    <div class="col-lg-8">
        <div class="table-card p-3">
            <h6 class="fw-600 mb-3" style="font-weight:600;color:#1e3a5f;">
                <i class="bi bi-bar-chart-fill me-2"></i>Ringkasan Platform
            </h6>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Total Pendapatan</div>
                        <div class="fw-700 fs-5" style="font-weight:700;color:#1e3a5f;">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </div>
                        <div class="small text-muted">Dari pembayaran yang disetujui</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Tingkat Konversi Trial</div>
                        @php
                            $trialCount = $schoolsByStatus->get('trial', 0);
                            $activeCount = $schoolsByStatus->get('active', 0);
                            $conversionRate = ($totalSchools > 0)
                                ? round(($activeCount / $totalSchools) * 100, 1)
                                : 0;
                        @endphp
                        <div class="fw-700 fs-5" style="font-weight:700;color:#0f5132;">{{ $conversionRate }}%</div>
                        <div class="small text-muted">Sekolah aktif dari total</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Sekolah Aktif + Trial</div>
                        <div class="fw-700 fs-5" style="font-weight:700;color:#856404;">
                            {{ number_format($activeCount + $trialCount) }}
                        </div>
                        <div class="small text-muted">Pengguna aktif saat ini</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Perlu Perhatian</div>
                        <div class="fw-700 fs-5" style="font-weight:700;color:#842029;">
                            {{ number_format($schoolsByStatus->get('expired', 0) + $schoolsByStatus->get('suspended', 0)) }}
                        </div>
                        <div class="small text-muted">Expired + Suspended</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
