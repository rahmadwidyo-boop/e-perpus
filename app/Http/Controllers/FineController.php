<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Carbon\Carbon;

class FineController extends Controller
{
    public function index()
    {
        // Update status terlambat otomatis
        Loan::where('status', 'dipinjam')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        // Denda yang belum dibayar (masih terlambat, belum dikembalikan)
        $unpaidFines = Loan::with(['student', 'book'])
            ->where('status', 'terlambat')
            ->orderBy('due_date')
            ->get()
            ->map(function ($loan) {
                $loan->current_fine = $loan->calculated_fine;
                $loan->late_days_count = $loan->late_days;
                return $loan;
            });

        // Denda yang sudah dibayar (sudah dikembalikan dan ada denda)
        $paidFines = Loan::with(['student', 'book'])
            ->where('status', 'dikembalikan')
            ->where('fine', '>', 0)
            ->orderByDesc('return_date')
            ->get();

        // Ringkasan
        $totalUnpaid   = $unpaidFines->sum('current_fine');
        $totalPaid     = $paidFines->sum('fine');
        $totalAll      = $totalUnpaid + $totalPaid;
        $countUnpaid   = $unpaidFines->count();
        $countPaid     = $paidFines->count();

        return view('fines.index', compact(
            'unpaidFines', 'paidFines',
            'totalUnpaid', 'totalPaid', 'totalAll',
            'countUnpaid', 'countPaid'
        ));
    }
}
