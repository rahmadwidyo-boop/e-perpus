<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function info()
    {
        $school = Auth::user()->school;

        return view('subscription.info', compact('school'));
    }

    public function paymentForm()
    {
        return view('subscription.payment');
    }

    public function submitPayment(Request $request)
    {
        $request->validate([
            'amount'        => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'bank_name'     => 'required|string|max:100',
            'notes'         => 'nullable|string|max:500',
        ]);

        Payment::create([
            'school_id'     => Auth::user()->school_id,
            'amount'        => $request->amount,
            'transfer_date' => $request->transfer_date,
            'bank_name'     => $request->bank_name,
            'notes'         => $request->notes,
            'status'        => 'pending',
        ]);

        return redirect()->route('subscription.history')
            ->with('success', 'Pengajuan pembayaran berhasil dikirim. Menunggu verifikasi dari admin.');
    }

    public function history()
    {
        $payments = Payment::where('school_id', Auth::user()->school_id)
            ->orderByDesc('created_at')
            ->get();

        return view('subscription.history', compact('payments'));
    }
}
