@extends('layouts.app')

@section('title', 'Info Langganan')
@section('page-title', 'Info Langganan')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Status Card --}}
        <div class="table-card p-4 mb-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:52px;height:52px;background:#e8f0fe;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-credit-card-fill" style="color:#1e3a5f;font-size:1.4rem;"></i>
                </div>
                <div>
                    <h5 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">Status Langganan</h5>
                    <p class="text-muted small mb-0">{{ $school->name }}</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Status Saat Ini</div>
                        @php
                            $status = $school->subscription_status;
                            $badgeConfig = [
                                'trial'     => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-clock-fill',           'label' => 'Trial'],
                                'active'    => ['bg' => '#d1e7dd', 'color' => '#0f5132', 'icon' => 'bi-check-circle-fill',    'label' => 'Aktif'],
                                'expired'   => ['bg' => '#f8d7da', 'color' => '#842029', 'icon' => 'bi-x-circle-fill',        'label' => 'Kedaluwarsa'],
                                'suspended' => ['bg' => '#e2e3e5', 'color' => '#41464b', 'icon' => 'bi-pause-circle-fill',    'label' => 'Disuspend'],
                            ];
                            $cfg = $badgeConfig[$status] ?? $badgeConfig['expired'];
                        @endphp
                        <span class="badge fs-6 px-3 py-2" style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};">
                            <i class="bi {{ $cfg['icon'] }} me-2"></i>{{ $cfg['label'] }}
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Sisa Hari</div>
                        @php $daysLeft = $school->daysRemaining(); @endphp
                        @if(in_array($status, ['expired', 'suspended']))
                            <div class="fw-700 fs-4" style="font-weight:700;color:#842029;">—</div>
                            <div class="small text-danger">Langganan tidak aktif</div>
                        @else
                            <div class="fw-700 fs-4" style="font-weight:700;color:{{ $daysLeft <= 7 ? '#842029' : '#0f5132' }};">
                                {{ $daysLeft }} hari
                            </div>
                            @if($daysLeft <= 7)
                                <div class="small text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Segera perpanjang!</div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Tanggal Berakhir</div>
                        @if($status === 'trial' && $school->trial_ends_at)
                            <div class="fw-600" style="font-weight:600;">{{ $school->trial_ends_at->format('d F Y') }}</div>
                            <div class="small text-muted">Akhir masa trial</div>
                        @elseif($status === 'active' && $school->subscription_ends_at)
                            <div class="fw-600" style="font-weight:600;">{{ $school->subscription_ends_at->format('d F Y') }}</div>
                            <div class="small text-muted">Akhir masa langganan</div>
                        @else
                            <div class="fw-600 text-muted" style="font-weight:600;">—</div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3" style="background:#f8f9fa;">
                        <div class="small text-muted mb-1">Jenis Langganan</div>
                        <div class="fw-600" style="font-weight:600;">Paket Sekolah</div>
                        <div class="small text-muted">Rp 1.500.000 / tahun</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Instructions --}}
        <div class="table-card p-4 mb-4">
            <h6 class="fw-700 mb-3" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-info-circle-fill me-2"></i>Cara Melakukan Pembayaran
            </h6>

            <div class="alert" style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;" role="alert">
                <div class="fw-600 mb-2" style="font-weight:600;color:#856404;">
                    <i class="bi bi-bank me-2"></i>Transfer Bank
                </div>
                <div class="row g-2">
                    <div class="col-sm-6">
                        <div class="small text-muted">Bank</div>
                        <div class="fw-600" style="font-weight:600;">BCA (Bank Central Asia)</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Nomor Rekening</div>
                        <div class="fw-700 fs-5 d-flex align-items-center gap-2" style="font-weight:700;color:#1e3a5f;">
                            1234567890
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2"
                                    onclick="navigator.clipboard.writeText('1234567890');this.innerHTML='<i class=\'bi bi-check\'></i>'"
                                    title="Salin nomor rekening" style="font-size:.7rem;">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Atas Nama</div>
                        <div class="fw-600" style="font-weight:600;">E-Perpustakaan</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="small text-muted">Nominal Transfer</div>
                        <div class="fw-700" style="font-weight:700;color:#1e3a5f;">Rp 1.500.000</div>
                    </div>
                </div>
            </div>

            <ol class="small text-muted ps-3 mb-0">
                <li class="mb-1">Transfer sejumlah <strong>Rp 1.500.000</strong> ke rekening BCA di atas.</li>
                <li class="mb-1">Klik tombol <strong>"Ajukan Pembayaran"</strong> dan isi formulir dengan data transfer Anda.</li>
                <li class="mb-1">Tim kami akan memverifikasi pembayaran dalam <strong>1×24 jam kerja</strong>.</li>
                <li>Setelah diverifikasi, langganan Anda akan aktif selama <strong>1 tahun</strong>.</li>
            </ol>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('subscription.payment') }}" class="btn px-4 py-2" style="background:#1e3a5f;color:#fff;border-radius:10px;font-weight:600;">
                <i class="bi bi-send-fill me-2"></i>Ajukan Pembayaran
            </a>
            <a href="{{ route('subscription.history') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;font-weight:600;">
                <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
            </a>
            @if(in_array($status, ['trial', 'active']))
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;font-weight:600;">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            @endif
        </div>

    </div>
</div>

@endsection
