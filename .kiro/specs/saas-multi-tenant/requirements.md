# Requirements Document

## Introduction

Upgrade aplikasi e-perpustakaan sekolah (Laravel 12, PHP 8.2) yang saat ini bersifat single-tenant menjadi platform SaaS multi-tenant. Setiap sekolah mendapatkan ruang data yang terisolasi dalam satu database (single-database multi-tenancy menggunakan `school_id`). Platform menyediakan tiga lapisan: halaman publik untuk registrasi sekolah baru, panel Super Admin untuk mengelola seluruh tenant dan pembayaran, serta panel Admin Sekolah yang merupakan evolusi dari aplikasi perpustakaan yang sudah ada.

Sistem menggunakan path-based routing (bukan subdomain) karena dijalankan di lingkungan lokal (`localhost/e-perpus/public/`). Monetisasi dilakukan melalui langganan tahunan Rp 1.500.000 dengan konfirmasi pembayaran manual oleh Super Admin. Setiap sekolah baru mendapatkan masa trial gratis 30 hari secara otomatis.

---

## Glossary

- **System**: Aplikasi SaaS e-perpustakaan multi-tenant secara keseluruhan.
- **Super_Admin**: Pengguna dengan akses penuh ke seluruh data semua tenant dan manajemen platform.
- **School_Admin**: Pengguna administrator sebuah sekolah yang mengelola data perpustakaan sekolahnya sendiri.
- **School**: Entitas tenant yang merepresentasikan satu sekolah pelanggan.
- **Tenant**: Sinonim untuk School dalam konteks multi-tenancy.
- **Subscription**: Status langganan sebuah School yang menentukan hak akses ke fitur perpustakaan.
- **Trial**: Status langganan awal selama 30 hari sejak registrasi, tanpa biaya.
- **Payment**: Bukti pembayaran yang diunggah oleh School_Admin untuk perpanjangan langganan.
- **Tenant_Middleware**: Komponen Laravel yang memfilter semua query data perpustakaan berdasarkan `school_id` milik School_Admin yang sedang login.
- **TenantScope**: Laravel Global Scope yang secara otomatis menambahkan klausa `WHERE school_id = ?` pada semua query model Book, Student, dan Loan.
- **Loan**: Transaksi peminjaman buku oleh siswa.
- **Book**: Data buku dalam koleksi perpustakaan sebuah sekolah.
- **Student**: Data siswa yang terdaftar di sebuah sekolah.

---

## Requirements

### Requirement 1: Registrasi Sekolah Baru (Publik)

**User Story:** Sebagai pengelola sekolah, saya ingin mendaftarkan sekolah saya ke platform melalui halaman publik, sehingga saya dapat langsung menggunakan sistem perpustakaan dengan masa trial gratis 30 hari.

#### Acceptance Criteria

1. THE System SHALL menyediakan halaman registrasi publik yang dapat diakses tanpa login di path `/register`.
2. WHEN seorang pengunjung mengisi form registrasi dengan nama sekolah, slug sekolah, nama admin, email, dan password yang valid, THEN THE System SHALL membuat record School baru, membuat akun School_Admin yang terhubung ke School tersebut, dan mengaktifkan masa trial selama 30 hari secara otomatis.
3. WHEN seorang pengunjung mengirimkan form registrasi, THE System SHALL memvalidasi bahwa email belum terdaftar dan slug sekolah belum digunakan oleh School lain.
4. IF email atau slug sudah terdaftar, THEN THE System SHALL menampilkan pesan error yang spesifik pada field yang bermasalah tanpa menghapus input lainnya.
5. WHEN registrasi berhasil, THEN THE System SHALL langsung melakukan login sebagai School_Admin baru dan mengarahkan ke halaman dashboard perpustakaan.
6. THE System SHALL memvalidasi bahwa slug hanya mengandung huruf kecil, angka, dan tanda hubung, dengan panjang 3–50 karakter.

---

### Requirement 2: Manajemen Data Sekolah (Tabel Schools)

**User Story:** Sebagai Super_Admin, saya ingin setiap sekolah memiliki data profil dan status langganan yang tersimpan, sehingga saya dapat mengelola seluruh tenant dari satu tempat.

#### Acceptance Criteria

1. THE System SHALL menyimpan data setiap School dengan atribut: `id`, `name` (nama sekolah), `slug` (identifier unik), `admin_name` (nama penanggung jawab), `subscription_status` (`trial`, `active`, `expired`, `suspended`), `trial_ends_at` (tanggal berakhir trial), `subscription_ends_at` (tanggal berakhir langganan berbayar), `created_at`, `updated_at`.
2. THE System SHALL memastikan nilai `slug` bersifat unik di seluruh tabel `schools`.
3. WHEN masa trial sebuah School telah melewati `trial_ends_at` dan School belum melakukan pembayaran, THEN THE System SHALL menganggap status School tersebut sebagai `expired`.
4. WHEN masa langganan berbayar sebuah School telah melewati `subscription_ends_at`, THEN THE System SHALL menganggap status School tersebut sebagai `expired`.

---

### Requirement 3: Isolasi Data Antar Tenant (Multi-Tenancy)

**User Story:** Sebagai School_Admin, saya ingin data buku, siswa, dan peminjaman sekolah saya tidak bisa diakses oleh sekolah lain, sehingga privasi dan keamanan data terjaga.

#### Acceptance Criteria

1. THE System SHALL menambahkan kolom `school_id` (foreign key ke tabel `schools`) pada tabel `books`, `students`, dan `loans`.
2. THE System SHALL menerapkan TenantScope sebagai Global Scope pada model Book, Student, dan Loan sehingga setiap query secara otomatis difilter berdasarkan `school_id` milik School_Admin yang sedang aktif.
3. WHEN seorang School_Admin melakukan query apapun terhadap data Book, Student, atau Loan, THEN THE System SHALL hanya mengembalikan data yang memiliki `school_id` sama dengan sekolah milik School_Admin tersebut.
4. IF seorang School_Admin mencoba mengakses resource (buku, siswa, atau peminjaman) yang `school_id`-nya berbeda dari sekolahnya, THEN THE System SHALL mengembalikan respons 404 Not Found.
5. WHEN seorang School_Admin membuat data baru (Book, Student, atau Loan), THEN THE System SHALL secara otomatis mengisi `school_id` dengan ID sekolah milik School_Admin yang sedang login.

---

### Requirement 4: Sistem Langganan dan Status Akses

**User Story:** Sebagai School_Admin, saya ingin mengetahui status langganan sekolah saya dan mendapatkan akses penuh selama langganan aktif, sehingga saya dapat merencanakan perpanjangan tepat waktu.

#### Acceptance Criteria

1. WHILE status langganan School adalah `trial` atau `active`, THE System SHALL mengizinkan School_Admin mengakses seluruh fitur perpustakaan.
2. WHEN status langganan School adalah `expired` atau `suspended`, THE System SHALL mengarahkan School_Admin ke halaman informasi langganan dan mencegah akses ke fitur perpustakaan.
3. THE System SHALL menampilkan informasi sisa hari trial atau sisa hari langganan pada dashboard School_Admin.
4. WHEN sisa hari langganan kurang dari atau sama dengan 7 hari, THE System SHALL menampilkan notifikasi peringatan pada dashboard School_Admin.
5. THE System SHALL menyediakan halaman khusus yang menjelaskan cara melakukan pembayaran perpanjangan langganan ketika status School adalah `expired`.

---

### Requirement 5: Pembayaran Manual dan Konfirmasi Super Admin

**User Story:** Sebagai School_Admin, saya ingin mengunggah bukti transfer pembayaran langganan, sehingga Super Admin dapat memverifikasi dan mengaktifkan langganan saya.

#### Acceptance Criteria

1. THE System SHALL menyediakan form bagi School_Admin untuk mengirimkan permintaan pembayaran dengan data: nominal yang dibayarkan, tanggal transfer, nama bank pengirim, dan catatan opsional.
2. WHEN School_Admin mengirimkan permintaan pembayaran, THEN THE System SHALL menyimpan record Payment dengan status `pending` dan menampilkan konfirmasi bahwa pembayaran sedang diproses.
3. THE System SHALL menyimpan data Payment dengan atribut: `id`, `school_id`, `amount`, `transfer_date`, `bank_name`, `notes`, `status` (`pending`, `approved`, `rejected`), `reviewed_at`, `reviewed_by`, `created_at`.
4. WHEN Super_Admin menyetujui sebuah Payment, THEN THE System SHALL mengubah status Payment menjadi `approved`, mengubah `subscription_status` School menjadi `active`, dan menetapkan `subscription_ends_at` menjadi 1 tahun dari tanggal persetujuan.
5. WHEN Super_Admin menolak sebuah Payment, THEN THE System SHALL mengubah status Payment menjadi `rejected` dan menyimpan alasan penolakan yang dapat dilihat oleh School_Admin.
6. THE System SHALL menampilkan riwayat semua Payment milik sebuah School kepada School_Admin yang bersangkutan.

---

### Requirement 6: Panel Super Admin

**User Story:** Sebagai Super_Admin, saya ingin memiliki panel terpusat untuk mengelola semua sekolah, memverifikasi pembayaran, dan melihat statistik platform, sehingga saya dapat mengoperasikan bisnis SaaS ini secara efisien.

#### Acceptance Criteria

1. THE Super_Admin SHALL dapat mengakses panel manajemen di path `/superadmin/dashboard` setelah login.
2. THE System SHALL menampilkan statistik ringkasan pada dashboard Super_Admin: total sekolah terdaftar, jumlah sekolah dengan status `active`, jumlah sekolah dengan status `trial`, jumlah sekolah dengan status `expired`, dan total pendapatan dari Payment yang `approved`.
3. THE Super_Admin SHALL dapat melihat daftar semua School beserta nama, slug, status langganan, tanggal trial/expired, dan jumlah Payment pending.
4. THE Super_Admin SHALL dapat mengubah `subscription_status` sebuah School menjadi `suspended` atau `active` secara langsung dari daftar sekolah.
5. THE Super_Admin SHALL dapat melihat daftar semua Payment dengan status `pending` dan melakukan aksi approve atau reject pada setiap Payment.
6. WHEN Super_Admin melakukan approve atau reject pada Payment, THEN THE System SHALL mencatat `reviewed_at` dengan timestamp saat ini dan `reviewed_by` dengan ID Super_Admin yang melakukan aksi.
7. THE System SHALL memastikan hanya pengguna dengan role `super_admin` yang dapat mengakses semua route di bawah prefix `/superadmin/`.

---

### Requirement 7: Sistem Role dan Autentikasi

**User Story:** Sebagai pengguna sistem, saya ingin login dengan satu halaman login yang sama dan diarahkan ke panel yang sesuai dengan role saya, sehingga pengalaman login terasa sederhana.

#### Acceptance Criteria

1. THE System SHALL menambahkan kolom `role` pada tabel `users` dengan nilai yang mungkin: `super_admin`, `school_admin`.
2. THE System SHALL menambahkan kolom `school_id` (nullable, foreign key ke `schools`) pada tabel `users`.
3. WHEN seorang pengguna berhasil login, THEN THE System SHALL mengarahkan pengguna dengan role `super_admin` ke `/superadmin/dashboard` dan pengguna dengan role `school_admin` ke `/dashboard`.
4. THE System SHALL memastikan pengguna dengan role `school_admin` tidak dapat mengakses route dengan prefix `/superadmin/`.
5. THE System SHALL memastikan pengguna dengan role `super_admin` tidak terikat pada `school_id` manapun dan dapat melihat data lintas tenant.
6. IF seorang pengguna yang belum login mencoba mengakses route yang dilindungi, THEN THE System SHALL mengarahkan pengguna tersebut ke halaman login.

---

### Requirement 8: Middleware Tenant dan Pemeriksaan Status Langganan

**User Story:** Sebagai pengembang sistem, saya ingin ada middleware yang secara otomatis menegakkan isolasi data dan pemeriksaan status langganan, sehingga tidak ada celah akses data lintas tenant atau akses oleh tenant yang sudah expired.

#### Acceptance Criteria

1. THE System SHALL menyediakan Tenant_Middleware yang dijalankan pada setiap request ke route perpustakaan milik School_Admin.
2. WHEN Tenant_Middleware dijalankan, THE System SHALL mengambil `school_id` dari data pengguna yang sedang login dan menyimpannya dalam konteks request saat ini.
3. WHEN Tenant_Middleware dijalankan, THE System SHALL memeriksa status langganan School yang bersangkutan.
4. IF status langganan School adalah `expired` atau `suspended`, THEN THE System SHALL mengarahkan request ke halaman informasi langganan, kecuali untuk route pembayaran.
5. THE System SHALL memastikan TenantScope aktif untuk semua query model Book, Student, dan Loan selama request berlangsung.

---

### Requirement 9: Migrasi Data Existing

**User Story:** Sebagai pengembang, saya ingin data yang sudah ada di database dapat dimigrasikan ke struktur multi-tenant tanpa kehilangan data, sehingga upgrade dapat dilakukan dengan aman.

#### Acceptance Criteria

1. THE System SHALL menyediakan migration database untuk menambahkan kolom `school_id` pada tabel `books`, `students`, dan `loans` dengan nilai default yang dapat dikonfigurasi untuk data existing.
2. THE System SHALL menyediakan seeder untuk membuat satu record School default (untuk data existing) dan mengupdate semua record existing di tabel `books`, `students`, dan `loans` agar memiliki `school_id` yang valid.
3. THE System SHALL menyediakan seeder untuk mengubah akun admin existing menjadi `school_admin` dengan `school_id` yang sesuai, serta membuat satu akun `super_admin` baru.
4. WHEN migration dijalankan pada database yang sudah berisi data, THE System SHALL memastikan tidak ada data yang hilang atau rusak.

---

### Requirement 10: Halaman Landing dan Navigasi Publik

**User Story:** Sebagai pengunjung baru, saya ingin ada halaman utama yang menjelaskan platform ini dan menyediakan tautan untuk mendaftar atau login, sehingga saya dapat memahami layanan sebelum mendaftar.

#### Acceptance Criteria

1. THE System SHALL menyediakan halaman landing publik di path `/` yang menampilkan informasi tentang platform, fitur utama, dan harga langganan (Rp 1.500.000/tahun).
2. THE System SHALL menampilkan tautan ke halaman registrasi (`/register`) dan halaman login (`/login`) dari halaman landing.
3. WHEN seorang pengguna yang sudah login mengakses halaman landing atau login, THEN THE System SHALL mengarahkan pengguna tersebut ke dashboard yang sesuai dengan role-nya.
