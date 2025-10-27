<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harian;
use App\Models\CaringTelepon;
use Illuminate\Support\Facades\Auth;

class CaringController extends Controller
{
    /**
     * Tampilkan halaman Caring Telepon untuk user login
     */
    public function telepon(Request $request)
    {
        $user = Auth::user();

        // ===========================
        // Sinkronisasi Harian -> CaringTelepon
        // ===========================
        $harianUsers = Harian::with('assignedUsers')->get();

        foreach ($harianUsers as $h) {
            // 1. Update semua record existing untuk snd ini (update status_bayar, payment_date)
            CaringTelepon::where('snd', $h->snd)
                ->update([
                    'status_bayar'   => $h->status_bayar,
                    'payment_date'   => $h->payment_date,
                    'witel'          => $h->witel,
                    'type'           => $h->type,
                    'produk_bundling'=> $h->produk_bundling,
                    'fi_home'        => $h->fi_home,
                    'account_num'    => $h->account_num,
                    'snd_group'      => $h->snd_group,
                    'nama'           => $h->nama,
                    'cp'             => $h->cp,
                    'datel'          => $h->datel,
                    'no_hp'          => $h->no_hp,
                    'nama_real'      => $h->nama_real,
                    'segmen_real'    => $h->segmen_real,
                ]);

            // 2. Tambahkan record untuk assigned users yang belum ada
            if ($h->assignedUsers && $h->assignedUsers->count() > 0) {
                foreach ($h->assignedUsers as $assignedUser) {
                    CaringTelepon::updateOrCreate(
                        [
                            'snd'     => $h->snd,
                            'user_id' => $assignedUser->id
                        ],
                        [
                            'witel'          => $h->witel,
                            'type'           => $h->type,
                            'produk_bundling'=> $h->produk_bundling,
                            'fi_home'        => $h->fi_home,
                            'account_num'    => $h->account_num,
                            'snd_group'      => $h->snd_group,
                            'nama'           => $h->nama,
                            'cp'             => $h->cp,
                            'datel'          => $h->datel,
                            'payment_date'   => $h->payment_date,
                            'status_bayar'   => $h->status_bayar,
                            'no_hp'          => $h->no_hp,
                            'nama_real'      => $h->nama_real,
                            'segmen_real'    => $h->segmen_real,
                            // Hanya simpan status_call & keterangan jika belum ada
                            'status_call'    => CaringTelepon::where('snd', $h->snd)
                                                           ->where('user_id', $assignedUser->id)
                                                           ->value('status_call') ?? $h->status_call ?? null,
                            'keterangan'     => CaringTelepon::where('snd', $h->snd)
                                                           ->where('user_id', $assignedUser->id)
                                                           ->value('keterangan') ?? $h->keterangan ?? null,
                        ]
                    );
                }
            }
        }

        // ===========================
        // Limit & Search
        // ===========================
        $limit  = $request->get('limit', 10);
        $search = $request->get('search');

        $data = CaringTelepon::with('user')
            ->where('user_id', $user->id)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('snd', 'like', "%{$search}%")
                          ->orWhere('nama', 'like', "%{$search}%")
                          ->orWhere('nama_real', 'like', "%{$search}%")
                          ->orWhere('datel', 'like', "%{$search}%")
                          ->orWhere('cp', 'like', "%{$search}%")
                          ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate($limit)
            ->withQueryString();

        return view('caring.telepon', compact('data', 'limit', 'search'));
    }

    /**
     * Update status call atau keterangan pelanggan via AJAX
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:caring_telepon,id',
            'status_call' => 'nullable|string',
            'keterangan'  => 'nullable|string',
        ]);

        $record = CaringTelepon::findOrFail($request->id);

        $updateData = [];
        if ($request->has('status_call')) {
            $updateData['status_call'] = $request->status_call;
        }
        if ($request->has('keterangan')) {
            $updateData['keterangan'] = $request->keterangan;
        }

        if (!empty($updateData)) {
            $record->update($updateData);
        }

        return response()->json(['success' => true]);
    }
}
