<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cek rate limit — maksimal 5 percobaan per menit per email+IP
        $this->ensureIsNotRateLimited($request);

        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            // Login berhasil — reset rate limiter
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            // Cek verifikasi email (kecuali super_admin)
            if (!Auth::user()->isSuperAdmin() && !Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if (Auth::user()->isSuperAdmin()) {
                return redirect()->intended(route('superadmin.dashboard'));
            }
            return redirect()->intended(route('dashboard'));
        }

        // Login gagal — tambah hitungan rate limiter
        RateLimiter::hit($this->throttleKey($request), 60);

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Pastikan request tidak melebihi batas percobaan login.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    /**
     * Key unik per email + IP address.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower($request->input('email')) . '|' . $request->ip()
        );
    }
}
