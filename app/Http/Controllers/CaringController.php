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
        // Sinkronisasi Harian -> CaringTelepon (hanya update existing records, tidak create baru)
        // ===========================
        Harian::chunk(100, function ($harianUsers) {
            foreach ($harianUsers as $h) {
                // Update existing records only - do not create new ones
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
                        'alamat'         => $h->alamat,
                        'no_hp'          => $h->no_hp,
                        'nama_real'      => $h->nama_real,
                        'segmen_real'    => $h->segmen_real,
                    ]);
            }
        });

        // ===========================
        // Limit & Search & Sort
        // ===========================
        $limit  = $request->get('limit', 10);
        $search = $request->get('search');
        $sort   = $request->get('sort');

        $query = CaringTelepon::with('user')
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('snd', 'like', "%{$search}%")
                          ->orWhere('nama', 'like', "%{$search}%")
                          ->orWhere('nama_real', 'like', "%{$search}%")
                          ->orWhere('datel', 'like', "%{$search}%")
                          ->orWhere('alamat', 'like', "%{$search}%")
                          ->orWhere('cp', 'like', "%{$search}%")
                          ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($sort === 'paid', function ($q) {
                $q->orderByRaw("CASE WHEN status_bayar = 'Paid' THEN 1 ELSE 2 END")
                  ->orderBy('created_at', 'asc');
            })
            ->when($sort === 'unpaid', function ($q) {
                $q->orderByRaw("CASE WHEN status_bayar = 'Unpaid' THEN 1 ELSE 2 END")
                  ->orderBy('created_at', 'asc');
            })
            ->when(!$sort, function ($q) {
                $q->orderBy('created_at', 'asc');
            });

        $totalUnique = (clone $query)->distinct('snd')->count('snd');

        $data = $query->paginate($limit)->withQueryString();

        return view('caring.telepon', compact('data', 'limit', 'search', 'sort', 'totalUnique'));
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

        // Validate that the record exists and belongs to the current user (if not admin)
        if (auth()->user()->role !== 'admin' && $record->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $updateData = [];
        if ($request->has('status_call')) {
            $updateData['status_call'] = $request->status_call;
            $updateData['contact_date'] = now()->toDateString();
        }
        if ($request->has('keterangan')) {
            $updateData['keterangan'] = $request->keterangan;
        }

        if (!empty($updateData)) {
            $record->update($updateData);

            // Automate status_bayar based on status_call
            if ($request->status_call === 'Konfirmasi Pembayaran') {
                $record->update([
                    'status_bayar' => 'Paid',
                    'payment_date' => now()->toDateString(),
                ]);
                // Sync to harian table
                \App\Models\Harian::where('snd', $record->snd)->update([
                    'status_bayar' => 'Paid',
                    'payment_date' => now()->toDateString(),
                ]);
            } else {
                $record->update([
                    'status_bayar' => 'Unpaid',
                    'payment_date' => null,
                ]);
                // Sync to harian table
                \App\Models\Harian::where('snd', $record->snd)->update([
                    'status_bayar' => 'Unpaid',
                    'payment_date' => null,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
