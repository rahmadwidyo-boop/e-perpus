@extends('layouts.app')

@section('title', 'Ajukan Pembayaran')
@section('page-title', 'Ajukan Pembayaran')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Info Banner --}}
        <div class="alert mb-4" style="background:#e8f0fe;border:1px solid #c5d8f8;border-radius:12px;" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-info-circle-fill mt-1" style="color:#1e3a5f;"></i>
                <div>
                    <div class="fw-600 small" style="font-weight:600;color:#1e3a5f;">Informasi Pembayaran</div>
                    <div class="small text-muted">
                        Transfer ke <strong>BCA 1234567890</strong> a.n. E-Perpustakaan sebesar <strong>Rp 1.500.000</strong>,
                        lalu isi formulir di bawah ini.
                    </div>
                </div>
            </div>
        </div>

        <div class="table-card p-4">
            <h6 class="fw-700 mb-4" style="font-weight:700;color:#1e3a5f;">
                <i class="bi bi-send-fill me-2"></i>Formulir Pengajuan Pembayaran
            </h6>

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

            <form action="{{ route('subscription.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-500 small" style="font-weight:500;">
                        Nominal Transfer <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8f9fa;border:1.5px solid #dee2e6;border-right:none;">
                            <span class="text-muted small fw-600">Rp</span>
                        </span>
                        <input type="number" name="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               style="border-radius:0 10px 10px 0;border:1.5px solid #dee2e6;"
                               value="{{ old('amount', 1500000) }}"
                               min="1" required>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Nominal standar perpanjangan: Rp 1.500.000</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-500 small" style="font-weight:500;">
                        Tanggal Transfer <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="transfer_date"
                           class="form-control @error('transfer_date') is-invalid @enderror"
                           style="border-radius:10px;border:1.5px solid #dee2e6;"
                           value="{{ old('transfer_date', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" required>
                    @error('transfer_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-500 small" style="font-weight:500;">
                        Nama Bank <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8f9fa;border:1.5px solid #dee2e6;border-right:none;">
                            <i class="bi bi-bank text-muted"></i>
                        </span>
                        <input type="text" name="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               style="border-radius:0 10px 10px 0;border:1.5px solid #dee2e6;"
                               placeholder="Contoh: BCA, BNI, Mandiri, BRI"
                               value="{{ old('bank_name') }}"
                               maxlength="100" required>
                    </div>
                    @error('bank_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-500 small" style="font-weight:500;">
                        Catatan <span class="text-muted">(opsional)</span>
                    </label>
                    <textarea name="notes"
                              class="form-control @error('notes') is-invalid @enderror"
                              style="border-radius:10px;border:1.5px solid #dee2e6;"
                              rows="3"
                              placeholder="Nomor referensi transfer, nama pengirim, atau catatan lainnya..."
                              maxlength="500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn px-4 py-2" style="background:#1e3a5f;color:#fff;border-radius:10px;font-weight:600;">
                        <i class="bi bi-send-fill me-2"></i>Kirim Pengajuan
                    </button>
                    <a href="{{ route('subscription.info') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;font-weight:600;">
                        <i class="bi bi-arrow-left me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
