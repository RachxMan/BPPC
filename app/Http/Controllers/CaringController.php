<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaringTelepon;
use App\Models\Harian;
use Illuminate\Support\Facades\Auth;

class CaringController extends Controller
{
    /**
     * Tampilkan halaman Caring Telepon untuk CA/Admin yang login
     */
    public function telepon(Request $request)
    {
        $user = Auth::user();

        // Ambil semua data Harian
        $harianUsers = Harian::all();

        // Sinkronisasi data ke tabel CaringTelepon
        foreach ($harianUsers as $h) {
            CaringTelepon::updateOrCreate(
                [
                    'snd' => $h->snd,
                    'user_id' => $user->id
                ],
                [
                    'witel' => $h->witel,
                    'type' => $h->type,
                    'produk_bundling' => $h->produk_bundling,
                    'fi_home' => $h->fi_home,
                    'account_num' => $h->account_num,
                    'snd_group' => $h->snd_group,
                    'nama' => $h->nama,
                    'cp' => $h->cp,
                    'datel' => $h->datel,
                    'payment_date' => $h->payment_date,
                    'status_bayar' => $h->status_bayar,
                    'no_hp' => $h->no_hp,
                    'nama_real' => $h->nama_real,
                    'segmen_real' => $h->segmen_real,
                ]
            );
        }

        // Ambil limit per halaman
        $limit = $request->get('limit', 10);

        // Ambil keyword search
        $search = $request->get('search');

        // Ambil data CaringTelepon untuk user login dengan search
        $data = CaringTelepon::with('user')
            ->where('user_id', $user->id)
            ->when($search, function($q) use ($search) {
                $q->where(function($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                          ->orWhere('nama_real', 'like', "%{$search}%")
                          ->orWhere('snd', 'like', "%{$search}%")
                          ->orWhere('account_num', 'like', "%{$search}%")
                          ->orWhere('cp', 'like', "%{$search}%")
                          ->orWhere('datel', 'like', "%{$search}%")
                          ->orWhere('produk_bundling', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate($limit)
            ->withQueryString(); // agar query search & limit tetap di pagination

        return view('caring.telepon', compact('data', 'limit', 'search'));
    }

    /**
     * Update status call atau keterangan pelanggan
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:caring_telepon,id',
            'status_call' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $record = CaringTelepon::findOrFail($request->id);

        // Update kolom status_call dan/atau keterangan jika ada
        $record->update(array_filter([
            'status_call' => $request->status_call,
            'keterangan' => $request->keterangan
        ], fn($value) => !is_null($value)));

        return response()->json(['success' => true]);
    }
}
