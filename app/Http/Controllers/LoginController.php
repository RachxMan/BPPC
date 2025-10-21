<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login sementara (tanpa validasi)
    public function login(Request $request)
    {
        // Langsung redirect ke dashboard tanpa cek credentials
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        // Hanya redirect ke login
        return redirect()->route('login');
    }
}