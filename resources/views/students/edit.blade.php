@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <h6 class="fw-700 mb-4" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Siswa
            </h6>
            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf @method('PUT')
                @include('students._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-save me-2"></i>Perbarui Siswa
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
