@extends('layouts.app')

@section('title', 'Pinjam Buku')
@section('page-title', 'Pinjam Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="table-card p-4">
            <h6 class="fw-700 mb-4" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-arrow-left-right me-2"></i>Form Peminjaman Buku
            </h6>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('loans.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-500">Siswa <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select form-select-sm @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->nis }}) - Kelas {{ $student->class }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-500">Buku <span class="text-danger">*</span></label>
                        <select name="book_id" class="form-select form-select-sm @error('book_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ $book->code }}) - Stok: {{ $book->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-500">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="loan_date" class="form-control form-control-sm @error('loan_date') is-invalid @enderror"
                               value="{{ old('loan_date', date('Y-m-d')) }}" required>
                        @error('loan_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-500">Batas Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control form-control-sm @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="alert alert-info mt-3 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <small>Denda keterlambatan: <strong>Rp 1.000 per hari</strong></small>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-save me-2"></i>Simpan Peminjaman
                    </button>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
