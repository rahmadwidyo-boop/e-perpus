@extends('layouts.app')

@section('title', 'Denda')
@section('page-title', 'Manajemen Denda')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="stat-card bg-white h-100">
            <div class="icon-box" style="background:#fce4ec;">
                <i class="bi bi-hourglass-split" style="color:#c62828;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#c62828;font-size:1.2rem;">
                    Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                </div>
                <div class="stat-label">Denda Belum Dibayar ({{ $countUnpaid }} siswa)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card bg-white h-100">
            <div class="icon-box" style="background:#e8f5e9;">
                <i class="bi bi-cash-stack" style="color:#2e7d32;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#2e7d32;font-size:1.2rem;">
                    Rp {{ number_format($totalPaid, 0, ',', '.') }}
                </div>
                <div class="stat-label">Denda Sudah Dibayar ({{ $countPaid }} transaksi)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="stat-card bg-white h-100">
            <div class="icon-box" style="background:#e8f0fe;">
                <i class="bi bi-wallet2" style="color:#1e3a5f;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#1e3a5f;font-size:1.2rem;">
                    Rp {{ number_format($totalAll, 0, ',', '.') }}
                </div>
                <div class="stat-label">Total Seluruh Denda</div>
            </div>
        </div>
    </div>
</div>

{{-- Denda Belum Dibayar --}}
<div class="table-card mb-4">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-700 mb-0" style="font-weight:700;color:#c62828;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Denda Belum Dibayar
        </h6>
        <span class="badge" style="background:#fce4ec;color:#c62828;">{{ $countUnpaid }} siswa</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th class="text-center">Terlambat</th>
                    <th class="text-end">Denda</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unpaidFines as $loan)
                <tr>
                    <td>
                        <div class="fw-500" style="font-weight:500;">{{ $loan->student->name }}</div>
                        <small class="text-muted">{{ $loan->student->nis }}</small>
                    </td>
                    <td>{{ $loan->student->class }}</td>
                    <td>
                        <div style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $loan->book->title }}
                        </div>
                        <small class="text-muted">{{ $loan->book->code }}</small>
                    </td>
                    <td><small>{{ $loan->loan_date->format('d/m/Y') }}</small></td>
                    <td>
                        <small class="text-danger fw-500" style="font-weight:500;">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-danger">{{ $loan->late_days_count }} hari</span>
                    </td>
                    <td class="text-end">
                        <span class="fw-700" style="font-weight:700;color:#c62828;">
                            Rp {{ number_format($loan->current_fine, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('loans.return', $loan) }}" method="POST"
                              onsubmit="return confirm('Kembalikan buku dan catat denda Rp {{ number_format($loan->current_fine, 0, ',', '.') }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success" style="border-radius:6px;font-size:.75rem;">
                                <i class="bi bi-arrow-return-left me-1"></i>Kembalikan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-3 d-block mb-2 text-success"></i>
                        Tidak ada denda yang belum dibayar
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($unpaidFines->count() > 0)
            <tfoot>
                <tr style="background:#fce4ec;">
                    <td colspan="6" class="text-end fw-600 py-2" style="font-weight:600;">Total Denda Belum Dibayar:</td>
                    <td class="text-end fw-700 py-2" style="font-weight:700;color:#c62828;">
                        Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- Denda Sudah Dibayar --}}
<div class="table-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-700 mb-0" style="font-weight:700;color:#2e7d32;">
            <i class="bi bi-check-circle-fill me-2"></i>Riwayat Denda Sudah Dibayar
        </h6>
        <span class="badge" style="background:#e8f5e9;color:#2e7d32;">{{ $countPaid }} transaksi</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th class="text-end">Denda Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paidFines as $loan)
                <tr>
                    <td>
                        <div class="fw-500" style="font-weight:500;">{{ $loan->student->name }}</div>
                        <small class="text-muted">{{ $loan->student->nis }}</small>
                    </td>
                    <td>{{ $loan->student->class }}</td>
                    <td>
                        <div style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $loan->book->title }}
                        </div>
                    </td>
                    <td><small>{{ $loan->loan_date->format('d/m/Y') }}</small></td>
                    <td><small>{{ $loan->return_date->format('d/m/Y') }}</small></td>
                    <td class="text-end">
                        <span class="fw-700" style="font-weight:700;color:#2e7d32;">
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada riwayat denda
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($paidFines->count() > 0)
            <tfoot>
                <tr style="background:#e8f5e9;">
                    <td colspan="5" class="text-end fw-600 py-2" style="font-weight:600;">Total Denda Terkumpul:</td>
                    <td class="text-end fw-700 py-2" style="font-weight:700;color:#2e7d32;">
                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
