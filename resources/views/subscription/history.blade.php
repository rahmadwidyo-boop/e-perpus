@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')
@section('page-title', 'Riwayat Pembayaran')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h6 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">Riwayat Pembayaran</h6>
        <p class="text-muted small mb-0">Semua pengajuan pembayaran langganan Anda</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('subscription.payment') }}" class="btn btn-sm px-3" style="background:#1e3a5f;color:#fff;border-radius:8px;font-weight:600;">
            <i class="bi bi-plus-circle me-1"></i>Ajukan Baru
        </a>
        <a href="{{ route('subscription.info') }}" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:8px;">
            <i class="bi bi-info-circle me-1"></i>Info Langganan
        </a>
    </div>
</div>

<div class="table-card">
    @if($payments->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            <div class="fw-600" style="font-weight:600;">Belum ada riwayat pembayaran</div>
            <div class="small mt-1">Ajukan pembayaran pertama Anda untuk memperpanjang langganan.</div>
            <a href="{{ route('subscription.payment') }}" class="btn btn-sm mt-3 px-4" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                <i class="bi bi-send-fill me-1"></i>Ajukan Pembayaran
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Tanggal Transfer</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $payment)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-600" style="font-weight:600;">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>{{ $payment->bank_name }}</td>
                        <td>{{ $payment->transfer_date->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusConfig = [
                                    'pending'  => ['bg' => '#fff3cd', 'color' => '#856404', 'icon' => 'bi-clock-fill',        'label' => 'Menunggu'],
                                    'approved' => ['bg' => '#d1e7dd', 'color' => '#0f5132', 'icon' => 'bi-check-circle-fill', 'label' => 'Disetujui'],
                                    'rejected' => ['bg' => '#f8d7da', 'color' => '#842029', 'icon' => 'bi-x-circle-fill',     'label' => 'Ditolak'],
                                ];
                                $sc = $statusConfig[$payment->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="badge rounded-pill px-3 py-2" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                <i class="bi {{ $sc['icon'] }} me-1"></i>{{ $sc['label'] }}
                            </span>
                        </td>
                        <td>
                            @if($payment->status === 'rejected' && $payment->rejection_reason)
                                <div class="d-flex align-items-start gap-1">
                                    <i class="bi bi-chat-left-text text-danger mt-1" style="font-size:.8rem;"></i>
                                    <small class="text-danger">{{ $payment->rejection_reason }}</small>
                                </div>
                            @elseif($payment->status === 'approved' && $payment->reviewed_at)
                                <small class="text-muted">
                                    <i class="bi bi-check2 me-1"></i>
                                    Diverifikasi {{ $payment->reviewed_at->format('d/m/Y') }}
                                </small>
                            @elseif($payment->notes)
                                <small class="text-muted">{{ Str::limit($payment->notes, 50) }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
