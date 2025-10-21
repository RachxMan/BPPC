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
        $data = $request->only(['first_name', 'last_name', 'email', 'mobile', 'gender', 'address']);
        

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
