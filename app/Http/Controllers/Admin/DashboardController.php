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

        $jumlahCA = DB::table('users')->where('role', 'CA')->where('status', 'active')->count();
        $jumlahAdmin = DB::table('users')->where('role', 'Admin')->where('status', 'active')->count();

        // ==============================
        // Progress Collection (% pelanggan dihubungi)
        // ==============================
        $contactOptions = ['Konfirmasi Pembayaran', 'Tidak Konfirmasi Pembayaran', 'Tutup Telpon'];
        $uncontactOptions = ['RNA', 'Tidak Aktif', 'Nomor Luar Jangkauan', 'Tidak Tersambung'];

        $totalCall = DB::table('caring_telepon')->count();
        $contacted = DB::table('caring_telepon')->whereIn('status_call', $contactOptions)->count();
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

        // ==============================
        // Kinerja 1 bulan terakhir (tanggal real)
        // ==============================
        $oneMonthAgo = Carbon::now()->subMonth();
        $caMonthlyPerformance = DB::table('caring_telepon')
            ->select(
                'user_id',
                DB::raw('DATE(updated_at) as date'),
                DB::raw('COUNT(*) as contacts_per_day')
            )
            ->where('updated_at', '>=', $oneMonthAgo)
            ->whereNotNull('status_call')
            ->groupBy('user_id', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('user_id');

        // ==============================
        // Status Pembayaran
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
        // Progress Collection Mingguan
        // ==============================
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        $weekDataRaw = DB::table('caring_telepon')
            ->select(
                DB::raw('DAYOFWEEK(updated_at) as day'),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $contactOptions) . "') THEN 1 ELSE 0 END) as contacted"),
                DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $uncontactOptions) . "') THEN 1 ELSE 0 END) as uncontacted")
            )
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->get();

        $weekData = collect();
        for ($day = 1; $day <= 7; $day++) {
            $record = $weekDataRaw->firstWhere('day', $day);
            $weekData->push([
                'day' => $day,
                'contacted' => $record->contacted ?? 0,
                'uncontacted' => $record->uncontacted ?? 0,
            ]);
        }

        // ==============================
        // Progress Collection (7 hari terakhir)
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
            ->select('caring_telepon.id', 'caring_telepon.snd', 'caring_telepon.nama', 'caring_telepon.status_call', 'caring_telepon.keterangan', 'users.nama_lengkap as ca_name')
            ->paginate(10);

        // ==============================
        // Paket Terlaris
        // ==============================
        $paketTerlaris = DB::table('caring_telepon')
            ->select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // ==============================
        // Data Pelanggan
        // ==============================
        $dataPelanggan = DB::table('harian')
            ->where(function($query) {
                $query->where('status_bayar', '!=', 'paid')
                      ->orWhereNull('status_bayar');
            })
            ->whereNotNull('status_bayar')
            ->orderBy('created_at', 'desc')
            ->select('snd','nama','datel','cp','no_hp','status_bayar','payment_date')
            ->paginate(10);

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

    public function updateFollowupStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:caring_telepon,id',
            'status' => 'required|in:belum,sudah'
        ]);

        $record = \App\Models\CaringTelepon::findOrFail($request->id);
        if ($request->status === 'belum') {
            $record->update(['status_call' => null]);
        } else {
            $record->update(['status_call' => 'Konfirmasi Pembayaran']);
        }

        return response()->json(['success' => true]);
    }

    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'snd' => 'required|exists:harian,snd',
            'status' => 'required|in:UNPAID,PAID'
        ]);

        $status = strtolower($request->status);
        $updateData = ['status_bayar' => $status];
        if ($status === 'paid') {
            $updateData['payment_date'] = now();
        }

        \App\Models\Harian::where('snd', $request->snd)->update($updateData);
        \App\Models\CaringTelepon::where('snd', $request->snd)->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui menjadi ' . strtoupper($status)
        ]);
    }
}
