@extends('layouts.app')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-700 mb-4" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Buku
            </h6>

            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('books._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-save me-2"></i>Perbarui Buku
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
