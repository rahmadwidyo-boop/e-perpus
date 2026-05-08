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

        return view('reports.index', compact('loans', 'filters', 'years'));
    }

    public function export(Request $request)
    {
        $filters  = $request->only(['status', 'start_date', 'end_date', 'month', 'year']);
        $filename = 'laporan-peminjaman-' . Carbon::now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new LoansExport($filters), $filename);
    }
}
