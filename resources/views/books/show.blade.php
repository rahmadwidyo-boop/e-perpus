@extends('layouts.app')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="table-card p-4 text-center">
            @if($book->cover)
                <img src="{{ Storage::url($book->cover) }}" alt="cover"
                     style="max-height:200px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,.15);">
            @else
                <div style="height:200px;background:#f0f4f8;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-book fs-1 text-muted"></i>
                </div>
            @endif
            <h5 class="mt-3 fw-700" style="font-weight:700;">{{ $book->title }}</h5>
            <span class="badge bg-primary">{{ $book->category }}</span>
            <div class="mt-3">
                <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                    Stok: {{ $book->stock }}
                </span>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="table-card p-4 mb-3">
            <h6 class="fw-700 mb-3" style="font-weight:700;color:#1e3a5f;">Informasi Buku</h6>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted" width="140">Kode Buku</td><td><code>{{ $book->code }}</code></td></tr>
                <tr><td class="text-muted">Penulis</td><td>{{ $book->author }}</td></tr>
                <tr><td class="text-muted">Penerbit</td><td>{{ $book->publisher }}</td></tr>
                <tr><td class="text-muted">Tahun Terbit</td><td>{{ $book->publish_year }}</td></tr>
                <tr><td class="text-muted">Rak/Lokasi</td><td>{{ $book->shelf }}</td></tr>
                <tr><td class="text-muted">Ditambahkan</td><td>{{ $book->created_at->format('d F Y') }}</td></tr>
            </table>
        </div>

        <div class="table-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-700 mb-0" style="font-weight:700;color:#1e3a5f;">Riwayat Peminjaman</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($book->loans->sortByDesc('created_at')->take(10) as $loan)
                        <tr>
                            <td>{{ $loan->student->name ?? '-' }}</td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum pernah dipinjam</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
