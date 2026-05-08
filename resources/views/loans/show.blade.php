@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="table-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-700 mb-0" style="font-weight:700;color:#1e3a5f;">
                    <i class="bi bi-receipt me-2"></i>Detail Transaksi
                </h6>
                <span class="badge badge-{{ $loan->status }} fs-6 px-3 py-2">{{ ucfirst($loan->status) }}</span>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block">Kode Transaksi</small>
                        <code class="fs-6">{{ $loan->code }}</code>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block mb-1">Siswa</small>
                        <div class="fw-600" style="font-weight:600;">{{ $loan->student->name }}</div>
                        <small class="text-muted">{{ $loan->student->nis }} | {{ $loan->student->class }} - {{ $loan->student->major }}</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block mb-1">Buku</small>
                        <div class="fw-600" style="font-weight:600;">{{ $loan->book->title }}</div>
                        <small class="text-muted">{{ $loan->book->code }} | {{ $loan->book->author }}</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block">Tanggal Pinjam</small>
                        <div class="fw-500" style="font-weight:500;">{{ $loan->loan_date->format('d F Y') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block">Batas Pengembalian</small>
                        <div class="fw-500 {{ $loan->status === 'terlambat' ? 'text-danger' : '' }}" style="font-weight:500;">
                            {{ $loan->due_date->format('d F Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded" style="background:#f8f9fa;">
                        <small class="text-muted d-block">Tanggal Dikembalikan</small>
                        <div class="fw-500" style="font-weight:500;">
                            {{ $loan->return_date ? $loan->return_date->format('d F Y') : '-' }}
                        </div>
                    </div>
                </div>

                @if($loan->status === 'terlambat' || $loan->fine > 0)
                <div class="col-12">
                    <div class="p-3 rounded border border-danger" style="background:#fff5f5;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-danger d-block">Keterlambatan</small>
                                <div class="fw-600 text-danger" style="font-weight:600;">
                                    {{ $loan->late_days }} hari
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-danger d-block">Denda</small>
                                <div class="fw-600 text-danger fs-5" style="font-weight:600;">
                                    Rp {{ number_format($loan->fine > 0 ? $loan->fine : $loan->calculated_fine, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="d-flex gap-2 mt-4">
                @if($loan->status !== 'dikembalikan')
                <form action="{{ route('loans.return', $loan) }}" method="POST"
                      onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success" style="border-radius:8px;">
                        <i class="bi bi-arrow-return-left me-2"></i>Kembalikan Buku
                    </button>
                </form>
                @endif
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
