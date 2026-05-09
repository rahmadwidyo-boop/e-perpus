# Implementation Plan: SaaS Multi-Tenant E-Perpustakaan

## Overview

Implementasi upgrade aplikasi e-perpustakaan single-tenant menjadi platform SaaS multi-tenant menggunakan Laravel 12, PHP 8.2, dan MySQL. Pendekatan single-database multi-tenancy dengan kolom `school_id` sebagai discriminator. Migrasi dilakukan secara bertahap untuk menjaga keamanan data existing.

## Tasks

- [x] 1. Buat tabel baru dan modifikasi schema database (migrations bertahap)
  - [x] 1.1 Buat migration untuk tabel `schools` dan `payments`
    - Buat file migration `create_schools_table.php` dengan kolom: `id`, `name`, `slug` (unique), `admin_name`, `subscription_status` (enum: trial/active/expired/suspended, default trial), `trial_ends_at`, `subscription_ends_at`, `timestamps`
    - Buat file migration `create_payments_table.php` dengan kolom: `id`, `school_id` (FK ke schools), `amount`, `transfer_date`, `bank_name`, `notes`, `status` (enum: pending/approved/rejected, default pending), `rejection_reason`, `reviewed_at`, `reviewed_by` (FK ke users nullable), `timestamps`
    - _Requirements: 2.1, 5.3_

  - [x] 1.2 Buat migration untuk menambah kolom `role` dan `school_id` ke tabel `users`
    - Tambah kolom `role` (enum: super_admin/school_admin, default school_admin) setelah kolom `email`
    - Tambah kolom `school_id` (nullable, FK ke schools ON DELETE SET NULL) setelah kolom `role`
    - _Requirements: 7.1, 7.2_

  - [x] 1.3 Buat migration untuk menambah kolom `school_id` ke tabel `books`, `students`, `loans` (nullable dulu)
    - Tambah kolom `school_id` (nullable, FK ke schools ON DELETE CASCADE) pada tabel `books` setelah kolom `id`
    - Tambah kolom `school_id` (nullable, FK ke schools ON DELETE CASCADE) pada tabel `students` setelah kolom `id`
    - Tambah kolom `school_id` (nullable, FK ke schools ON DELETE CASCADE) pada tabel `loans` setelah kolom `id`
    - _Requirements: 3.1, 9.1_

  - [x] 1.4 Buat migration untuk mengubah constraint UNIQUE menjadi composite unique per `school_id`
    - Drop unique constraint `books.code`, tambah unique composite `(school_id, code)`
    - Drop unique constraint `students.nis`, tambah unique composite `(school_id, nis)`
    - Drop unique constraint `loans.code`, tambah unique composite `(school_id, code)`
    - _Requirements: 3.1, 9.4_

- [x] 2. Buat Model baru dan modifikasi Model existing
  - [x] 2.1 Buat `app/Models/Scopes/TenantScope.php`
    - Implementasi `Scope` interface dengan method `apply(Builder $builder, Model $model)`
    - Baca `current_school_id` dari `app()->make('current_school_id')` jika sudah di-bind
    - Jika `school_id` tidak null, tambahkan `WHERE {table}.school_id = ?`
    - Jika null (Super Admin), tidak tambahkan filter apapun
    - _Requirements: 3.2, 8.5_

  - [ ]* 2.2 Tulis property test untuk TenantScope (Property 6)
    - **Property 6: Isolasi Data Antar Tenant (TenantScope)**
    - **Validates: Requirements 3.2, 3.3, 8.5**
    - Buat `tests/Feature/Tenancy/TenantScopePropertyTest.php` menggunakan Eris
    - Generate dua sekolah dengan jumlah buku acak, verifikasi query hanya mengembalikan data sekolah aktif

  - [x] 2.3 Buat `app/Models/School.php`
    - Fillable: `name`, `slug`, `admin_name`, `subscription_status`, `trial_ends_at`, `subscription_ends_at`
    - Casts: `trial_ends_at` dan `subscription_ends_at` sebagai `datetime`
    - Relasi: `users()`, `books()`, `students()`, `loans()`, `payments()` (HasMany)
    - Method `isActive(): bool` — return true jika status `trial` atau `active`
    - Method `isExpired(): bool` — return true jika status `expired` atau tanggal sudah lewat
    - Method `daysRemaining(): int` — hitung sisa hari dari `trial_ends_at` atau `subscription_ends_at`
    - _Requirements: 2.1, 4.3_

  - [ ]* 2.4 Tulis property test untuk School model (Property 4, 5)
    - **Property 4: Round-Trip Data School**
    - **Property 5: Deteksi Kedaluwarsa Langganan**
    - **Validates: Requirements 2.1, 2.3, 2.4**
    - Buat `tests/Unit/SchoolModelPropertyTest.php` menggunakan Eris
    - Verifikasi round-trip data dan logika `isExpired()` untuk berbagai tanggal

  - [x] 2.5 Buat `app/Models/Payment.php`
    - Fillable: `school_id`, `amount`, `transfer_date`, `bank_name`, `notes`, `status`, `rejection_reason`, `reviewed_at`, `reviewed_by`
    - Casts: `transfer_date` sebagai `date`, `reviewed_at` sebagai `datetime`
    - Relasi: `school(): BelongsTo`, `reviewer(): BelongsTo` (ke User)
    - _Requirements: 5.3_

  - [x] 2.6 Modifikasi `app/Models/User.php`
    - Tambah `role` dan `school_id` ke `$fillable`
    - Tambah relasi `school(): BelongsTo`
    - Tambah helper `isSuperAdmin(): bool` dan `isSchoolAdmin(): bool`
    - _Requirements: 7.1, 7.2, 7.5_

  - [x] 2.7 Modifikasi `app/Models/Book.php`, `Student.php`, `Loan.php`
    - Tambah `school_id` ke `$fillable` pada ketiga model
    - Tambah `static::addGlobalScope(new TenantScope())` di method `booted()` pada ketiga model
    - Tambah relasi `school(): BelongsTo` pada ketiga model
    - _Requirements: 3.1, 3.2, 3.5_

  - [ ]* 2.8 Tulis unit test untuk TenantScope (Property 7, 8)
    - **Property 7: Proteksi Akses Cross-Tenant (404)**
    - **Property 8: Auto-Fill school_id Saat Membuat Data**
    - **Validates: Requirements 3.4, 3.5**
    - Buat `tests/Feature/Tenancy/TenantScopeTest.php`
    - Test akses cross-tenant menghasilkan 404, test auto-fill school_id saat create

- [x] 3. Checkpoint — Pastikan semua migration dapat dijalankan tanpa error
  - Jalankan `php artisan migrate` dan verifikasi semua tabel terbentuk dengan benar
  - Pastikan tidak ada data existing yang hilang

- [-] 4. Buat Seeders untuk data existing dan akun sistem
  - [x] 4.1 Buat `database/seeders/DefaultSchoolSeeder.php`
    - Buat satu record School default (nama: "Sekolah Default", slug: "default", subscription_status: active)
    - Update semua record existing di `books`, `students`, `loans` agar `school_id` = ID sekolah default
    - Setelah backfill, ubah kolom `school_id` pada ketiga tabel menjadi NOT NULL
    - _Requirements: 9.1, 9.2, 9.4_

  - [x] 4.2 Buat `database/seeders/SuperAdminSeeder.php`
    - Buat akun `super_admin` baru (email: superadmin@eperpus.com, password: configurable)
    - Update akun admin existing: set `role = school_admin`, set `school_id` = ID sekolah default
    - _Requirements: 9.3_

  - [ ] 4.3 Buat `database/factories/SchoolFactory.php` dan `PaymentFactory.php`
    - `SchoolFactory`: generate data sekolah dengan status acak, trial_ends_at, subscription_ends_at
    - `PaymentFactory`: generate data pembayaran dengan status acak
    - Modifikasi `UserFactory` untuk support kolom `role` dan `school_id`
    - _Requirements: 9.2_

  - [x] 4.4 Update `database/seeders/DatabaseSeeder.php`
    - Panggil `DefaultSchoolSeeder` dan `SuperAdminSeeder` dalam urutan yang benar
    - _Requirements: 9.2, 9.3_

  - [ ]* 4.5 Tulis smoke test untuk integritas data migrasi (Property 20)
    - **Property 20: Integritas Data Saat Migrasi**
    - **Validates: Requirements 9.4**
    - Buat `tests/Smoke/SchemaTest.php`
    - Verifikasi kolom `school_id` ada di `books`, `students`, `loans`, `users`
    - Verifikasi kolom `role` ada di `users`

- [x] 5. Buat Middleware baru
  - [x] 5.1 Buat `app/Http/Middleware/TenantMiddleware.php`
    - Ambil `school_id` dari `Auth::user()->school_id`
    - Bind ke container: `app()->instance('current_school_id', $schoolId)`
    - Lanjutkan request dengan `$next($request)`
    - _Requirements: 8.1, 8.2, 8.5_

  - [x] 5.2 Buat `app/Http/Middleware/SubscriptionMiddleware.php`
    - Load School dari `school_id` yang sudah di-bind di container
    - Jika status `expired` atau `suspended` DAN bukan route pembayaran (`/subscription/*`) → redirect ke `/subscription/info`
    - Jika status `trial` atau `active` → lanjutkan request
    - _Requirements: 4.1, 4.2, 8.3, 8.4_

  - [x] 5.3 Buat `app/Http/Middleware/RoleMiddleware.php`
    - Terima parameter `$role` dari route definition
    - Cek `Auth::user()->role === $role`, jika tidak → `abort(403)`
    - _Requirements: 6.7, 7.4_

  - [x] 5.4 Daftarkan ketiga middleware di `bootstrap/app.php`
    - Daftarkan `TenantMiddleware` dengan alias `tenant`
    - Daftarkan `SubscriptionMiddleware` dengan alias `subscription`
    - Daftarkan `RoleMiddleware` dengan alias `role`
    - _Requirements: 8.1_

  - [ ]* 5.5 Tulis feature test untuk middleware (Property 9, 17, 19)
    - **Property 9: Status Langganan Mengontrol Akses (dengan Pengecualian Route Pembayaran)**
    - **Property 17: Proteksi Route Berdasarkan Role**
    - **Property 19: Redirect ke Login untuk Request Tidak Terautentikasi**
    - **Validates: Requirements 4.1, 4.2, 6.7, 7.4, 7.6, 8.4**
    - Buat `tests/Feature/Subscription/SubscriptionAccessTest.php`

- [x] 6. Buat Controllers baru
  - [x] 6.1 Buat `app/Http/Controllers/LandingController.php`
    - Method `index()`: tampilkan halaman landing publik
    - Jika user sudah login, redirect ke dashboard sesuai role
    - _Requirements: 10.1, 10.2, 10.3_

  - [x] 6.2 Buat `app/Http/Controllers/RegistrationController.php`
    - Method `show()`: tampilkan form registrasi
    - Method `store(Request $request)`: validasi input (nama sekolah, slug, nama admin, email, password)
    - Validasi slug: `regex:/^[a-z0-9\-]+$/`, min:3, max:50, unique:schools,slug
    - Validasi email: unique:users,email
    - Buat record School dengan `trial_ends_at = now()->addDays(30)`, status `trial`
    - Buat User dengan role `school_admin`, `school_id` = ID sekolah baru
    - Login otomatis dengan `Auth::login($user)`, redirect ke `/dashboard`
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6_

  - [ ]* 6.3 Tulis property test untuk registrasi (Property 1, 2, 3)
    - **Property 1: Registrasi membuat School, Admin, dan Trial**
    - **Property 2: Validasi Format Slug**
    - **Property 3: Keunikan Slug dan Email**
    - **Validates: Requirements 1.2, 1.3, 1.5, 1.6, 2.2**
    - Buat `tests/Feature/Registration/RegistrationPropertyTest.php` menggunakan Eris

  - [x] 6.4 Modifikasi `app/Http/Controllers/AuthController.php`
    - Method `showLogin()`: jika sudah login, redirect berdasarkan role (super_admin → `/superadmin/dashboard`, school_admin → `/dashboard`)
    - Method `login()`: setelah berhasil login, redirect berdasarkan role
    - _Requirements: 7.3, 10.3_

  - [ ]* 6.5 Tulis feature test untuk auth redirect (Property 18)
    - **Property 18: Redirect Pasca-Login Berdasarkan Role**
    - **Validates: Requirements 7.3, 10.3**
    - Buat `tests/Feature/Auth/AuthRedirectTest.php`

  - [x] 6.6 Buat `app/Http/Controllers/SubscriptionController.php`
    - Method `info()`: tampilkan halaman info langganan (status, sisa hari, cara bayar)
    - Method `paymentForm()`: tampilkan form pengajuan pembayaran
    - Method `submitPayment(Request $request)`: validasi dan simpan Payment dengan status `pending`
    - Method `history()`: tampilkan riwayat Payment milik sekolah yang sedang login
    - _Requirements: 4.2, 4.5, 5.1, 5.2, 5.6_

  - [ ]* 6.7 Tulis property test untuk payment workflow (Property 11, 14)
    - **Property 11: Submission Pembayaran Membuat Record Pending**
    - **Property 14: Isolasi Riwayat Pembayaran Per Sekolah**
    - **Validates: Requirements 5.2, 5.3, 5.6**
    - Buat `tests/Feature/Payment/PaymentPropertyTest.php` menggunakan Eris

  - [x] 6.8 Buat `app/Http/Controllers/SuperAdmin/DashboardController.php`
    - Method `index()`: hitung dan tampilkan statistik platform
    - Statistik: total sekolah, jumlah per status (trial/active/expired/suspended), total pendapatan dari Payment approved
    - _Requirements: 6.1, 6.2_

  - [x] 6.9 Buat `app/Http/Controllers/SuperAdmin/SchoolController.php`
    - Method `index()`: tampilkan daftar semua School dengan nama, slug, status, tanggal, jumlah Payment pending
    - Method `updateStatus(Request $request, School $school)`: ubah `subscription_status` menjadi `suspended` atau `active`
    - _Requirements: 6.3, 6.4_

  - [x] 6.10 Buat `app/Http/Controllers/SuperAdmin/PaymentController.php`
    - Method `index()`: tampilkan daftar semua Payment dengan status `pending`
    - Method `approve(Request $request, Payment $payment)`: validasi status masih `pending`, ubah status Payment menjadi `approved`, update School (status `active`, `subscription_ends_at` = sekarang + 1 tahun), catat `reviewed_at` dan `reviewed_by`
    - Method `reject(Request $request, Payment $payment)`: validasi status masih `pending`, ubah status Payment menjadi `rejected`, simpan `rejection_reason`, catat `reviewed_at` dan `reviewed_by`
    - Gunakan DB transaction untuk atomicity pada approve
    - _Requirements: 5.4, 5.5, 6.5, 6.6_

  - [ ]* 6.11 Tulis property test untuk payment approval (Property 12, 13)
    - **Property 12: Approval Pembayaran Mengupdate School dan Mencatat Audit**
    - **Property 13: Penolakan Pembayaran Menyimpan Alasan dan Audit**
    - **Validates: Requirements 5.4, 5.5, 6.6**
    - Buat `tests/Feature/Payment/PaymentWorkflowTest.php`

  - [ ]* 6.12 Tulis property test untuk Super Admin access (Property 15, 16)
    - **Property 15: Super Admin Melihat Semua Sekolah**
    - **Property 16: Akurasi Statistik Dashboard Super Admin**
    - **Validates: Requirements 6.2, 6.3**
    - Buat `tests/Feature/SuperAdmin/SuperAdminPropertyTest.php` menggunakan Eris

- [x] 7. Checkpoint — Pastikan semua controller dapat di-resolve tanpa error
  - Jalankan `php artisan route:list` dan verifikasi semua route terdaftar
  - Pastikan tidak ada error pada `php artisan config:cache`

- [x] 8. Update Routes
  - [x] 8.1 Update `routes/web.php` — tambah route publik
    - `GET /` → `LandingController@index` (nama: `landing`)
    - `GET /register` → `RegistrationController@show` (nama: `register`)
    - `POST /register` → `RegistrationController@store` (nama: `register.store`)
    - Update `GET /login` dan `POST /login` untuk redirect berdasarkan role
    - _Requirements: 1.1, 10.1, 10.2_

  - [x] 8.2 Update `routes/web.php` — tambah middleware `tenant` dan `subscription` ke route School Admin
    - Tambah `tenant` dan `subscription` ke middleware group yang sudah ada (`auth`)
    - Tambah route subscription: `GET /subscription/info`, `GET /subscription/payment`, `POST /subscription/payment`, `GET /subscription/history`
    - Route subscription TIDAK boleh dibungkus `subscription` middleware (agar tetap bisa diakses saat expired)
    - _Requirements: 4.2, 4.5, 5.1, 8.1_

  - [x] 8.3 Update `routes/web.php` — tambah route Super Admin
    - Buat route group dengan prefix `/superadmin` dan middleware `auth`, `role:super_admin`
    - `GET /superadmin/dashboard` → `SuperAdmin\DashboardController@index`
    - `GET /superadmin/schools` → `SuperAdmin\SchoolController@index`
    - `PATCH /superadmin/schools/{school}/status` → `SuperAdmin\SchoolController@updateStatus`
    - `GET /superadmin/payments` → `SuperAdmin\PaymentController@index`
    - `PATCH /superadmin/payments/{payment}/approve` → `SuperAdmin\PaymentController@approve`
    - `PATCH /superadmin/payments/{payment}/reject` → `SuperAdmin\PaymentController@reject`
    - _Requirements: 6.1, 6.4, 6.5, 6.7_

  - [ ]* 8.4 Tulis smoke test untuk aksesibilitas route (Property 19)
    - **Property 19: Redirect ke Login untuk Request Tidak Terautentikasi**
    - **Validates: Requirements 1.1, 4.5, 6.1, 7.6, 10.1**
    - Buat `tests/Smoke/RouteAccessibilityTest.php`
    - Verifikasi `/`, `/register`, `/login` dapat diakses tanpa auth (HTTP 200)
    - Verifikasi route protected redirect ke `/login` jika belum auth

- [x] 9. Buat Views
  - [x] 9.1 Buat layout baru `resources/views/layouts/public.blade.php`
    - Layout untuk halaman publik (landing, register, login)
    - Sertakan Bootstrap 5 CDN, navbar dengan link ke `/` (landing), `/login`, `/register`
    - _Requirements: 10.1, 10.2_

  - [x] 9.2 Buat layout baru `resources/views/layouts/superadmin.blade.php`
    - Layout untuk panel Super Admin
    - Sidebar dengan navigasi: Dashboard, Sekolah, Pembayaran
    - Tampilkan nama Super Admin yang sedang login
    - _Requirements: 6.1_

  - [x] 9.3 Update `resources/views/layouts/app.blade.php` (atau layout existing)
    - Tambah informasi sisa hari langganan di navbar/header
    - Tampilkan badge peringatan jika sisa hari ≤ 7
    - Tambah link ke `/subscription/info` di navbar
    - _Requirements: 4.3, 4.4_

  - [x] 9.4 Buat `resources/views/landing.blade.php`
    - Tampilkan informasi platform, fitur utama, harga (Rp 1.500.000/tahun)
    - Tombol CTA: "Daftar Sekarang" → `/register`, "Login" → `/login`
    - _Requirements: 10.1, 10.2_

  - [x] 9.5 Buat `resources/views/auth/register.blade.php`
    - Form registrasi: nama sekolah, slug, nama admin, email, password, konfirmasi password
    - Tampilkan error validasi per-field tanpa menghapus input lain
    - Informasi trial gratis 30 hari
    - _Requirements: 1.1, 1.4, 1.6_

  - [x] 9.6 Buat `resources/views/subscription/info.blade.php`
    - Tampilkan status langganan saat ini, sisa hari, tanggal berakhir
    - Instruksi cara melakukan pembayaran (nomor rekening, nominal)
    - Tombol "Ajukan Pembayaran" → `/subscription/payment`
    - _Requirements: 4.2, 4.5_

  - [x] 9.7 Buat `resources/views/subscription/payment.blade.php`
    - Form pengajuan pembayaran: nominal, tanggal transfer, nama bank, catatan
    - _Requirements: 5.1_

  - [x] 9.8 Buat `resources/views/subscription/history.blade.php`
    - Tabel riwayat pembayaran: tanggal, nominal, bank, status, alasan penolakan (jika ada)
    - _Requirements: 5.6_

  - [x] 9.9 Buat `resources/views/superadmin/dashboard.blade.php`
    - Kartu statistik: total sekolah, active, trial, expired, total pendapatan
    - _Requirements: 6.2_

  - [x] 9.10 Buat `resources/views/superadmin/schools/index.blade.php`
    - Tabel daftar sekolah: nama, slug, status (badge warna), tanggal trial/expired, jumlah payment pending
    - Tombol aksi: Suspend / Aktifkan per baris
    - _Requirements: 6.3, 6.4_

  - [x] 9.11 Buat `resources/views/superadmin/payments/index.blade.php`
    - Tabel daftar Payment pending: nama sekolah, nominal, tanggal transfer, bank
    - Tombol Approve dan Reject per baris
    - Form inline untuk alasan penolakan saat Reject
    - _Requirements: 6.5, 6.6_

  - [x] 9.12 Update `resources/views/dashboard.blade.php` (School Admin)
    - Tambah widget sisa hari langganan di bagian atas
    - Tampilkan notifikasi peringatan jika sisa hari ≤ 7
    - _Requirements: 4.3, 4.4_

- [x] 10. Checkpoint — Pastikan semua tests pass dan aplikasi berjalan
  - Jalankan `php artisan test` dan pastikan semua test hijau
  - Jalankan seeder: `php artisan db:seed` dan verifikasi data terbentuk
  - Pastikan tidak ada data existing yang hilang setelah seeder dijalankan

- [x] 11. Integrasi akhir dan wiring
  - [x] 11.1 Update `app/Providers/AppServiceProvider.php`
    - Tidak ada perubahan khusus yang diperlukan (TenantScope di-bind via middleware)
    - Verifikasi `Paginator::useBootstrapFive()` masih aktif
    - _Requirements: 8.5_

  - [x] 11.2 Verifikasi integrasi TenantMiddleware → TenantScope → Controller
    - Pastikan `app()->instance('current_school_id', ...)` di TenantMiddleware terbaca oleh TenantScope
    - Test manual: login sebagai school_admin, akses `/books`, verifikasi hanya buku sekolah sendiri yang muncul
    - _Requirements: 3.2, 3.3, 8.2_

  - [x] 11.3 Verifikasi alur registrasi end-to-end
    - Akses `/register`, isi form, verifikasi redirect ke `/dashboard` setelah registrasi
    - Verifikasi trial 30 hari terbentuk dengan benar
    - _Requirements: 1.2, 1.5_

  - [x] 11.4 Verifikasi alur pembayaran end-to-end
    - Login sebagai school_admin dengan status expired, verifikasi redirect ke `/subscription/info`
    - Ajukan pembayaran, verifikasi record Payment pending terbentuk
    - Login sebagai super_admin, approve pembayaran, verifikasi status sekolah berubah ke `active`
    - _Requirements: 4.2, 5.2, 5.4_

  - [ ]* 11.5 Tulis property test untuk subscription access (Property 10)
    - **Property 10: Kalkulasi Sisa Hari Langganan**
    - **Validates: Requirements 4.3**
    - Buat `tests/Feature/Subscription/SubscriptionPropertyTest.php` menggunakan Eris
    - Generate berbagai nilai tanggal, verifikasi `daysRemaining()` konsisten

- [x] 12. Final Checkpoint — Pastikan semua tests pass
  - Jalankan `php artisan test --stop-on-failure` dan pastikan semua test hijau
  - Verifikasi `php artisan route:list` menampilkan semua route yang diharapkan
  - Pastikan tidak ada data existing yang hilang atau rusak

## Notes

- Tasks bertanda `*` bersifat opsional dan dapat dilewati untuk MVP yang lebih cepat
- Setiap task mereferensikan requirements spesifik untuk traceability
- Urutan implementasi: migrations → models → seeders → middleware → controllers → routes → views
- Gunakan `RefreshDatabase` trait pada semua feature test untuk isolasi
- Gunakan `withoutMiddleware([SubscriptionMiddleware::class])` pada test yang fokus pada logika lain
- Property tests menggunakan library Eris (`composer require --dev giorgiosironi/eris`)
- Setiap property test dikonfigurasi minimum 100 iterasi dengan `MinimumEvaluations::times(100)`
- TenantScope bersifat graceful: jika `current_school_id` tidak di-bind (Super Admin), tidak ada filter yang diterapkan
- Migration kolom `school_id` menggunakan nullable dulu, baru NOT NULL setelah seeder backfill data existing
