<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentsImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;

    // Daftar kelas valid
    public static array $validClasses = [
        'X 1','X 2','X 3','X 4','X 5','X 6','X 7',
        'XI 1','XI 2','XI 3','XI 4','XI 5','XI 6','XI 7',
        'XII 1','XII 2','XII 3','XII 4','XII 5','XII 6','XII 7',
    ];

    public int $imported = 0;
    public int $skipped  = 0;

    public function model(array $row): ?Student
    {
        // Kolom yang diharapkan: nis, nama, kelas, no_hp (opsional), alamat (opsional)
        $nis   = trim($row['nis'] ?? $row['no_induk'] ?? '');
        $name  = trim($row['nama'] ?? $row['nama_siswa'] ?? '');
        $class = trim($row['kelas'] ?? '');

        // Skip baris kosong
        if (empty($nis) || empty($name) || empty($class)) {
            $this->skipped++;
            return null;
        }

        // Normalisasi kelas: "X1" → "X 1", "xi 3" → "XI 3"
        $class = $this->normalizeClass($class);

        // Skip jika kelas tidak valid
        if (!in_array($class, self::$validClasses)) {
            $this->skipped++;
            return null;
        }

        // Skip jika NIS sudah ada (update jika mau, tapi kita skip saja)
        if (Student::where('nis', $nis)->exists()) {
            $this->skipped++;
            return null;
        }

        $this->imported++;

        return new Student([
            'nis'     => $nis,
            'name'    => $name,
            'class'   => $class,
            'phone'   => trim($row['no_hp'] ?? $row['telepon'] ?? '') ?: null,
            'address' => trim($row['alamat'] ?? '') ?: null,
        ]);
    }

    private function normalizeClass(string $class): string
    {
        // Hapus spasi berlebih, uppercase
        $class = strtoupper(trim(preg_replace('/\s+/', ' ', $class)));

        // "X1" → "X 1", "XI3" → "XI 3", "XII7" → "XII 7"
        $class = preg_replace('/^(XII|XI|X)(\d)$/', '$1 $2', $class);

        return $class;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
