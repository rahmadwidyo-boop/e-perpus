<?php

namespace App\Http\Controllers;

use App\Exports\LoansExport;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'start_date', 'end_date', 'month', 'year']);

        $query = Loan::with(['student', 'book']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('loan_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('loan_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['month']) && !empty($filters['year'])) {
            $query->whereMonth('loan_date', $filters['month'])
                  ->whereYear('loan_date', $filters['year']);
        } elseif (!empty($filters['year'])) {
            $query->whereYear('loan_date', $filters['year']);
        }

        $loans = $query->orderByDesc('loan_date')->paginate(15)->withQueryString();
        $years = Loan::selectRaw('YEAR(loan_date) as year')->distinct()->orderByDesc('year')->pluck('year');

        // Hitung total denda dari hasil filter (tanpa pagination)
        $summaryQuery = Loan::with(['student', 'book']);
        if (!empty($filters['status'])) $summaryQuery->where('status', $filters['status']);
        if (!empty($filters['start_date'])) $summaryQuery->whereDate('loan_date', '>=', $filters['start_date']);
        if (!empty($filters['end_date'])) $summaryQuery->whereDate('loan_date', '<=', $filters['end_date']);
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $summaryQuery->whereMonth('loan_date', $filters['month'])->whereYear('loan_date', $filters['year']);
        } elseif (!empty($filters['year'])) {
            $summaryQuery->whereYear('loan_date', $filters['year']);
        }

        $totalFine        = $summaryQuery->sum('fine');
        $totalTransactions = $summaryQuery->count();
        $totalReturned    = (clone $summaryQuery)->where('status', 'dikembalikan')->count();

        return view('reports.index', compact('loans', 'filters', 'years', 'totalFine', 'totalTransactions', 'totalReturned'));
    }

    public function export(Request $request)
    {
        $filters  = $request->only(['status', 'start_date', 'end_date', 'month', 'year']);
        $filename = 'laporan-peminjaman-' . Carbon::now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new LoansExport($filters), $filename);
    }
}
