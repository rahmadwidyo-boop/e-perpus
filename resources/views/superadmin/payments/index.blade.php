@extends('layouts.superadmin')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h6 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">Pembayaran Menunggu Verifikasi</h6>
        <p class="text-muted small mb-0">{{ $payments->count() }} pengajuan perlu ditinjau</p>
    </div>
</div>

<div class="table-card">
    @if($payments->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-check-circle fs-1 d-block mb-2 text-success"></i>
            <div class="fw-600" style="font-weight:600;">Tidak ada pembayaran yang menunggu</div>
            <div class="small mt-1">Semua pengajuan pembayaran sudah diproses.</div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sekolah</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Tanggal Transfer</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $payment)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-600" style="font-weight:600;">{{ $payment->school->name }}</div>
                            <small class="text-muted">{{ $payment->school->slug }}</small>
                        </td>
                        <td>
                            <div class="fw-600" style="font-weight:600;color:#1e3a5f;">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>{{ $payment->bank_name }}</td>
                        <td>{{ $payment->transfer_date->format('d/m/Y') }}</td>
                        <td>
                            <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($payment->notes)
                                <small class="text-muted">{{ Str::limit($payment->notes, 40) }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                {{-- Approve --}}
                                <form action="{{ route('superadmin.payments.approve', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm px-3 py-1"
                                            style="background:#d1e7dd;color:#0f5132;border:none;border-radius:6px;font-size:.8rem;font-weight:600;"
                                            onclick="return confirm('Setujui pembayaran dari {{ addslashes($payment->school->name) }}?\nLangganan akan aktif 1 tahun.')">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </button>
                                </form>

                                {{-- Reject Button (trigger modal) --}}
                                <button type="button" class="btn btn-sm px-3 py-1"
                                        style="background:#f8d7da;color:#842029;border:none;border-radius:6px;font-size:.8rem;font-weight:600;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $payment->id }}">
                                    <i class="bi bi-x-circle me-1"></i>Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Reject Modal --}}
                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius:16px;border:none;">
                                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;">
                                    <h6 class="modal-title fw-700" style="font-weight:700;color:#842029;">
                                        <i class="bi bi-x-circle-fill me-2"></i>Tolak Pembayaran
                                    </h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('superadmin.payments.reject', $payment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="alert" style="background:#f8d7da;border:none;border-radius:10px;" role="alert">
                                            <div class="small">
                                                <strong>{{ $payment->school->name }}</strong> —
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                ({{ $payment->bank_name }}, {{ $payment->transfer_date->format('d/m/Y') }})
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500 small" style="font-weight:500;">
                                                Alasan Penolakan <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="rejection_reason"
                                                      class="form-control"
                                                      style="border-radius:10px;border:1.5px solid #dee2e6;"
                                                      rows="3"
                                                      placeholder="Contoh: Nominal tidak sesuai, bukti transfer tidak valid, dll."
                                                      maxlength="1000" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary px-4" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-sm px-4"
                                                style="background:#842029;color:#fff;border:none;border-radius:8px;font-weight:600;">
                                            <i class="bi bi-x-circle me-1"></i>Konfirmasi Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
