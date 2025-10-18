<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        // nanti bisa ditambahkan auth()->logout();
        return redirect('/login')->with('status', 'Berhasil logout');
    }
}
