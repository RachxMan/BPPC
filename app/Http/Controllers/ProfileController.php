<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil & pengaturan.
     */
    public function index()
    {
        $user = Auth::user();
        return view('profil_pengaturan', compact('user'));
    }

    /**
     * Update data profil & foto.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'mobile' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female',
            'address' => 'nullable|string|max:500',
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'mobile.max' => 'Nomor telepon maksimal 20 karakter.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'address.max' => 'Alamat maksimal 500 karakter.',
        ]);

        $user = auth()->user();
        $user->nama_lengkap = $validated['first_name'] . ' ' . $validated['last_name'];
        $user->email = $validated['email'];
        $user->no_telp = $validated['mobile'];
        $user->jenis_kelamin = $validated['gender'];
        $user->alamat = $validated['address'];
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
