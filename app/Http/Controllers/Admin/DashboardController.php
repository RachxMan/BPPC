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
    $searchCA = $request->get('search_ca', '');
    $weekOffset = (int) $request->get('week_offset', 0); // 0 = current week, -1 = previous, 1 = next

    // ==============================
    // KPI Section
    // ==============================
    $totalPelanggan = DB::table('harian')->count();
    $totalFollowUp = DB::table('caring_telepon')->where('follow_up_count', '>', 0)->count();
    $today = Carbon::today();

    $recentFollowUp = DB::table('caring_telepon')
        ->whereDate('contact_date', $today)
        ->where('follow_up_count', '>', 0)
        ->count();

    $jumlahCA = DB::table('users')->where('role', 'ca')->where('status', 'Aktif')->count();
    $jumlahAdmin = DB::table('users')->where('role', 'admin')->where('status', 'Aktif')->count();

    // Get all active users (CA and Admin) for performance chart, sorted by nama_lengkap for consistent color assignment
    $activeUsers = DB::table('users')->whereIn('role', ['ca', 'admin'])->where('status', 'Aktif')->select('id', 'nama_lengkap')->orderBy('nama_lengkap')->get();

    // ==============================
    // Progress Collection
    // ==============================
    $contactOptions = ['Konfirmasi Pembayaran', 'Tidak Konfirmasi Pembayaran', 'Tutup Telpon'];
    $uncontactOptions = ['RNA', 'Tidak Aktif', 'Nomor Luar Jangkauan', 'Tidak Tersambung'];
    $totalCall = DB::table('caring_telepon')->count();
    $contacted = DB::table('caring_telepon')->whereIn('status_call', $contactOptions)->whereNotNull('contact_date')->count();
    $progressCollection = $totalCall > 0 ? round(($contacted / $totalCall) * 100, 2) : 0;

    // ==============================
    // Filter bulan (default: bulan sekarang)
    // ==============================
    $selectedMonth = $request->get('month', now()->format('Y-m'));
    $startOfMonth = Carbon::parse($selectedMonth . '-01')->startOfMonth();
    $endOfMonth = Carbon::parse($selectedMonth . '-01')->endOfMonth();

    // ==============================
    // Kinerja CA per tanggal dalam bulan terpilih
    // ==============================
    $caDailyPerformance = DB::table('caring_telepon')
        ->select(
            'user_id',
            DB::raw('DATE(contact_date) as date'),
            DB::raw('SUM(follow_up_count) as contacts_per_day')
        )
        ->whereBetween('contact_date', [$startOfMonth, $endOfMonth])
        ->where('follow_up_count', '>', 0)
        ->whereNotNull('contact_date')
        ->whereIn('user_id', $activeUsers->pluck('id'))
        ->groupBy('user_id', 'date')
        ->orderBy('date')
        ->get()
        ->groupBy('user_id');

    // ==============================
    // Status Pembayaran
    // ==============================
    $statusBayarRaw = DB::table('harian')
        ->select('status_bayar', DB::raw('count(distinct snd) as total'))
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
    // Progress Mingguan with offset
    // ==============================
    $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY)->addWeeks($weekOffset);
    $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SATURDAY)->addWeeks($weekOffset);

    $weekDataRaw = DB::table('caring_telepon')
        ->select(
            DB::raw('DAYOFWEEK(contact_date) as day'),
            DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $contactOptions) . "') THEN 1 ELSE 0 END) as contacted"),
            DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $uncontactOptions) . "') THEN 1 ELSE 0 END) as uncontacted"),
            DB::raw("SUM(CASE WHEN status_call IN ('" . implode("','", $contactOptions) . "') AND LOWER(status_bayar) = 'paid' THEN 1 ELSE 0 END) as paid")
        )
        ->whereBetween('contact_date', [$startOfWeek, $endOfWeek])
        ->whereNotNull('contact_date')
        ->groupBy('day')
        ->get();

    $weekData = collect();
    for ($day = 1; $day <= 7; $day++) {
        $record = $weekDataRaw->firstWhere('day', $day);
        $weekData->push([
            'day' => $day,
            'contacted' => $record->contacted ?? 0,
            'uncontacted' => $record->uncontacted ?? 0,
            'paid' => $record->paid ?? 0,
        ]);
    }

    // Week label and period
    $weekLabel = $weekOffset == 0 ? 'Minggu Ini' : ($weekOffset < 0 ? 'Minggu Lalu' : 'Minggu Depan');
    $weekPeriod = 'Periode: ' . $startOfWeek->format('d M Y') . ' – ' . $endOfWeek->format('d M Y');



    // ==============================
    // Data Pelanggan, Paket, dsb
    // ==============================


    $filterUserPelanggan = $request->get('filter_user_pelanggan');

    $dataPelangganQuery = DB::table('harian')
        ->leftJoin('caring_telepon', 'harian.snd', '=', 'caring_telepon.snd')
        ->leftJoin('users', 'caring_telepon.user_id', '=', 'users.id')
        ->where(function($q) {
            $q->where('harian.status_bayar', '!=', 'paid')->orWhereNull('harian.status_bayar');
        })
        ->whereNotNull('harian.status_bayar')
        ->where(function ($q) {
            $q->where('users.status', 'Aktif')
              ->orWhereNull('caring_telepon.user_id');
        })
        ->orderBy('harian.created_at', 'desc')
        ->select('harian.snd','harian.nama','harian.datel','harian.alamat','harian.cp','harian.no_hp','harian.status_bayar','harian.payment_date', 'users.nama_lengkap as ca_name');

    // Jika role CA, hanya tampilkan data yang assigned ke CA tersebut
    if (auth()->user()->role === 'ca') {
        $dataPelangganQuery->where('caring_telepon.user_id', auth()->id());
    } elseif ($filterUserPelanggan) {
        $dataPelangganQuery->where('caring_telepon.user_id', $filterUserPelanggan);
    }

    $dataPelangganQueryClone = clone $dataPelangganQuery;
    $totalDataPelanggan = $dataPelangganQueryClone->distinct('harian.snd')->count('harian.snd');

    $dataPelanggan = $dataPelangganQuery->paginate(10, ['*'], 'pelanggan_page');

    $filterUserBelum = $request->get('filter_user_belum');
    $filterFollowUp = $request->get('filter_followup');

    $belumFollowUpQuery = DB::table('caring_telepon')
        ->leftJoin('users', 'caring_telepon.user_id', '=', 'users.id')
        ->where('caring_telepon.status_bayar', 'Unpaid') // Only show unpaid customers
        ->where(function ($q) {
            $q->where('users.status', 'Aktif')
              ->orWhereNull('caring_telepon.user_id');
        })
        ->orderBy('caring_telepon.created_at', 'desc')
        ->select('caring_telepon.id', 'caring_telepon.snd', 'caring_telepon.nama',
                 'caring_telepon.status_call', 'caring_telepon.keterangan', 'caring_telepon.contact_date',
                 'users.nama_lengkap as ca_name');

    // Filter follow-up status
    if ($filterFollowUp === 'sudah') {
        $belumFollowUpQuery->whereNotNull('caring_telepon.contact_date');
    } elseif ($filterFollowUp === 'belum') {
        $belumFollowUpQuery->whereNull('caring_telepon.contact_date');
    }

    // Jika role CA, hanya tampilkan data yang assigned ke CA tersebut
    if (auth()->user()->role === 'ca') {
        $belumFollowUpQuery->where('caring_telepon.user_id', auth()->id());
    } elseif ($filterUserBelum) {
        $belumFollowUpQuery->where('caring_telepon.user_id', $filterUserBelum);
    }

    $belumFollowUpQueryClone = clone $belumFollowUpQuery;
    $totalBelumFollowUp = $belumFollowUpQueryClone->distinct('caring_telepon.snd')->count('caring_telepon.snd');

    $belumFollowUp = $belumFollowUpQuery->paginate(10, ['*'], 'belum_page');


    return view('admin.dashboard.index', compact(
        'totalPelanggan',
        'totalFollowUp',
        'recentFollowUp',
        'progressCollection',
        'jumlahCA',
        'jumlahAdmin',
        'caDailyPerformance',
        'statusBayar',
        'weekData',
        'weekLabel',
        'weekPeriod',
        'weekOffset',
        'dataPelanggan',
        'selectedMonth',
        'searchCA',
        'belumFollowUp',
        'activeUsers',
        'totalBelumFollowUp',
        'totalDataPelanggan'
    ));
}

    // Removed updateFollowupStatus method as status updates should only be done in Caring Telepon

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
