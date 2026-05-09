<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('school')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return view('superadmin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($payment) {
            $now = now();

            $payment->update([
                'status'      => 'approved',
                'reviewed_at' => $now,
                'reviewed_by' => Auth::id(),
            ]);

            $payment->school->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $now->copy()->addYear(),
            ]);
        });

        return redirect()->route('superadmin.payments.index')
            ->with('success', 'Pembayaran berhasil disetujui. Langganan sekolah telah diaktifkan.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_at'      => now(),
            'reviewed_by'      => Auth::id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }
}
