<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('landing');
    }
}
