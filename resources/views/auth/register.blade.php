<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - E-Perpustakaan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 50%, #1e3a5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .register-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .register-logo {
            width: 64px; height: 64px;
            background: #1e3a5f;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }
        .btn-register {
            background: #1e3a5f;
            color: #fff;
            border: none;
            padding: .75rem;
            font-weight: 600;
            border-radius: 10px;
            transition: background .2s;
        }
        .btn-register:hover { background: #2d5a8e; color: #fff; }
        .form-control {
            border-radius: 10px;
            padding: .65rem 1rem;
            border: 1.5px solid #dee2e6;
        }
        .form-control:focus {
            border-color: #1e3a5f;
            box-shadow: 0 0 0 .2rem rgba(30,58,95,.15);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            border-right: none;
        }
        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
        .trial-banner {
            background: linear-gradient(135deg, #d1e7dd, #a8d5b5);
            border: 1px solid #a3cfbb;
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: 1.5rem;
        }
        .slug-preview {
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            border-radius: 10px;
            padding: .5rem .85rem;
            font-size: .8rem;
            color: #6c757d;
            margin-top: .35rem;
        }
        .slug-preview span {
            color: #1e3a5f;
            font-weight: 600;
        }
        .form-label {
            font-weight: 500;
            font-size: .875rem;
            color: #495057;
        }
        .section-divider {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6c757d;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: .4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <div class="register-logo">
                <i class="bi bi-book-fill text-white fs-3"></i>
            </div>
            <h4 class="fw-700 mb-1" style="color:#1e3a5f;font-weight:700;">Daftar E-Perpustakaan</h4>
            <p class="text-muted small mb-0">Buat akun perpustakaan sekolah Anda</p>
        </div>

        {{-- Trial Banner --}}
        <div class="trial-banner">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-gift-fill" style="color:#0f5132;font-size:1.2rem;"></i>
                <div>
                    <div class="fw-600 small" style="font-weight:600;color:#0f5132;">Trial 30 Hari Gratis!</div>
                    <div class="small" style="color:#155724;">Coba semua fitur tanpa biaya. Tidak perlu kartu kredit.</div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 py-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            {{-- Data Sekolah --}}
            <div class="section-divider">Data Sekolah</div>

            <div class="mb-3">
                <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building text-muted"></i></span>
                    <input type="text" name="school_name" id="schoolName"
                           class="form-control @error('school_name') is-invalid @enderror"
                           placeholder="SMA Negeri 1 Jakarta"
                           value="{{ old('school_name') }}" required>
                </div>
                @error('school_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug URL <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-link-45deg text-muted"></i></span>
                    <input type="text" name="slug" id="slugInput"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="sman1-jakarta"
                           value="{{ old('slug') }}" required
                           pattern="[a-z0-9\-]+" title="Hanya huruf kecil, angka, dan tanda hubung">
                </div>
                @error('slug')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="slug-preview" id="slugPreview">
                    <i class="bi bi-globe me-1"></i>
                    URL: eperpustakaan.id/<span id="slugDisplay">sman1-jakarta</span>
                </div>
                <div class="form-text">Hanya huruf kecil, angka, dan tanda hubung (-). Min. 3 karakter.</div>
            </div>

            {{-- Data Admin --}}
            <div class="section-divider mt-4">Data Administrator</div>

            <div class="mb-3">
                <label class="form-label">Nama Admin <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="admin_name"
                           class="form-control @error('admin_name') is-invalid @enderror"
                           placeholder="Budi Santoso"
                           value="{{ old('admin_name') }}" required>
                </div>
                @error('admin_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="admin@sekolah.sch.id"
                           value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 karakter" required minlength="8">
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill text-muted"></i></span>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100">
                <i class="bi bi-person-plus me-2"></i>Daftar & Mulai Trial Gratis
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:#1e3a5f;font-weight:600;">Login di sini</a>
            </small>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted">© {{ date('Y') }} E-Perpustakaan Sekolah</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-generate slug from school name
        const schoolNameInput = document.getElementById('schoolName');
        const slugInput = document.getElementById('slugInput');
        const slugDisplay = document.getElementById('slugDisplay');

        function generateSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .substring(0, 50);
        }

        schoolNameInput.addEventListener('input', function () {
            if (!slugInput.dataset.manualEdit) {
                const slug = generateSlug(this.value);
                slugInput.value = slug;
                slugDisplay.textContent = slug || 'nama-sekolah';
            }
        });

        slugInput.addEventListener('input', function () {
            this.dataset.manualEdit = 'true';
            // Force lowercase and valid chars
            const cleaned = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
            this.value = cleaned;
            slugDisplay.textContent = cleaned || 'nama-sekolah';
        });

        // Init slug display
        const initSlug = slugInput.value || 'sman1-jakarta';
        slugDisplay.textContent = initSlug;
    </script>
</body>
</html>
