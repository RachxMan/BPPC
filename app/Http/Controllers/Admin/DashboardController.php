<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ==============================
        // KPI
        // ==============================
        $totalPelanggan = DB::table('harian')->count();

        $totalFollowUp = DB::table('caring_telepon')
            ->whereNotNull('status_call')
            ->count();

        $today = Carbon::today();
        $recentFollowUp = DB::table('caring_telepon')
            ->whereDate('updated_at', $today)
            ->whereNotNull('status_call')
            ->count();

        // ==============================
        // Progress Collection (% pelanggan dihubungi)
        // ==============================
        $contactOptions = ['Konfirmasi Pembayaran', 'Tidak Konfirmasi Pembayaran', 'Tutup Telpon'];
        $uncontactOptions = ['RNA', 'Tidak Aktif', 'Nomor Luar Jangkauan', 'Tidak Tersambung'];

        $totalCall = DB::table('caring_telepon')->count();
        $contacted = DB::table('caring_telepon')
            ->whereIn('status_call', $contactOptions)
            ->count();
        $progressCollection = $totalCall > 0 ? round(($contacted / $totalCall) * 100, 2) : 0;

        // ==============================
        // Status Pembayaran
        // ==============================
        $statusBayarRaw = DB::table('harian')
            ->select('status_bayar', DB::raw('count(*) as total'))
            ->groupBy('status_bayar')
            ->get();

        $statusBayar = ['paid' => 0, 'unpaid' => 0];
        foreach ($statusBayarRaw as $row) {
            $status = strtolower($row->status_bayar);
            if ($status === 'paid') $statusBayar['paid'] = $row->total;
            if ($status === 'unpaid') $statusBayar['unpaid'] = $row->total;
        }

        // ==============================
        // Progress Collection Mingguan (Senin-Jumat)
        // ==============================
        $startOfWeek = Carbon::now()->startOfWeek(); // Senin
        $endOfWeek   = Carbon::now()->startOfWeek()->addDays(4)->endOfDay(); // Jumat

        $weekDataRaw = DB::table('caring_telepon')
            ->select(
                DB::raw('DAYOFWEEK(updated_at) as day'),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $contactOptions) . "') THEN 1 ELSE 0 END) as contacted"),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $uncontactOptions) . "') THEN 1 ELSE 0 END) as uncontacted")
            )
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get();

        // Pastikan array Senin-Jumat lengkap
        $weekData = collect();
        for ($day = 2; $day <= 6; $day++) { // 2=Senin, 6=Jumat
            $record = $weekDataRaw->firstWhere('day', $day);
            $weekData->push([
                'day' => $day,
                'contacted' => $record->contacted ?? 0,
                'uncontacted' => $record->uncontacted ?? 0,
            ]);
        }

        // ==============================
        // Belum Follow-up
        // ==============================
        // Revisi: status_call masih kosong/null
        $belumFollowUp = DB::table('caring_telepon')
            ->whereNull('status_call')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->select('snd', 'nama', 'status_call', 'keterangan')
            ->get();

        // ==============================
        // Data Pelanggan
        // ==============================
        $dataPelanggan = DB::table('caring_telepon')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->select('snd','nama','datel','cp','no_hp','status_bayar','payment_date')
            ->get();

        // ==============================
        // Return View
        // ==============================
        return view('admin.dashboard.index', compact(
            'totalPelanggan',
            'totalFollowUp',
            'recentFollowUp',
            'progressCollection',
            'statusBayar',
            'weekData',
            'belumFollowUp',
            'dataPelanggan'
        ));
    }
}
