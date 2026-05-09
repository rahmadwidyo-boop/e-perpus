<?php

use App\Http\Controllers\FineController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SchoolController as SuperAdminSchoolController;
use App\Http\Controllers\SuperAdmin\PaymentController as SuperAdminPaymentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── Publik ────────────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->middleware('throttle:5,1')->name('register.store');

// ── Verifikasi Email ──────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Halaman notifikasi "cek email kamu"
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Proses klik link verifikasi dari email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    })->middleware('signed')->name('verification.verify');

    // Kirim ulang email verifikasi
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi sudah dikirim ulang ke email Anda.');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ── School Admin ──────────────────────────────────────────────────────────────
// Route subscription TIDAK pakai middleware subscription (agar bisa diakses saat expired)
Route::middleware(['auth', 'verified', 'tenant'])->group(function () {
    Route::get('/subscription/info', [SubscriptionController::class, 'info'])->name('subscription.info');
    Route::get('/subscription/payment', [SubscriptionController::class, 'paymentForm'])->name('subscription.payment');
    Route::post('/subscription/payment', [SubscriptionController::class, 'submitPayment'])->name('subscription.submit');
    Route::get('/subscription/history', [SubscriptionController::class, 'history'])->name('subscription.history');
});

// Route perpustakaan PAKAI middleware subscription
Route::middleware(['auth', 'verified', 'tenant', 'subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Buku
    Route::resource('books', BookController::class);

    // Manajemen Siswa
    Route::resource('students', StudentController::class);
    Route::get('/students-import', [StudentController::class, 'importForm'])->name('students.import.form');
    Route::post('/students-import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students-template', [StudentController::class, 'downloadTemplate'])->name('students.template');

    // Transaksi Peminjaman
    Route::resource('loans', LoanController::class)->except(['edit', 'update']);
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    // Denda
    Route::get('/fines', [FineController::class, 'index'])->name('fines.index');

    // Laporan & Export
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// ── Super Admin ───────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schools', [SuperAdminSchoolController::class, 'index'])->name('schools.index');
    Route::patch('/schools/{school}/status', [SuperAdminSchoolController::class, 'updateStatus'])->name('schools.updateStatus');
    Route::get('/payments', [SuperAdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/approve', [SuperAdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::patch('/payments/{payment}/reject', [SuperAdminPaymentController::class, 'reject'])->name('payments.reject');
});
