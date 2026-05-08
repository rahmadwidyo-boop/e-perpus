@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#e8f0fe;">
                <i class="bi bi-journal-bookmark-fill" style="color:#1e3a5f;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#1e3a5f;">{{ number_format($totalBooks) }}</div>
                <div class="stat-label">Total Stok Buku</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#e8f5e9;">
                <i class="bi bi-people-fill" style="color:#2e7d32;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#2e7d32;">{{ number_format($totalStudents) }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#fff8e1;">
                <i class="bi bi-arrow-left-right" style="color:#f57f17;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#f57f17;">{{ number_format($totalLoaned) }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-white">
            <div class="icon-box" style="background:#fce4ec;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#c62828;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#c62828;">{{ number_format($totalLate) }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Chart --}}
    <div class="col-lg-8">
        <div class="table-card p-3">
            <h6 class="fw-600 mb-3" style="font-weight:600;color:#1e3a5f;">
                <i class="bi bi-bar-chart-fill me-2"></i>Statistik Peminjaman 12 Bulan Terakhir
            </h6>
            <canvas id="loanChart" height="100"></canvas>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="table-card p-3 h-100">
            <h6 class="fw-600 mb-3" style="font-weight:600;color:#1e3a5f;">
                <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('loans.create') }}" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                    <i class="bi bi-plus-circle me-2"></i>Pinjam Buku Baru
                </a>
                <a href="{{ route('books.create') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-journal-plus me-2"></i>Tambah Buku
                </a>
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-person-plus me-2"></i>Tambah Siswa
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Daftar Peminjaman Aktif --}}
    <div class="col-lg-7">
        <div class="table-card">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-600 mb-0" style="font-weight:600;color:#1e3a5f;">
                    <i class="bi bi-clock-history me-2"></i>Peminjaman Aktif
                </h6>
                <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.75rem;">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Buku</th>
                            <th>Batas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeLoans->take(8) as $loan)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-weight:500;">{{ $loan->student->name }}</div>
                                <small class="text-muted">{{ $loan->student->class }}</small>
                            <td>
                                <div style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $loan->book->title }}
                                </div>
                            </td>
                            <td>
                                <small>{{ $loan->due_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                @if($loan->status === 'terlambat')
                                    <span class="badge badge-terlambat rounded-pill">Terlambat</span>
                                @else
                                    <span class="badge badge-dipinjam rounded-pill">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Tidak ada peminjaman aktif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Daftar Keterlambatan --}}
    <div class="col-lg-5">
        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-600 mb-0" style="font-weight:600;color:#c62828;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Keterlambatan
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Terlambat</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lateLoans->take(8) as $loan)
                        <tr>
                            <td>
                                <div class="fw-500" style="font-weight:500;">{{ $loan->student->name }}</div>
                                <small class="text-muted">{{ $loan->book->title }}</small>
                            </td>
                            <td>
                                <span class="badge bg-danger">{{ $loan->late_days }} hari</span>
                            </td>
                            <td>
                                <small class="text-danger fw-500">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle fs-4 d-block mb-1 text-success"></i>
                                Tidak ada keterlambatan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('loanChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(30, 58, 95, 0.8)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: 'rgba(0,0,0,.05)' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
