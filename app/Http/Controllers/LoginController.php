<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

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

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role !== $request->role) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['role' => 'Username tidak terdaftar sebagai role ' . ($request->role === 'admin' ? 'Admin' : 'CA') . '.']);
            }

            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Selamat datang Admin!');
            } elseif ($user->role === 'ca') {
                return redirect()->route('dashboard')->with('success', 'Selamat datang CA!');
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors(['role' => 'Role tidak dikenal.']);
            }
        }

        return back()->withErrors([
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
