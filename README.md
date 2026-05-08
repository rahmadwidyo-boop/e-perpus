# 📚 E-Perpustakaan Sekolah

Sistem manajemen perpustakaan sekolah berbasis web, dibangun dengan **Laravel 12** dan **PHP 8.2**.

---

## 🔗 Cara Membuka Aplikasi

Pastikan **XAMPP sudah berjalan** (Apache + MySQL aktif), lalu buka browser:

```
http://localhost/e-perpus/public
```

---

## 🔐 Login Admin

| Field    | Value                  |
|----------|------------------------|
| Email    | admin@eperpus.com      |
| Password | admin123               |

> Ganti password setelah pertama kali login melalui database atau tambahkan fitur ganti password.

---

## ⚙️ Cara Menjalankan (Setup Awal)

### 1. Pastikan XAMPP Aktif
- Buka **XAMPP Control Panel**
- Klik **Start** pada **Apache** dan **MySQL**

### 2. Letakkan Project
Pastikan folder project berada di:
```
C:\xampp\htdocs\e-perpus\
```

### 3. Install Dependensi (jika belum)
Buka terminal / command prompt di folder project, lalu jalankan:
```bash
composer install
```

### 4. Salin File Environment
```bash
cp .env.example .env
```
Lalu edit file `.env`, sesuaikan bagian database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_perpus
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate App Key
```bash
php artisan key:generate
```

### 6. Buat Database
Buka **phpMyAdmin** → `http://localhost/phpmyadmin`  
Buat database baru dengan nama: `e_perpus`

### 7. Jalankan Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 8. Buat Storage Link (untuk upload cover buku)
```bash
php artisan storage:link
```

### 9. Buka Aplikasi
```
http://localhost/e-perpus/public
```

---

## 🚀 Menjalankan dengan Laravel Development Server (Opsional)

Jika tidak ingin pakai XAMPP, bisa jalankan server bawaan Laravel:

```bash
php artisan serve
```

Lalu buka:
```
http://127.0.0.1:8000
```

> **Catatan:** Cara ini hanya untuk development. Untuk XAMPP, gunakan link di atas.

---

## 📋 Fitur Aplikasi

| Fitur | Keterangan |
|-------|------------|
| 🏠 Dashboard | Statistik buku, siswa, peminjaman, keterlambatan + grafik |
| 📖 Manajemen Buku | CRUD buku, upload cover, filter kategori |
| 👨‍🎓 Manajemen Siswa | CRUD siswa, filter kelas, import dari Excel |
| 🔄 Peminjaman | Input peminjaman, stok otomatis berkurang |
| ↩️ Pengembalian | Kembalikan buku, hitung denda otomatis |
| 📊 Laporan | Filter & export data peminjaman ke Excel (.xlsx) |

---

## 📁 Format Import Excel Siswa

File Excel harus memiliki header di baris pertama:

| nis | nama | kelas | no_hp | alamat |
|-----|------|-------|-------|--------|
| 2024001 | Ahmad Fauzi | X 1 | 081234... | Jl. ... |

**Kelas yang valid:** X 1 – X 7 · XI 1 – XI 7 · XII 1 – XII 7

> Download template tersedia di halaman **Import Siswa**.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Database:** MySQL (MariaDB 10.4 via XAMPP)
- **Frontend:** Bootstrap 5, Bootstrap Icons, Chart.js
- **Export/Import:** Maatwebsite/Laravel-Excel

---

## 📂 Struktur Folder Penting

```
e-perpus/
├── app/
│   ├── Http/Controllers/   # AuthController, BookController, dll
│   ├── Models/             # Book, Student, Loan
│   ├── Imports/            # StudentsImport (import Excel)
│   └── Exports/            # LoansExport (export Excel)
├── database/
│   ├── migrations/         # Struktur tabel database
│   └── seeders/            # Data awal (admin)
├── resources/views/        # Tampilan Blade
├── routes/web.php          # Semua routing
├── .env                    # Konfigurasi (jangan di-commit!)
└── public/                 # Entry point aplikasi
```

---

## ⚠️ Catatan untuk GitHub

File `.env` sudah ada di `.gitignore` dan **tidak akan ikut ter-upload** ke GitHub.  
Setelah clone dari GitHub, jalankan ulang langkah setup dari **nomor 4** di atas.

---

## 👤 Akun Default

```
Email    : admin@eperpus.com
Password : admin123
```

> Segera ganti password setelah deploy ke server production.
