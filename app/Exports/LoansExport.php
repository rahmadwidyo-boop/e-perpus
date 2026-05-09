<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoansExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Loan::with(['student', 'book']);

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('loan_date', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('loan_date', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['month']) && !empty($this->filters['year'])) {
            $query->whereMonth('loan_date', $this->filters['month'])
                  ->whereYear('loan_date', $this->filters['year']);
        } elseif (!empty($this->filters['year'])) {
            $query->whereYear('loan_date', $this->filters['year']);
        }

        return $query->orderByDesc('loan_date')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Kode Buku',
            'Judul Buku',
            'Tanggal Pinjam',
            'Batas Pengembalian',
            'Tanggal Dikembalikan',
            'Status',
            'Denda (Rp)',
        ];
    }

    protected static int $row = 0;

    public function map($loan): array
    {
        self::$row++;

        // Hitung denda: jika sudah dikembalikan pakai kolom fine,
        // jika masih terlambat hitung dari hari ini
        $fine = $loan->fine;
        if ($loan->status === 'terlambat' && $fine == 0) {
            $fine = $loan->calculated_fine;
        }

        return [
            self::$row,
            $loan->code,
            $loan->student->nis ?? '-',
            $loan->student->name ?? '-',
            $loan->student->class ?? '-',
            $loan->book->code ?? '-',
            $loan->book->title ?? '-',
            $loan->loan_date?->format('d/m/Y'),
            $loan->due_date?->format('d/m/Y'),
            $loan->return_date?->format('d/m/Y') ?? '-',
            ucfirst($loan->status),
            $fine > 0 ? 'Rp ' . number_format($fine, 0, ',', '.') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E3A5F']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
