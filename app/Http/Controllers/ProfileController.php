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
            $user = Auth::user();

            // Validasi data umum profil
            $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'no_telp' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:500',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'profile_photo.image' => 'File harus berupa gambar.',
                'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
            ]);

            // Update data umum
            $user->fill([
                'nama_lengkap' => $validated['nama_lengkap'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'no_telp' => $validated['no_telp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            // === Upload foto baru ===
            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama jika ada
                if ($user->profile_photo && Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                    Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
                }

                $file = $request->file('profile_photo');
                $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '_', $file->getClientOriginalName());
                $file->storeAs('profile_photos', $filename, 'public');

                $user->profile_photo = $filename;
            }

            // === Hapus foto jika diminta ===
            if ($request->input('delete_photo')) {
                if ($user->profile_photo && Storage::disk('public')->exists('profile_photos/' . $user->profile_photo)) {
                    Storage::disk('public')->delete('profile_photos/' . $user->profile_photo);
                }
                $user->profile_photo = null;
            }

            $user->save();

            // === Balasan untuk AJAX ===
            if ($request->ajax()) {
                $photoUrl = $user->profile_photo
                    ? asset('storage/profile_photos/' . $user->profile_photo)
                    : asset('img/1594252-200.png');

                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui!',
                    'profile_photo_url' => $photoUrl
                ]);
            }

            return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
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

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui!');
    }
}
