@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small fw-500">Kode Buku <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control form-control-sm @error('code') is-invalid @enderror"
               value="{{ old('code', $book->code ?? '') }}" placeholder="Contoh: BK-001" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label small fw-500">Judul Buku <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control form-control-sm @error('title') is-invalid @enderror"
               value="{{ old('title', $book->title ?? '') }}" placeholder="Judul lengkap buku" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-500">Kategori <span class="text-danger">*</span></label>
        <input type="text" name="category" class="form-control form-control-sm @error('category') is-invalid @enderror"
               value="{{ old('category', $book->category ?? '') }}"
               placeholder="Contoh: Fiksi, Sains, Sejarah..."
               list="category-list" required>
        <datalist id="category-list">
            @foreach($categories as $cat)
                <option value="{{ $cat }}">
            @endforeach
        </datalist>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-500">Penulis <span class="text-danger">*</span></label>
        <input type="text" name="author" class="form-control form-control-sm @error('author') is-invalid @enderror"
               value="{{ old('author', $book->author ?? '') }}" placeholder="Nama penulis" required>
        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-500">Penerbit <span class="text-danger">*</span></label>
        <input type="text" name="publisher" class="form-control form-control-sm @error('publisher') is-invalid @enderror"
               value="{{ old('publisher', $book->publisher ?? '') }}" placeholder="Nama penerbit" required>
        @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-500">Tahun Terbit <span class="text-danger">*</span></label>
        <input type="number" name="publish_year" class="form-control form-control-sm @error('publish_year') is-invalid @enderror"
               value="{{ old('publish_year', $book->publish_year ?? '') }}"
               min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" required>
        @error('publish_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-500">Stok <span class="text-danger">*</span></label>
        <input type="number" name="stock" class="form-control form-control-sm @error('stock') is-invalid @enderror"
               value="{{ old('stock', $book->stock ?? 1) }}" min="0" required>
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-500">Rak/Lokasi <span class="text-danger">*</span></label>
        <input type="text" name="shelf" class="form-control form-control-sm @error('shelf') is-invalid @enderror"
               value="{{ old('shelf', $book->shelf ?? '') }}" placeholder="Contoh: Rak A-1" required>
        @error('shelf')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-500">Cover Buku <small class="text-muted">(opsional, maks 2MB)</small></label>
        <input type="file" name="cover" class="form-control form-control-sm @error('cover') is-invalid @enderror"
               accept="image/jpg,image/jpeg,image/png,image/webp">
        @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(!empty($book->cover))
            <div class="mt-2">
                <img src="{{ Storage::url($book->cover) }}" alt="cover" style="height:60px;border-radius:6px;">
                <small class="text-muted d-block">Cover saat ini</small>
            </div>
        @endif
    </div>
</div>
