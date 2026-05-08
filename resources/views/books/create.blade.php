@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-700 mb-4" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-journal-plus me-2"></i>Form Tambah Buku
            </h6>

            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('books._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-save me-2"></i>Simpan Buku
                    </button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
