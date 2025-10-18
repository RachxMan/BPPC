<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaAkunController extends Controller
{
    /**
     * Tampilkan halaman kelola akun.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Tambah akun baru (opsional, bisa dikembangkan nanti).
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
        ]);

        // Simulasi penyimpanan (nanti diganti dengan model User)
        return response()->json(['message' => 'Akun berhasil dibuat']);
    }

    /**
     * Hapus akun (opsional untuk pengembangan berikutnya).
     */
    public function destroy($id)
    {
        // Simulasi penghapusan
        return response()->json(['message' => "Akun dengan ID {$id} dihapus"]);
    }
}
