<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ✅ Tambahkan ini agar halaman login bisa diakses
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:admin,ca',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan role sesuai
            if ($user->role !== $request->role) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'role' => 'Username tidak terdaftar sebagai role ' . ucfirst($request->role) . '.'
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang ' . strtoupper($user->role) . '!');
        }

        // Login gagal
        return redirect()->route('login')->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
