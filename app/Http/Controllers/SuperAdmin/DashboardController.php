<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\School;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSchools = School::count();

        $schoolsByStatus = School::selectRaw('subscription_status, COUNT(*) as total')
            ->groupBy('subscription_status')
            ->pluck('total', 'subscription_status');

        $totalRevenue = Payment::where('status', 'approved')->sum('amount');

        return view('superadmin.dashboard', compact(
            'totalSchools',
            'schoolsByStatus',
            'totalRevenue'
        ));
    }
}
