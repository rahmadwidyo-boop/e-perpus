@extends('layouts.app')

@section('title', 'Import Siswa')
@section('page-title', 'Import Data Siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Upload Form --}}
        <div class="table-card p-4 mb-3">
            <h6 class="fw-700 mb-1" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-file-earmark-arrow-up me-2"></i>Upload File Excel
            </h6>
            <p class="text-muted small mb-4">Upload file Excel berisi data siswa. Format yang didukung: <strong>.xlsx, .xls, .csv</strong></p>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <div class="border-2 border-dashed rounded-3 p-4 text-center" id="dropZone"
                         style="border: 2px dashed #dee2e6; cursor:pointer; transition: all .2s;"
                         onclick="document.getElementById('fileInput').click()">
                        <i class="bi bi-cloud-arrow-up fs-2 text-muted d-block mb-2"></i>
                        <div class="fw-500" style="font-weight:500;" id="dropText">
                            Klik atau drag & drop file di sini
                        </div>
                        <small class="text-muted">Maksimal 5MB — .xlsx, .xls, .csv</small>
                        <input type="file" name="file" id="fileInput" class="d-none"
                               accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn" style="background:#1e3a5f;color:#fff;border-radius:8px;">
                        <i class="bi bi-upload me-2"></i>Import Sekarang
                    </button>
                    <a href="{{ route('students.template') }}" class="btn btn-outline-success" style="border-radius:8px;">
                        <i class="bi bi-download me-2"></i>Download Template
                    </a>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Panduan Format --}}
        <div class="table-card p-4">
            <h6 class="fw-700 mb-3" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-info-circle me-2"></i>Panduan Format File
            </h6>

            <p class="small text-muted mb-3">Baris pertama harus berisi <strong>header kolom</strong> seperti berikut:</p>

            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered" style="font-size:.8rem;">
                    <thead class="table-dark">
                        <tr>
                            <th>nis</th>
                            <th>nama</th>
                            <th>kelas</th>
                            <th>no_hp</th>
                            <th>alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024001</td>
                            <td>Ahmad Fauzi</td>
                            <td>X 1</td>
                            <td>081234567890</td>
                            <td>Jl. Merdeka No. 1</td>
                        </tr>
                        <tr>
                            <td>2024002</td>
                            <td>Siti Rahayu</td>
                            <td>XI 3</td>
                            <td><em class="text-muted">kosong</em></td>
                            <td><em class="text-muted">kosong</em></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-warning py-2 small">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <strong>Kelas yang valid:</strong>
                X 1 – X 7 &nbsp;|&nbsp; XI 1 – XI 7 &nbsp;|&nbsp; XII 1 – XII 7
            </div>

            <ul class="small text-muted mb-0 ps-3">
                <li>Kolom <strong>nis</strong>, <strong>nama</strong>, dan <strong>kelas</strong> wajib diisi</li>
                <li>Kolom <strong>no_hp</strong> dan <strong>alamat</strong> boleh kosong</li>
                <li>Siswa dengan NIS yang sudah ada akan <strong>dilewati</strong> (tidak duplikat)</li>
                <li>Format kelas: gunakan spasi antara tingkat dan nomor, contoh <code>X 1</code>, <code>XI 3</code>, <code>XII 7</code></li>
                <li>Format tanpa spasi seperti <code>X1</code>, <code>XI3</code> juga diterima otomatis</li>
            </ul>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const fileInput = document.getElementById('fileInput');
const dropText  = document.getElementById('dropText');
const dropZone  = document.getElementById('dropZone');

fileInput.addEventListener('change', function () {
    if (this.files.length > 0) {
        dropText.textContent = this.files[0].name;
        dropZone.style.borderColor = '#1e3a5f';
        dropZone.style.background  = '#f0f4ff';
    }
});

// Drag & drop
dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.style.borderColor = '#1e3a5f';
    dropZone.style.background  = '#f0f4ff';
});
dropZone.addEventListener('dragleave', () => {
    dropZone.style.borderColor = '#dee2e6';
    dropZone.style.background  = '';
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        dropText.textContent = file.name;
        dropZone.style.borderColor = '#1e3a5f';
        dropZone.style.background  = '#f0f4ff';
    }
});
</script>
@endpush
