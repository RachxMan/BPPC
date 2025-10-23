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
        try {
            $user = auth()->user();

            // Handle profile photo upload only
            if ($request->hasFile('profile_photo')) {
                $validated = $request->validate([
                    'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ], [
                    'profile_photo.required' => 'File foto profil wajib dipilih.',
                    'profile_photo.image' => 'File harus berupa gambar.',
                    'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                    'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
                ]);

                // Delete old photo if exists
                if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile_photos/' . $user->profile_photo);
                }

                $file = $request->file('profile_photo');
                $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profile_photos', $filename);
                $user->profile_photo = $filename;
                $user->save();

                if ($request->ajax()) {
                    $photoUrl = asset('storage/profile_photos/' . $user->profile_photo);
                    return response()->json(['success' => true, 'profile_photo_url' => $photoUrl]);
                }
            }

            // Handle delete photo only
            if ($request->input('delete_photo')) {
                if ($user->profile_photo && Storage::exists('public/profile_photos/' . $user->profile_photo)) {
                    Storage::delete('public/profile_photos/' . $user->profile_photo);
                }
                $user->profile_photo = null;
                $user->save();

                if ($request->ajax()) {
                    $photoUrl = asset('img/1594252-200.png');
                    return response()->json(['success' => true, 'profile_photo_url' => $photoUrl]);
                }
            }

            // Handle profile data update
            if ($request->has(['nama_lengkap', 'username', 'email']) || $request->hasFile('profile_photo') || $request->input('delete_photo')) {
                if ($request->has(['nama_lengkap', 'username', 'email'])) {
                    $validated = $request->validate([
                        'nama_lengkap' => 'required|string|max:255',
                        'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
                        'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
                        'no_telp' => 'nullable|string|max:20',
                        'alamat' => 'nullable|string|max:500',
                    ], [
                        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                        'username.required' => 'Username wajib diisi.',
                        'username.unique' => 'Username sudah digunakan.',
                        'email.required' => 'Email wajib diisi.',
                        'email.email' => 'Format email tidak valid.',
                        'email.unique' => 'Email sudah digunakan.',
                        'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
                        'alamat.max' => 'Alamat maksimal 500 karakter.',
                    ]);
                }

                if (isset($validated)) {
                    $user->nama_lengkap = $validated['nama_lengkap'];
                    $user->username = $validated['username'];
                    $user->email = $validated['email'];
                    $user->no_telp = $validated['no_telp'];
                    $user->alamat = $validated['alamat'];
                }
                $user->save();
            }

            if ($request->ajax()) {
                $photoUrl = $user->profile_photo ? asset('storage/profile_photos/' . $user->profile_photo) : asset('img/1594252-200.png');
                return response()->json(['success' => true, 'profile_photo_url' => $photoUrl]);
            }

            return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()], 500);
            }
            throw $e;
        }
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui!');
    }
}
