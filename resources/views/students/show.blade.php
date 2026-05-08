@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="table-card p-4 text-center">
            <div style="width:80px;height:80px;background:#e8f0fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                <i class="bi bi-person-fill fs-2" style="color:#1e3a5f;"></i>
            </div>
            <h5 class="fw-700" style="font-weight:700;">{{ $student->name }}</h5>
            <p class="text-muted mb-1">{{ $student->class }}</p>
            <code>{{ $student->nis }}</code>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="table-card p-4 mb-3">
            <h6 class="fw-700 mb-3" style="font-weight:700;color:#1e3a5f;">Informasi Siswa</h6>
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted" width="140">NIS</td><td><code>{{ $student->nis }}</code></td></tr>
                <tr><td class="text-muted">Kelas</td><td>{{ $student->class }}</td></tr>
                <tr><td class="text-muted">No. HP</td><td>{{ $student->phone ?? '-' }}</td></tr>
                <tr><td class="text-muted">Alamat</td><td>{{ $student->address ?? '-' }}</td></tr>
                <tr><td class="text-muted">Terdaftar</td><td>{{ $student->created_at->format('d F Y') }}</td></tr>
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
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->loans->sortByDesc('created_at') as $loan)
                        <tr>
                            <td>{{ $loan->book->title ?? '-' }}</td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td><span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                            <td>
                                @if($loan->fine > 0)
                                    <small class="text-danger">Rp {{ number_format($loan->fine, 0, ',', '.') }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum pernah meminjam</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
