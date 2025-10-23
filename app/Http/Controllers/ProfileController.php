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
        $user = Auth::user();

        $request->validate([
            'first_name'    => 'nullable|string|max:100',
            'last_name'     => 'nullable|string|max:100',
            'email'         => 'required|email',
            'mobile'        => 'nullable|string|max:20',
            'gender'        => 'nullable|string',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // --- Hapus foto jika tombol hapus ditekan ---
        if ($request->has('delete_photo')) {
            if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                Storage::delete('public/profile_photos/' . $user->profile_photo);
            }
            $user->profile_photo = null;
        }

        // --- Upload foto baru ---
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                Storage::delete('public/profile_photos/' . $user->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/profile_photos', $filename);
            $user->profile_photo = $filename;
        }

        // --- Update data lainnya ---
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->mobile     = $request->mobile;
        $user->gender     = $request->gender;
        $user->address    = $request->address;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password'      => 'required|min:8',
            'confirm_password'  => 'required|same:new_password',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
