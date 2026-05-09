<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::withCount(['payments as pending_payments_count' => function ($query) {
            $query->where('status', 'pending');
        }])->orderByDesc('created_at')->get();

        return view('superadmin.schools.index', compact('schools'));
    }

    public function updateStatus(Request $request, School $school)
    {
        $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $school->update([
            'subscription_status' => $request->status,
        ]);

        $statusLabel = $request->status === 'active' ? 'diaktifkan' : 'disuspend';

        return back()->with('success', "Sekolah \"{$school->name}\" berhasil {$statusLabel}.");
    }
}
