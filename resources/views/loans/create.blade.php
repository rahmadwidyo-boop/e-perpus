@extends('layouts.app')

@section('title', 'Pinjam Buku')
@section('page-title', 'Pinjam Buku')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    /* Sesuaikan style Select2 dengan Bootstrap */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: .875rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #212529;
        padding-left: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 .2rem rgba(30,58,95,.15);
        outline: none;
    }
    .select2-dropdown {
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,.1);
        font-size: .875rem;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1e3a5f;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1.5px solid #dee2e6;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: .875rem;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: #1e3a5f;
        outline: none;
    }
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #6c757d;
    }
    /* Placeholder style */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
</style>
@endpush

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

                    {{-- Pilih Siswa dengan Search --}}
                    <div class="col-12">
                        <label class="form-label small fw-500">Siswa <span class="text-danger">*</span></label>
                        <select name="student_id"
                                id="studentSelect"
                                class="form-select @error('student_id') is-invalid @enderror"
                                required>
                            <option value="">-- Ketik nama atau NIS siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->nis }}) - Kelas {{ $student->class }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Pilih Buku dengan Search --}}
                    <div class="col-12">
                        <label class="form-label small fw-500">Buku <span class="text-danger">*</span></label>
                        <select name="book_id"
                                id="bookSelect"
                                class="form-select @error('book_id') is-invalid @enderror"
                                required>
                            <option value="">-- Ketik judul atau kode buku --</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}"
                                    {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ $book->code }}) - Stok: {{ $book->stock }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-500">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="loan_date"
                               class="form-control form-control-sm @error('loan_date') is-invalid @enderror"
                               value="{{ old('loan_date', date('Y-m-d')) }}" required>
                        @error('loan_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-500">Batas Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="due_date"
                               class="form-control form-control-sm @error('due_date') is-invalid @enderror"
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

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // Select2 untuk Siswa
    $('#studentSelect').select2({
        placeholder: 'Ketik nama atau NIS siswa...',
        allowClear: true,
        minimumInputLength: 1,
        language: {
            inputTooShort: function () {
                return 'Ketik minimal 1 huruf untuk mencari siswa...';
            },
            noResults: function () {
                return 'Siswa tidak ditemukan';
            },
            searching: function () {
                return 'Mencari...';
            }
        },
        width: '100%',
    });

    // Select2 untuk Buku
    $('#bookSelect').select2({
        placeholder: 'Ketik judul atau kode buku...',
        allowClear: true,
        minimumInputLength: 1,
        language: {
            inputTooShort: function () {
                return 'Ketik minimal 1 huruf untuk mencari buku...';
            },
            noResults: function () {
                return 'Buku tidak ditemukan';
            },
            searching: function () {
                return 'Mencari...';
            }
        },
        width: '100%',
    });

    // Restore nilai lama jika ada validasi error
    @if(old('student_id'))
        $('#studentSelect').val('{{ old('student_id') }}').trigger('change');
    @endif
    @if(old('book_id'))
        $('#bookSelect').val('{{ old('book_id') }}').trigger('change');
    @endif
});
</script>
@endpush
