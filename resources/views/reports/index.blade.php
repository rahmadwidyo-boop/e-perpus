@extends('layouts.app')

@section('title', 'Laporan & Export')
@section('page-title', 'Laporan & Export Excel')

@section('content')
<div class="row g-3">
    {{-- Filter Panel --}}
    <div class="col-lg-3">
        <div class="table-card p-3">
            <h6 class="fw-700 mb-3" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-funnel-fill me-2"></i>Filter Laporan
            </h6>
            <form action="{{ route('reports.index') }}" method="GET" id="filterForm">
                <div class="mb-3">
                    <label class="form-label small fw-500">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ ($filters['status'] ?? '') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="terlambat" {{ ($filters['status'] ?? '') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="dikembalikan" {{ ($filters['status'] ?? '') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-500">Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ ($filters['month'] ?? '') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-500">Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label small fw-500">Rentang Tanggal</label>
                    <input type="date" name="start_date" class="form-control form-control-sm mb-2"
                           value="{{ $filters['start_date'] ?? '' }}" placeholder="Dari">
                    <input type="date" name="end_date" class="form-control form-control-sm"
                           value="{{ $filters['end_date'] ?? '' }}" placeholder="Sampai">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-search me-1"></i>Tampilkan
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                        Reset
                    </a>
                </div>
            </form>

            <hr>

            {{-- Export Button --}}
            <a href="{{ route('reports.export', request()->query()) }}"
               class="btn btn-sm btn-success w-100" style="border-radius:8px;">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel (.xlsx)
            </a>
            <small class="text-muted d-block text-center mt-1">Export sesuai filter aktif</small>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="col-lg-9">
        {{-- Ringkasan --}}
        <div class="row g-3 mb-3">
            <div class="col-4">
                <div class="table-card p-3 text-center">
                    <div class="small text-muted mb-1">Total Transaksi</div>
                    <div class="fw-700 fs-5" style="font-weight:700;color:#1e3a5f;">{{ number_format($totalTransactions) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="table-card p-3 text-center">
                    <div class="small text-muted mb-1">Sudah Dikembalikan</div>
                    <div class="fw-700 fs-5" style="font-weight:700;color:#2e7d32;">{{ number_format($totalReturned) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="table-card p-3 text-center">
                    <div class="small text-muted mb-1">Total Denda Terkumpul</div>
                    <div class="fw-700" style="font-weight:700;color:#c62828;font-size:1rem;">
                        Rp {{ number_format($totalFine, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-700 mb-0" style="font-weight:700;color:#1e3a5f;">
                    <i class="bi bi-table me-2"></i>Data Peminjaman
                </h6>
                <small class="text-muted">{{ $loans->total() }} data ditemukan</small>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas</th>
                            <th>Dikembalikan</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <td><code style="font-size:.7rem;">{{ Str::limit($loan->code, 15) }}</code></td>
                            <td>
                                <div style="font-weight:500;">{{ $loan->student->name }}</div>
                                <small class="text-muted">{{ $loan->student->class }}</small>
                            </td>
                            <td>
                                <div style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $loan->book->title }}
                                </div>
                            </td>
                            <td><small>{{ $loan->loan_date->format('d/m/Y') }}</small></td>
                            <td><small>{{ $loan->due_date->format('d/m/Y') }}</small></td>
                            <td><small>{{ $loan->return_date?->format('d/m/Y') ?? '-' }}</small></td>
                            <td>
                                <span class="badge badge-{{ $loan->status }} rounded-pill">{{ ucfirst($loan->status) }}</span>
                            </td>
                            <td>
                                @if($loan->fine > 0)
                                    <small class="text-danger">Rp {{ number_format($loan->fine, 0, ',', '.') }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Tidak ada data dengan filter ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($loans->total() > 0 && $totalFine > 0)
                    <tfoot>
                        <tr style="background:#fff8e1;">
                            <td colspan="7" class="text-end fw-600 py-2 px-3" style="font-weight:600;font-size:.85rem;">
                                <i class="bi bi-cash-stack me-1 text-warning"></i>
                                Total Denda (halaman ini):
                            </td>
                            <td class="fw-700 py-2 px-3" style="font-weight:700;color:#c62828;font-size:.85rem;">
                                Rp {{ number_format($loans->sum('fine'), 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr style="background:#fce4ec;">
                            <td colspan="7" class="text-end fw-600 py-2 px-3" style="font-weight:600;font-size:.85rem;">
                                <i class="bi bi-cash-coin me-1 text-danger"></i>
                                Total Denda Keseluruhan (semua filter):
                            </td>
                            <td class="fw-700 py-2 px-3" style="font-weight:700;color:#c62828;font-size:.9rem;">
                                Rp {{ number_format($totalFine, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($loans->hasPages())
            <div class="p-3 border-top">
                {{ $loans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
