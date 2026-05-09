<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks    = Book::sum('stock');
        $totalStudents = Student::count();
        $totalLoaned   = Loan::where('status', 'dipinjam')->count();

        // Update status terlambat otomatis
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $totalLate = Loan::where('status', 'terlambat')->count();

        // Total denda yang sudah dibayar (status dikembalikan dan ada denda)
        $totalFineCollected = Loan::where('status', 'dikembalikan')
            ->where('fine', '>', 0)
            ->sum('fine');

        // Total denda yang masih berjalan (belum dikembalikan, terlambat)
        $totalFinePending = Loan::where('status', 'terlambat')
            ->get()
            ->sum('calculated_fine');

        // Daftar siswa yang sedang meminjam
        $activeLoans = Loan::with(['student', 'book'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->orderBy('due_date')
            ->get();

        // Daftar keterlambatan
        $lateLoans = Loan::with(['student', 'book'])
            ->where('status', 'terlambat')
            ->orderBy('due_date')
            ->get();

        // Statistik peminjaman per bulan (12 bulan terakhir)
        $monthlyStats = Loan::select(
                DB::raw('MONTH(loan_date) as month'),
                DB::raw('YEAR(loan_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('loan_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format untuk chart
        $chartLabels = [];
        $chartData   = [];
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push(['year' => $date->year, 'month' => $date->month, 'label' => $date->translatedFormat('M Y')]);
        }

        foreach ($months as $m) {
            $chartLabels[] = $m['label'];
            $found = $monthlyStats->first(fn($s) => $s->month == $m['month'] && $s->year == $m['year']);
            $chartData[] = $found ? $found->total : 0;
        }

        return view('dashboard', compact(
            'totalBooks', 'totalStudents', 'totalLoaned', 'totalLate',
            'totalFineCollected', 'totalFinePending',
            'activeLoans', 'lateLoans', 'chartLabels', 'chartData'
        ));
    }
}
