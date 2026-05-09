<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'slug'        => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9\-]+$/',
                'unique:schools,slug',
            ],
            'admin_name'  => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
        ], [
            'school_name.required' => 'Nama sekolah wajib diisi.',
            'slug.required'        => 'Slug URL wajib diisi.',
            'slug.regex'           => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
            'slug.unique'          => 'Slug sudah digunakan sekolah lain.',
            'admin_name.required'  => 'Nama admin wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.unique'         => 'Email sudah terdaftar.',
            'password.required'    => 'Password wajib diisi.',
            'password.min'         => 'Password minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $school = School::create([
            'name'                => $request->school_name,
            'slug'                => $request->slug,
            'admin_name'          => $request->admin_name,
            'subscription_status' => 'trial',
            'trial_ends_at'       => Carbon::now()->addDays(30),
        ]);

        $user = User::create([
            'name'      => $request->admin_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'school_admin',
            'school_id' => $school->id,
        ]);

        // Login dulu agar bisa kirim email verifikasi
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di E-Perpustakaan.');
    }
}
