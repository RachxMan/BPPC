<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profil_pengaturan');
    }

    public function update(Request $request)
    {
        // Simulasi penyimpanan data sementara (belum pakai database)
        $data = $request->only(['first_name', 'last_name', 'email', 'mobile', 'gender', 'address']);
        
        // Bisa tambahkan log atau dd() untuk debugging
        // dd($data);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
