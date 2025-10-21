<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaAkunController extends Controller
{
    // Dummy data pengguna
    private $users = [
        [
            'nama' => 'Syarifuddin',
            'email' => 'udin@gmail.com',
            'username' => 'taylorsweepp21',
            'role' => 'Administrator',
            'status' => 'Aktif'
        ],
        [
            'nama' => 'Ji Chang Wook',
            'email' => 'wookiee@gmail.com',
            'username' => 'wookieee',
            'role' => 'Collection Agent',
            'status' => 'Aktif'
        ],
        [
            'nama' => 'Isnin bin Khamis',
            'email' => 'tokdalanggans2000@gmail.com',
            'username' => 'kampungdurianrunutuh',
            'role' => 'Collection Agent',
            'status' => 'Nonaktif'
        ]
    ];

    /**
     * Tampilkan halaman Kelola Akun
     */
    public function index(Request $request)
    {
        $users = $this->users;

        // Ambil tab aktif dari session atau query param (opsional)
        $activeTab = $request->query('tab', $request->session()->get('active_tab', 'daftar'));

        // Ambil pesan sukses (jika ada)
        $successMessage = $request->session()->get('success');

        return view('kelola-akun', compact('users', 'activeTab', 'successMessage'));
    }

    /**
     * Simulasi penyimpanan akun baru
     */
    public function store(Request $request)
    {
        // Ambil data dari form
        $dataBaru = $request->all();

        // Biasanya disimpan ke database
        // User::create([...]);

        // Redirect ke halaman kelola akun dengan tab registrasi tetap aktif
        return redirect()->route('user.index', ['tab' => 'registrasi'])
                         ->with('success', 'Akun baru berhasil ditambahkan!');
    }

    /**
     * Switch tab manual via URL (opsional)
     */
    public function switchTab($tab)
    {
        // Validasi tab
        if (!in_array($tab, ['daftar', 'registrasi'])) {
            $tab = 'daftar';
        }

        $users = $this->users;

        // Gunakan query param agar konsisten dengan index()
        return redirect()->route('user.index', ['tab' => $tab]);
    }
}
