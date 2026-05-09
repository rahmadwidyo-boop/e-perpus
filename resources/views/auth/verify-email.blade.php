<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - E-Perpustakaan</title>
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
        .verify-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            text-align: center;
        }
        .icon-box {
            width: 80px; height: 80px;
            background: #e8f0fe;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .btn-primary-custom {
            background: #1e3a5f;
            color: #fff;
            border: none;
            padding: .75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: background .2s;
            width: 100%;
        }
        .btn-primary-custom:hover { background: #2d5a8e; color: #fff; }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="icon-box">
            <i class="bi bi-envelope-check-fill" style="font-size:2rem;color:#1e3a5f;"></i>
        </div>

        <h4 class="fw-700 mb-2" style="font-weight:700;color:#1e3a5f;">Verifikasi Email Anda</h4>
        <p class="text-muted mb-4">
            Kami telah mengirim link verifikasi ke <strong>{{ Auth::user()->email }}</strong>.
            Silakan cek inbox atau folder spam Anda.
        </p>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 text-start">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="alert alert-info d-flex align-items-start gap-2 mb-4 text-start" style="background:#e8f0fe;border:none;border-radius:10px;">
            <i class="bi bi-info-circle-fill mt-1" style="color:#1e3a5f;"></i>
            <div class="small">
                Tidak menerima email? Cek folder <strong>Spam</strong> atau klik tombol di bawah untuk kirim ulang.
            </div>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary-custom">
                <i class="bi bi-send-fill me-2"></i>Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100" style="border-radius:10px;">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>

        <div class="mt-4">
            <small class="text-muted">© {{ date('Y') }} E-Perpustakaan Sekolah</small>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
