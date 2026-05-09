<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil school_id dari sekolah default (admin@eperpus.com)
        $schoolId = DB::table('users')->where('email', 'admin@eperpus.com')->value('school_id') ?? 1;

        $this->command->info("Mengisi demo data untuk school_id: {$schoolId}");

        // ── 20 Siswa ──────────────────────────────────────────────────────────
        $students = [
            ['nis' => '2024001', 'name' => 'Ahmad Fauzi',        'class' => 'X 1'],
            ['nis' => '2024002', 'name' => 'Siti Rahayu',        'class' => 'X 2'],
            ['nis' => '2024003', 'name' => 'Budi Santoso',       'class' => 'X 3'],
            ['nis' => '2024004', 'name' => 'Dewi Lestari',       'class' => 'X 4'],
            ['nis' => '2024005', 'name' => 'Eko Prasetyo',       'class' => 'XI 1'],
            ['nis' => '2024006', 'name' => 'Fitri Handayani',    'class' => 'XI 2'],
            ['nis' => '2024007', 'name' => 'Gilang Ramadhan',    'class' => 'XI 3'],
            ['nis' => '2024008', 'name' => 'Hana Pertiwi',       'class' => 'XI 4'],
            ['nis' => '2024009', 'name' => 'Irfan Maulana',      'class' => 'XI 5'],
            ['nis' => '2024010', 'name' => 'Joko Widodo',        'class' => 'XI 6'],
            ['nis' => '2024011', 'name' => 'Kartika Sari',       'class' => 'XII 1'],
            ['nis' => '2024012', 'name' => 'Lukman Hakim',       'class' => 'XII 2'],
            ['nis' => '2024013', 'name' => 'Maya Anggraini',     'class' => 'XII 3'],
            ['nis' => '2024014', 'name' => 'Nanda Putra',        'class' => 'XII 4'],
            ['nis' => '2024015', 'name' => 'Olivia Susanti',     'class' => 'XII 5'],
            ['nis' => '2024016', 'name' => 'Pandu Wicaksono',    'class' => 'XII 6'],
            ['nis' => '2024017', 'name' => 'Qori Amalia',        'class' => 'X 5'],
            ['nis' => '2024018', 'name' => 'Rizky Pratama',      'class' => 'X 6'],
            ['nis' => '2024019', 'name' => 'Sari Indah',         'class' => 'XI 7'],
            ['nis' => '2024020', 'name' => 'Taufik Hidayat',     'class' => 'XII 7'],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(
                ['nis' => $s['nis'], 'school_id' => $schoolId],
                [
                    'school_id' => $schoolId,
                    'name'      => $s['name'],
                    'class'     => $s['class'],
                    'phone'     => '08' . rand(100000000, 999999999),
                    'address'   => 'Jl. ' . $s['name'] . ' No. ' . rand(1, 100),
                ]
            );
        }
        $this->command->info('✅ 20 siswa berhasil ditambahkan.');

        // ── 50 Buku ───────────────────────────────────────────────────────────
        $books = [
            // Fiksi
            ['code' => 'BK-001', 'title' => 'Laskar Pelangi',                  'category' => 'Fiksi',       'author' => 'Andrea Hirata',        'publisher' => 'Bentang Pustaka',   'year' => 2005, 'shelf' => 'Rak A-1', 'stock' => 3],
            ['code' => 'BK-002', 'title' => 'Bumi Manusia',                    'category' => 'Fiksi',       'author' => 'Pramoedya Ananta Toer','publisher' => 'Hasta Mitra',       'year' => 1980, 'shelf' => 'Rak A-1', 'stock' => 2],
            ['code' => 'BK-003', 'title' => 'Negeri 5 Menara',                 'category' => 'Fiksi',       'author' => 'Ahmad Fuadi',          'publisher' => 'Gramedia',          'year' => 2009, 'shelf' => 'Rak A-2', 'stock' => 4],
            ['code' => 'BK-004', 'title' => 'Ayat-Ayat Cinta',                 'category' => 'Fiksi',       'author' => 'Habiburrahman El Shirazy','publisher' => 'Republika',      'year' => 2004, 'shelf' => 'Rak A-2', 'stock' => 3],
            ['code' => 'BK-005', 'title' => 'Sang Pemimpi',                    'category' => 'Fiksi',       'author' => 'Andrea Hirata',        'publisher' => 'Bentang Pustaka',   'year' => 2006, 'shelf' => 'Rak A-3', 'stock' => 2],
            ['code' => 'BK-006', 'title' => 'Perahu Kertas',                   'category' => 'Fiksi',       'author' => 'Dee Lestari',          'publisher' => 'Bentang Pustaka',   'year' => 2009, 'shelf' => 'Rak A-3', 'stock' => 3],
            ['code' => 'BK-007', 'title' => 'Dilan 1990',                      'category' => 'Fiksi',       'author' => 'Pidi Baiq',            'publisher' => 'Pastel Books',      'year' => 2014, 'shelf' => 'Rak A-4', 'stock' => 5],
            ['code' => 'BK-008', 'title' => 'Pulang',                          'category' => 'Fiksi',       'author' => 'Tere Liye',            'publisher' => 'Republika',         'year' => 2015, 'shelf' => 'Rak A-4', 'stock' => 3],
            ['code' => 'BK-009', 'title' => 'Hujan',                           'category' => 'Fiksi',       'author' => 'Tere Liye',            'publisher' => 'Gramedia',          'year' => 2016, 'shelf' => 'Rak A-5', 'stock' => 4],
            ['code' => 'BK-010', 'title' => 'Rindu',                           'category' => 'Fiksi',       'author' => 'Tere Liye',            'publisher' => 'Republika',         'year' => 2014, 'shelf' => 'Rak A-5', 'stock' => 2],
            // Sains
            ['code' => 'BK-011', 'title' => 'Fisika Dasar Jilid 1',            'category' => 'Sains',       'author' => 'Halliday & Resnick',   'publisher' => 'Erlangga',          'year' => 2010, 'shelf' => 'Rak B-1', 'stock' => 5],
            ['code' => 'BK-012', 'title' => 'Kimia Organik',                   'category' => 'Sains',       'author' => 'Fessenden',            'publisher' => 'Erlangga',          'year' => 2011, 'shelf' => 'Rak B-1', 'stock' => 4],
            ['code' => 'BK-013', 'title' => 'Biologi Sel',                     'category' => 'Sains',       'author' => 'Campbell',             'publisher' => 'Erlangga',          'year' => 2012, 'shelf' => 'Rak B-2', 'stock' => 3],
            ['code' => 'BK-014', 'title' => 'Matematika SMA Kelas X',          'category' => 'Sains',       'author' => 'Sukino',               'publisher' => 'Erlangga',          'year' => 2016, 'shelf' => 'Rak B-2', 'stock' => 6],
            ['code' => 'BK-015', 'title' => 'Matematika SMA Kelas XI',         'category' => 'Sains',       'author' => 'Sukino',               'publisher' => 'Erlangga',          'year' => 2016, 'shelf' => 'Rak B-3', 'stock' => 6],
            ['code' => 'BK-016', 'title' => 'Matematika SMA Kelas XII',        'category' => 'Sains',       'author' => 'Sukino',               'publisher' => 'Erlangga',          'year' => 2016, 'shelf' => 'Rak B-3', 'stock' => 5],
            ['code' => 'BK-017', 'title' => 'Fisika SMA Kelas X',              'category' => 'Sains',       'author' => 'Marthen Kanginan',     'publisher' => 'Erlangga',          'year' => 2017, 'shelf' => 'Rak B-4', 'stock' => 4],
            ['code' => 'BK-018', 'title' => 'Kimia SMA Kelas XI',              'category' => 'Sains',       'author' => 'Unggul Sudarmo',       'publisher' => 'Erlangga',          'year' => 2017, 'shelf' => 'Rak B-4', 'stock' => 4],
            ['code' => 'BK-019', 'title' => 'Biologi SMA Kelas XII',           'category' => 'Sains',       'author' => 'Irnaningtyas',         'publisher' => 'Erlangga',          'year' => 2018, 'shelf' => 'Rak B-5', 'stock' => 3],
            ['code' => 'BK-020', 'title' => 'Pengantar Statistika',            'category' => 'Sains',       'author' => 'Sudjana',              'publisher' => 'Tarsito',           'year' => 2005, 'shelf' => 'Rak B-5', 'stock' => 2],
            // Sejarah
            ['code' => 'BK-021', 'title' => 'Sejarah Indonesia Modern',        'category' => 'Sejarah',     'author' => 'M.C. Ricklefs',        'publisher' => 'Serambi',           'year' => 2008, 'shelf' => 'Rak C-1', 'stock' => 3],
            ['code' => 'BK-022', 'title' => 'Sejarah Nasional Indonesia',      'category' => 'Sejarah',     'author' => 'Marwati Djoened',      'publisher' => 'Balai Pustaka',     'year' => 2008, 'shelf' => 'Rak C-1', 'stock' => 2],
            ['code' => 'BK-023', 'title' => 'Perang Dunia II',                 'category' => 'Sejarah',     'author' => 'Antony Beevor',        'publisher' => 'Gramedia',          'year' => 2012, 'shelf' => 'Rak C-2', 'stock' => 2],
            ['code' => 'BK-024', 'title' => 'Proklamasi Kemerdekaan RI',       'category' => 'Sejarah',     'author' => 'Suhartono',            'publisher' => 'Gramedia',          'year' => 2001, 'shelf' => 'Rak C-2', 'stock' => 3],
            ['code' => 'BK-025', 'title' => 'Sejarah Peradaban Islam',         'category' => 'Sejarah',     'author' => 'Badri Yatim',          'publisher' => 'Rajawali Press',    'year' => 2010, 'shelf' => 'Rak C-3', 'stock' => 2],
            // Teknologi
            ['code' => 'BK-026', 'title' => 'Pemrograman Python untuk Pemula', 'category' => 'Teknologi',   'author' => 'Andi Offset',          'publisher' => 'Andi',              'year' => 2020, 'shelf' => 'Rak D-1', 'stock' => 4],
            ['code' => 'BK-027', 'title' => 'Belajar Laravel dari Nol',        'category' => 'Teknologi',   'author' => 'Ridwan Arifin',        'publisher' => 'Andi',              'year' => 2021, 'shelf' => 'Rak D-1', 'stock' => 3],
            ['code' => 'BK-028', 'title' => 'Desain Web dengan HTML & CSS',    'category' => 'Teknologi',   'author' => 'Jon Duckett',          'publisher' => 'Wiley',             'year' => 2011, 'shelf' => 'Rak D-2', 'stock' => 3],
            ['code' => 'BK-029', 'title' => 'Jaringan Komputer',               'category' => 'Teknologi',   'author' => 'Andrew Tanenbaum',     'publisher' => 'Pearson',           'year' => 2011, 'shelf' => 'Rak D-2', 'stock' => 2],
            ['code' => 'BK-030', 'title' => 'Basis Data',                      'category' => 'Teknologi',   'author' => 'Ramez Elmasri',        'publisher' => 'Pearson',           'year' => 2015, 'shelf' => 'Rak D-3', 'stock' => 3],
            // Bahasa
            ['code' => 'BK-031', 'title' => 'Kamus Besar Bahasa Indonesia',    'category' => 'Bahasa',      'author' => 'Tim Penyusun KBBI',    'publisher' => 'Balai Pustaka',     'year' => 2016, 'shelf' => 'Rak E-1', 'stock' => 2],
            ['code' => 'BK-032', 'title' => 'English Grammar in Use',          'category' => 'Bahasa',      'author' => 'Raymond Murphy',       'publisher' => 'Cambridge',         'year' => 2019, 'shelf' => 'Rak E-1', 'stock' => 4],
            ['code' => 'BK-033', 'title' => 'Bahasa Indonesia untuk SMA',      'category' => 'Bahasa',      'author' => 'Dawud',                'publisher' => 'Erlangga',          'year' => 2018, 'shelf' => 'Rak E-2', 'stock' => 5],
            ['code' => 'BK-034', 'title' => 'Tata Bahasa Baku Indonesia',      'category' => 'Bahasa',      'author' => 'Hasan Alwi',           'publisher' => 'Balai Pustaka',     'year' => 2003, 'shelf' => 'Rak E-2', 'stock' => 2],
            ['code' => 'BK-035', 'title' => 'Kamus Inggris-Indonesia',         'category' => 'Bahasa',      'author' => 'John M. Echols',       'publisher' => 'Gramedia',          'year' => 2014, 'shelf' => 'Rak E-3', 'stock' => 3],
            // Agama
            ['code' => 'BK-036', 'title' => 'Tafsir Al-Misbah Vol. 1',        'category' => 'Agama',       'author' => 'M. Quraish Shihab',    'publisher' => 'Lentera Hati',      'year' => 2002, 'shelf' => 'Rak F-1', 'stock' => 2],
            ['code' => 'BK-037', 'title' => 'Fiqih Islam Wa Adillatuhu',       'category' => 'Agama',       'author' => 'Wahbah Az-Zuhaili',    'publisher' => 'Gema Insani',       'year' => 2011, 'shelf' => 'Rak F-1', 'stock' => 2],
            ['code' => 'BK-038', 'title' => 'Pendidikan Agama Islam SMA',      'category' => 'Agama',       'author' => 'Endi Suhendi',         'publisher' => 'Erlangga',          'year' => 2019, 'shelf' => 'Rak F-2', 'stock' => 4],
            ['code' => 'BK-039', 'title' => 'Sirah Nabawiyah',                 'category' => 'Agama',       'author' => 'Ibnu Hisyam',          'publisher' => 'Darul Falah',       'year' => 2000, 'shelf' => 'Rak F-2', 'stock' => 2],
            ['code' => 'BK-040', 'title' => 'Riyadhus Shalihin',               'category' => 'Agama',       'author' => 'Imam An-Nawawi',       'publisher' => 'Gema Insani',       'year' => 2005, 'shelf' => 'Rak F-3', 'stock' => 3],
            // Sosial
            ['code' => 'BK-041', 'title' => 'Sosiologi SMA Kelas X',           'category' => 'Sosial',      'author' => 'Kun Maryati',          'publisher' => 'Erlangga',          'year' => 2017, 'shelf' => 'Rak G-1', 'stock' => 4],
            ['code' => 'BK-042', 'title' => 'Ekonomi SMA Kelas XI',            'category' => 'Sosial',      'author' => 'Alam S.',              'publisher' => 'Erlangga',          'year' => 2017, 'shelf' => 'Rak G-1', 'stock' => 4],
            ['code' => 'BK-043', 'title' => 'Geografi SMA Kelas XII',          'category' => 'Sosial',      'author' => 'Eni Anjayani',         'publisher' => 'Cempaka Putih',     'year' => 2018, 'shelf' => 'Rak G-2', 'stock' => 3],
            ['code' => 'BK-044', 'title' => 'Pengantar Ilmu Ekonomi',          'category' => 'Sosial',      'author' => 'N. Gregory Mankiw',    'publisher' => 'Erlangga',          'year' => 2012, 'shelf' => 'Rak G-2', 'stock' => 2],
            ['code' => 'BK-045', 'title' => 'Psikologi Umum',                  'category' => 'Sosial',      'author' => 'Alex Sobur',           'publisher' => 'Pustaka Setia',     'year' => 2003, 'shelf' => 'Rak G-3', 'stock' => 2],
            // Pengembangan Diri
            ['code' => 'BK-046', 'title' => 'Atomic Habits',                   'category' => 'Pengembangan Diri', 'author' => 'James Clear',     'publisher' => 'Gramedia',          'year' => 2019, 'shelf' => 'Rak H-1', 'stock' => 5],
            ['code' => 'BK-047', 'title' => 'The 7 Habits of Highly Effective People', 'category' => 'Pengembangan Diri', 'author' => 'Stephen Covey', 'publisher' => 'Binarupa Aksara', 'year' => 2013, 'shelf' => 'Rak H-1', 'stock' => 3],
            ['code' => 'BK-048', 'title' => 'Rich Dad Poor Dad',               'category' => 'Pengembangan Diri', 'author' => 'Robert Kiyosaki', 'publisher' => 'Gramedia',          'year' => 2015, 'shelf' => 'Rak H-2', 'stock' => 4],
            ['code' => 'BK-049', 'title' => 'Mindset',                         'category' => 'Pengembangan Diri', 'author' => 'Carol S. Dweck',  'publisher' => 'Gramedia',          'year' => 2017, 'shelf' => 'Rak H-2', 'stock' => 3],
            ['code' => 'BK-050', 'title' => 'Deep Work',                       'category' => 'Pengembangan Diri', 'author' => 'Cal Newport',     'publisher' => 'Gramedia',          'year' => 2018, 'shelf' => 'Rak H-3', 'stock' => 3],
        ];

        foreach ($books as $b) {
            Book::updateOrCreate(
                ['code' => $b['code'], 'school_id' => $schoolId],
                [
                    'school_id'    => $schoolId,
                    'title'        => $b['title'],
                    'category'     => $b['category'],
                    'author'       => $b['author'],
                    'publisher'    => $b['publisher'],
                    'publish_year' => $b['year'],
                    'shelf'        => $b['shelf'],
                    'stock'        => $b['stock'],
                ]
            );
        }
        $this->command->info('✅ 50 buku berhasil ditambahkan.');

        // ── 15 Transaksi Peminjaman (mix status) ──────────────────────────────
        $studentIds = Student::where('school_id', $schoolId)->pluck('id')->toArray();
        $bookIds    = Book::where('school_id', $schoolId)->pluck('id')->toArray();

        $loans = [
            // Dipinjam (aktif)
            ['student' => 0,  'book' => 0,  'loan' => '-5 days',  'due' => '+9 days',  'status' => 'dipinjam'],
            ['student' => 1,  'book' => 1,  'loan' => '-3 days',  'due' => '+11 days', 'status' => 'dipinjam'],
            ['student' => 2,  'book' => 2,  'loan' => '-7 days',  'due' => '+7 days',  'status' => 'dipinjam'],
            ['student' => 3,  'book' => 3,  'loan' => '-2 days',  'due' => '+12 days', 'status' => 'dipinjam'],
            ['student' => 4,  'book' => 4,  'loan' => '-1 days',  'due' => '+13 days', 'status' => 'dipinjam'],
            // Terlambat
            ['student' => 5,  'book' => 5,  'loan' => '-20 days', 'due' => '-6 days',  'status' => 'terlambat'],
            ['student' => 6,  'book' => 6,  'loan' => '-18 days', 'due' => '-4 days',  'status' => 'terlambat'],
            ['student' => 7,  'book' => 7,  'loan' => '-25 days', 'due' => '-11 days', 'status' => 'terlambat'],
            // Dikembalikan
            ['student' => 8,  'book' => 8,  'loan' => '-30 days', 'due' => '-16 days', 'status' => 'dikembalikan', 'return' => '-17 days', 'fine' => 1000],
            ['student' => 9,  'book' => 9,  'loan' => '-25 days', 'due' => '-11 days', 'status' => 'dikembalikan', 'return' => '-10 days', 'fine' => 0],
            ['student' => 10, 'book' => 10, 'loan' => '-40 days', 'due' => '-26 days', 'status' => 'dikembalikan', 'return' => '-20 days', 'fine' => 6000],
            ['student' => 11, 'book' => 11, 'loan' => '-15 days', 'due' => '-1 days',  'status' => 'dikembalikan', 'return' => '-1 days',  'fine' => 0],
            ['student' => 12, 'book' => 12, 'loan' => '-60 days', 'due' => '-46 days', 'status' => 'dikembalikan', 'return' => '-40 days', 'fine' => 6000],
            ['student' => 13, 'book' => 13, 'loan' => '-45 days', 'due' => '-31 days', 'status' => 'dikembalikan', 'return' => '-30 days', 'fine' => 0],
            ['student' => 14, 'book' => 14, 'loan' => '-90 days', 'due' => '-76 days', 'status' => 'dikembalikan', 'return' => '-70 days', 'fine' => 6000],
        ];

        foreach ($loans as $i => $l) {
            $studentId = $studentIds[$l['student']] ?? $studentIds[0];
            $bookId    = $bookIds[$l['book']] ?? $bookIds[0];
            $loanDate  = Carbon::now()->modify($l['loan'])->toDateString();
            $dueDate   = Carbon::now()->modify($l['due'])->toDateString();

            $data = [
                'school_id'  => $schoolId,
                'student_id' => $studentId,
                'book_id'    => $bookId,
                'loan_date'  => $loanDate,
                'due_date'   => $dueDate,
                'status'     => $l['status'],
                'fine'       => $l['fine'] ?? 0,
                'return_date'=> isset($l['return']) ? Carbon::now()->modify($l['return'])->toDateString() : null,
            ];

            Loan::updateOrCreate(
                ['code' => 'DEMO-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT), 'school_id' => $schoolId],
                array_merge($data, ['code' => 'DEMO-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT)])
            );

            // Kurangi stok untuk yang masih dipinjam/terlambat
            if (in_array($l['status'], ['dipinjam', 'terlambat'])) {
                Book::where('id', $bookId)->decrement('stock');
            }
        }
        $this->command->info('✅ 15 transaksi peminjaman berhasil ditambahkan.');
        $this->command->info('');
        $this->command->info('🎉 Demo data selesai! Login dengan admin@eperpus.com / admin123');
    }
}
