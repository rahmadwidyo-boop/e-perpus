@extends('layouts.app')

@section('title', 'Data Buku')
@section('page-title', 'Manajemen Buku')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-700 mb-0" style="font-weight:700;">Data Buku</h5>
        <small class="text-muted">Total: {{ $books->total() }} buku</small>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-sm" style="background:#1e3a5f;color:#fff;border-radius:8px;">
        <i class="bi bi-plus-lg me-1"></i>Tambah Buku
    </a>
</div>

{{-- Filter --}}
<div class="table-card p-3 mb-3">
    <form action="{{ route('books.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-1">Cari Buku</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Judul, kode, atau penulis..."
                       value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Kategori</label>
            <select name="category" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Rak</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $i => $book)
                <tr>
                    <td>{{ $books->firstItem() + $i }}</td>
                    <td><code>{{ $book->code }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($book->cover)
                                <img src="{{ Storage::url($book->cover) }}" alt="cover"
                                     style="width:32px;height:40px;object-fit:cover;border-radius:4px;">
                            @else
                                <div style="width:32px;height:40px;background:#e9ecef;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-book text-muted" style="font-size:.7rem;"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-500" style="font-weight:500;">{{ $book->title }}</div>
                                <small class="text-muted">{{ $book->publisher }}, {{ $book->publish_year }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $book->category }}</span></td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->shelf }}</td>
                    <td class="text-center">
                        <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $book->stock }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-xs btn-outline-info" title="Detail"
                               style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-xs btn-outline-warning" title="Edit"
                               style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST"
                                  onsubmit="return confirm('Hapus buku ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger" title="Hapus"
                                        style="padding:.2rem .5rem;font-size:.75rem;border-radius:6px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                        Belum ada data buku
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($books->hasPages())
    <div class="p-3 border-top">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
