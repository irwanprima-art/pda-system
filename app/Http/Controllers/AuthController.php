<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // =========================
    // FORM LOGIN
    // =========================
    public function showLogin()
    {
        return view('auth.login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // =========================
            // REDIRECT SESUAI ROLE
            // =========================

            // ADMIN → HOME (dashboard utama)
            if ($user->role === 'admin') {
                return redirect('/home');
            }

            // OPERATOR → halaman operator
            if ($user->role === 'operator') {
                return redirect('/operator');
            }

            // JAGA-JAGA ROLE TIDAK VALID
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'Role tidak dikenali.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
