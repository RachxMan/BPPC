<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==============================
        // Aktivitas CA - KPI
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

        // Jumlah CA dan Admin aktif
        $jumlahCA = DB::table('users')->where('role', 'CA')->where('status', 'active')->count();
        $jumlahAdmin = DB::table('users')->where('role', 'Admin')->where('status', 'active')->count();

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
        // CA Performance (with search)
        // ==============================
        $searchCA = $request->get('search_ca');
        $caPerformance = DB::table('users')
            ->leftJoin('caring_telepon', 'users.id', '=', 'caring_telepon.user_id')
            ->select(
                'users.nama_lengkap',
                DB::raw('COUNT(caring_telepon.id) as total_caring'),
                DB::raw('SUM(CASE WHEN caring_telepon.status_call IN ("' . implode('","', $contactOptions) . '") THEN 1 ELSE 0 END) as contacted'),
                DB::raw('SUM(CASE WHEN caring_telepon.status_call IN ("' . implode('","', $uncontactOptions) . '") THEN 1 ELSE 0 END) as uncontacted'),
                DB::raw('SUM(CASE WHEN caring_telepon.status_bayar = "paid" THEN 1 ELSE 0 END) as paid'),
                DB::raw('SUM(CASE WHEN caring_telepon.status_bayar = "unpaid" THEN 1 ELSE 0 END) as unpaid')
            )
            ->where('users.role', 'CA')
            ->where('users.status', 'active')
            ->when($searchCA, function ($q) use ($searchCA) {
                $q->where('users.nama_lengkap', 'like', "%{$searchCA}%");
            })
            ->groupBy('users.id', 'users.nama_lengkap')
            ->get();

        // Kinerja 1 bulan (contact per hari)
        $oneMonthAgo = Carbon::now()->subMonth();
        $caMonthlyPerformance = DB::table('caring_telepon')
            ->select(
                'user_id',
                DB::raw('DAY(updated_at) as day'),
                DB::raw('COUNT(*) as contacts_per_day')
            )
            ->where('updated_at', '>=', $oneMonthAgo)
            ->whereNotNull('status_call')
            ->groupBy('user_id', 'day')
            ->get()
            ->groupBy('user_id');

        // ==============================
        // Status Pembayaran (from caring_telepon)
        // ==============================
        $statusBayarRaw = DB::table('caring_telepon')
            ->select('status_bayar', DB::raw('count(*) as total'))
            ->whereNotNull('status_bayar')
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
        // Progress Collection (7 hari)
        // ==============================
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $sevenDaysData = DB::table('caring_telepon')
            ->select(
                DB::raw('DATE(updated_at) as date'),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $contactOptions) . "') THEN 1 ELSE 0 END) as contacted"),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $uncontactOptions) . "') THEN 1 ELSE 0 END) as uncontacted")
            )
            ->where('updated_at', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ==============================
        // Belum Follow-up
        // ==============================
        $belumFollowUp = DB::table('caring_telepon')
            ->leftJoin('users', 'caring_telepon.user_id', '=', 'users.id')
            ->whereNull('caring_telepon.status_call')
            ->orderBy('caring_telepon.created_at', 'desc')
            ->limit(10)
            ->select('caring_telepon.snd', 'caring_telepon.nama_real', 'caring_telepon.status_call', 'caring_telepon.keterangan', 'users.nama_lengkap as ca_name')
            ->get();

        // ==============================
        // Detail Pelanggan - Paket Terlaris
        // ==============================
        $paketTerlaris = DB::table('caring_telepon')
            ->select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // ==============================
        // Data Pelanggan (10 terbaru dari harian)
        // ==============================
        $dataPelanggan = DB::table('harian')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->select('snd','nama','datel','cp','no_hp','status_bayar','payment_date')
            ->get();

        // ==============================
        // Aktivitas Saya (untuk user login, tapi di admin dashboard mungkin tidak perlu, tapi sesuai task)
        // ==============================
        // Jika diperlukan, bisa tambahkan logic untuk user login

        // ==============================
        // Return View
        // ==============================
        return view('admin.dashboard.index', compact(
            'totalPelanggan',
            'totalFollowUp',
            'recentFollowUp',
            'progressCollection',
            'jumlahCA',
            'jumlahAdmin',
            'caPerformance',
            'searchCA',
            'caMonthlyPerformance',
            'statusBayar',
            'weekData',
            'sevenDaysData',
            'belumFollowUp',
            'paketTerlaris',
            'dataPelanggan'
        ));
    }
}
