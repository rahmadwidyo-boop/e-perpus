@extends('layouts.app')

@section('title', 'Transaksi Peminjaman')
@section('page-title', 'Transaksi Peminjaman')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700;">Daftar Peminjaman</h5>
        <small class="text-muted">Total: {{ $loans->total() }} transaksi</small>
    </div>
    <a href="{{ route('loans.create') }}" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
        <i class="bi bi-plus-lg me-1"></i>Pinjam Buku
    </a>
</div>

<div class="table-card p-3 mb-3">
    <form action="{{ route('loans.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-1">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Kode, nama siswa, atau judul buku..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Siswa</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td><code style="font-size:.75rem;">{{ $loan->code }}</code></td>
                    <td>
                        <div class="fw-500" style="font-weight:500;">{{ $loan->student->name }}</div>
                        <small class="text-muted">{{ $loan->student->class }}</small>
                    </td>
                    <td>
                        <div style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $loan->book->title }}
                        </div>
                    </td>
                    <td><small>{{ $loan->loan_date->format('d/m/Y') }}</small></td>
                    <td>
                        <small class="{{ $loan->status === 'terlambat' ? 'text-danger fw-500' : '' }}">
                            {{ $loan->due_date->format('d/m/Y') }}
                        </small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $loan->status }} rounded-pill">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>
                    <td>
                        @if($loan->fine > 0)
                            <small class="text-danger">Rp {{ number_format($loan->fine, 0, ',', '.') }}</small>
                        @elseif($loan->status === 'terlambat')
                            <small class="text-danger">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</small>
                        @else
                            <small class="text-muted">-</small>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-xs btn-outline-info" title="Detail"
                               style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($loan->status !== 'dikembalikan')
                            <form action="{{ route('loans.return', $loan) }}" method="POST"
                                  onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-outline-success" title="Kembalikan"
                                        style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                    <i class="bi bi-arrow-return-left"></i>
                                </button>
                            </form>
                            @endif
                            @if($loan->status === 'dikembalikan')
                            <form action="{{ route('loans.destroy', $loan) }}" method="POST"
                                  onsubmit="return confirm('Hapus data transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus"
                                        style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada transaksi peminjaman
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($loans->hasPages())
    <div class="p-3 border-top">
        {{ $loans->links() }}
    </div>
    @endif
</div>
@endsection
