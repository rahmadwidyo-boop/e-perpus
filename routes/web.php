<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (harus login)
Route::middleware('auth')->group(function () {

    // Dashboard
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

    // Laporan & Export
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});
