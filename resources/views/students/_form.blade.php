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
        <label class="form-label small fw-500">NIS <span class="text-danger">*</span></label>
        <input type="text" name="nis" class="form-control form-control-sm @error('nis') is-invalid @enderror"
               value="{{ old('nis', $student->nis ?? '') }}" placeholder="Nomor Induk Siswa" required>
        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label small fw-500">Nama Siswa <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
               value="{{ old('name', $student->name ?? '') }}" placeholder="Nama lengkap siswa" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-500">Kelas <span class="text-danger">*</span></label>
        <select name="class" class="form-select form-select-sm @error('class') is-invalid @enderror" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach([
                'X'   => ['X 1','X 2','X 3','X 4','X 5','X 6','X 7'],
                'XI'  => ['XI 1','XI 2','XI 3','XI 4','XI 5','XI 6','XI 7'],
                'XII' => ['XII 1','XII 2','XII 3','XII 4','XII 5','XII 6','XII 7'],
            ] as $tingkat => $kelasList)
                <optgroup label="Kelas {{ $tingkat }}">
                    @foreach($kelasList as $k)
                        <option value="{{ $k }}" {{ old('class', $student->class ?? '') == $k ? 'selected' : '' }}>
                            {{ $k }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-500">Nomor HP <small class="text-muted">(opsional)</small></label>
        <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror"
               value="{{ old('phone', $student->phone ?? '') }}" placeholder="08xxxxxxxxxx">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-12">
        <label class="form-label small fw-500">Alamat <small class="text-muted">(opsional)</small></label>
        <textarea name="address" class="form-control form-control-sm @error('address') is-invalid @enderror"
                  rows="2" placeholder="Alamat lengkap siswa">{{ old('address', $student->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
