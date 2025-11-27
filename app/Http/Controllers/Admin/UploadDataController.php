<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harian;
use App\Models\Bulanan;
use App\Models\User;
use App\Models\CaringTelepon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HarianImport;
use App\Imports\BulananImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UploadDataController extends Controller
{
    // ==============================
    // Halaman Index Upload
    // ==============================
    public function index()
    {
        $uploads = DB::table('uploaded_files')->orderBy('created_at', 'desc')->get();
        return view('admin.upload_data.upload_data', compact('uploads'));
    }

    public function harian()
    {
        $lastFile = DB::table('uploaded_files')->where('type', 'harian')->latest('id')->first();
        return view('admin.upload_data.harian', [
            'lastFileId' => $lastFile->id ?? null,
            'uploaded_file_name' => $lastFile->filename ?? null
        ]);
    }

    public function bulanan()
    {
        $lastFile = DB::table('uploaded_files')->where('type', 'bulanan')->latest('id')->first();
        return view('admin.upload_data.bulanan', [
            'lastFileId' => $lastFile->id ?? null,
            'uploaded_file_name' => $lastFile->filename ?? null
        ]);
    }

    // ==============================
    // Import File Excel
    // ==============================
    public function importHarian(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $fileId = $this->storeFile($request->file('file'), 'harian');

        $result = $this->saveRowsToDatabase($fileId, Harian::class, HarianImport::class);
        $count = $result['processed'];
        $duplicates = $result['duplicates'];

        // If there are duplicates, redirect to modal confirmation
        if (!empty($duplicates)) {
            return redirect()->route('upload.harian')
                ->with('duplicates', $duplicates)
                ->with('file_id', $fileId)
                ->with('uploaded_file_name', $request->file('file')->getClientOriginalName());
        }

        $this->combineCA($request);

        $message = "File Harian berhasil diupload! {$count} data baru disimpan ke database dan didistribusikan ke CA/Admin.";

        return redirect()->route('upload.harian')
            ->with('success', $message)
            ->with('uploaded_file_name', $request->file('file')->getClientOriginalName())
            ->with('lastFileId', $fileId);
    }

    public function importBulanan(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $fileId = $this->storeFile($request->file('file'), 'bulanan');

        $result = $this->saveRowsToDatabase($fileId, Bulanan::class, BulananImport::class);
        $count = $result['processed'];
        $duplicates = $result['duplicates'];

        // If there are duplicates, redirect to modal confirmation
        if (!empty($duplicates)) {
            return redirect()->route('upload.bulanan')
                ->with('duplicates', $duplicates)
                ->with('file_id', $fileId)
                ->with('uploaded_file_name', $request->file('file')->getClientOriginalName());
        }

        $message = "File Bulanan berhasil diupload! {$count} data baru disimpan ke database.";

        return redirect()->route('upload.bulanan')
            ->with('success', $message)
            ->with('uploaded_file_name', $request->file('file')->getClientOriginalName())
            ->with('lastFileId', $fileId);
    }

    // ==============================
// Review Data dari File Upload
// ==============================
public function reviewHarian($fileId)
{
    return $this->review($fileId, 'harian', \App\Imports\HarianImport::class);
}

public function reviewBulanan($fileId)
{
    return $this->review($fileId, 'bulanan', \App\Imports\BulananImport::class);
}

private function review($fileId, $type, $importClass)
{
    $rows = $this->getRowsFromFile($fileId, $importClass);
    if ($rows === null) {
        return redirect()->route("upload.$type")
            ->with('error', 'File tidak ditemukan atau tidak bisa dibaca.');
    }

    $filename = DB::table('uploaded_files')
        ->where('id', $fileId)
        ->value('filename');

    $dataExists = $type === 'harian' ? Harian::exists() : Bulanan::exists();

    return view('admin.upload_data.review_tabel', [
        'rows' => $rows,
        'fileId' => $fileId,
        'filename' => $filename,
        'type' => $type,
        'dataExists' => $dataExists,
    ]);
}


    // ==============================
    // Import with Replace (for duplicate confirmation)
    // ==============================
    public function importHarianReplace(Request $request)
    {
        $request->validate(['file_id' => 'required|integer']);
        $fileId = $request->file_id;

        $result = $this->saveRowsToDatabase($fileId, Harian::class, HarianImport::class, true); // true for replace mode
        $count = $result['processed'];
        $duplicates = $result['duplicates'];

        $this->combineCA($request);

        $message = "File Harian berhasil diupload dengan replace! {$count} data disimpan ke database dan didistribusikan ke CA/Admin.";

        return redirect()->route('upload.harian')
            ->with('success', $message)
            ->with('lastFileId', $fileId);
    }

    public function importBulananReplace(Request $request)
    {
        $request->validate(['file_id' => 'required|integer']);
        $fileId = $request->file_id;

        $result = $this->saveRowsToDatabase($fileId, Bulanan::class, BulananImport::class, true); // true for replace mode
        $count = $result['processed'];
        $duplicates = $result['duplicates'];

        $message = "File Bulanan berhasil diupload dengan replace! {$count} data disimpan ke database.";

        return redirect()->route('upload.bulanan')
            ->with('success', $message)
            ->with('lastFileId', $fileId);
    }

    // ==============================
    // Submit Data
    // ==============================
    private function submitData(Request $request, $fileId, $modelClass, $importClass, $typeName)
    {
        try {
            $result = $this->saveRowsToDatabase($fileId, $modelClass, $importClass);
            $count = $result['processed'];
            $duplicates = $result['duplicates'];

            $message = "✅ {$count} data {$typeName} baru berhasil disimpan ke database.";
            if (!empty($duplicates)) {
                $message .= " SND yang sudah ada dan dilewati: " . implode(', ', array_unique($duplicates)) . ".";
            }

            if ($typeName === 'Harian') {
                $this->combineCA($request);
                $message .= " Data telah didistribusikan ke CA/Admin.";
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Throwable $e) {
            Log::error("submitData gagal ({$typeName}): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function submitHarian(Request $request, $fileId)
    {
        return $this->submitData($request, $fileId, Harian::class, HarianImport::class, 'Harian');
    }

    public function submitBulanan(Request $request, $fileId)
    {
        return $this->submitData($request, $fileId, Bulanan::class, BulananImport::class, 'Bulanan');
    }

    // ==============================
    // Distribusi Data ke CA/Admin
    // ==============================
    public function combineCA(Request $request)
    {
        try {
            $users = User::whereIn('role', ['ca', 'admin'])->get();
            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada user dengan role CA/Admin ditemukan.'], 400);
            }

            $data = Harian::doesntHave('assignedUsers')->get();
            $totalData = $data->count();
            $totalUsers = $users->count();
            $assignedCount = 0;

            if ($totalData == 0) {
                return response()->json(['success' => true, 'message' => 'Tidak ada data baru untuk didistribusikan.']);
            }

            // Hitung pembagian merata
            $basePerUser = intdiv($totalData, $totalUsers);
            $extra = $totalData % $totalUsers;

            DB::beginTransaction();

            $userIndex = 0;
            $userAssignments = array_fill(0, $totalUsers, $basePerUser);
            for ($i = 0; $i < $extra; $i++) {
                $userAssignments[$i]++;
            }

            $dataChunks = [];
            $start = 0;
            foreach ($userAssignments as $count) {
                $dataChunks[] = $data->slice($start, $count);
                $start += $count;
            }

            foreach ($dataChunks as $index => $chunk) {
                $user = $users[$index];
                foreach ($chunk as $row) {
                    $row->assignedUsers()->attach($user->id);

                    // Jika status_bayar adalah 'unpaid', buat entry di caring_telepon untuk follow-up
                    if (strtolower($row->status_bayar) === 'unpaid') {
                        CaringTelepon::firstOrCreate(
                            ['snd' => $row->snd], // Prevent duplicates
                            [
                                'witel' => $row->witel,
                                'type' => $row->type,
                                'produk_bundling' => $row->produk_bundling,
                                'fi_home' => $row->fi_home,
                                'account_num' => $row->account_num,
                                'snd_group' => $row->snd_group,
                                'nama' => $row->nama,
                                'cp' => $row->cp,
                                'datel' => $row->datel,
                                'payment_date' => $row->payment_date,
                                'status_bayar' => $row->status_bayar,
                                'no_hp' => $row->no_hp,
                                'nama_real' => $row->nama_real,
                                'segmen_real' => $row->segmen_real,
                                'user_id' => $user->id,
                                'status_call' => null, // Belum follow-up
                                'keterangan' => null,
                                'contact_date' => null,
                                'alamat' => $row->alamat,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }

                    $assignedCount++;
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => "Berhasil mendistribusikan {$assignedCount} data secara merata ke {$totalUsers} CA/Admin."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("combineCA gagal: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Proses gagal: ' . $e->getMessage()], 500);
        }
    }

    // ==============================
    // Helper Functions
    // ==============================
    private function storeFile($file, $type)
    {
        $folder = "upload/admin/excel_files/{$type}";
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder, 0755, true);
        }

        $filename = Carbon::now()->format('d_m_Y_His') . '_' . $file->getClientOriginalName();
        $file->storeAs($folder, $filename, 'public');

        return DB::table('uploaded_files')->insertGetId([
            'filename' => $filename,
            'path' => "{$folder}/{$filename}",
            'type' => $type,
            'uploaded_by' => Auth::id() ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

private function getRowsFromFile($fileId, $importClass)
{
    $fileRecord = DB::table('uploaded_files')->where('id', $fileId)->first();
    if (!$fileRecord) return null;

    $filePath = storage_path('app/public/' . $fileRecord->path);
    if (!file_exists($filePath)) return null;

    try {
        $collection = Excel::toCollection(new $importClass, $filePath)[0];
    } catch (\Exception $e) {
        Log::error("Error baca file Excel: " . $e->getMessage());
        return null;
    }

    return $collection->map(function ($row) {
        $normalized = [];

        // Normalisasi semua header ke lowercase
        foreach ($row as $key => $value) {
            $normalized[strtolower(trim($key))] = $value;
        }

        // ====== 🔍 Deteksi kolom kontak/telepon secara otomatis ======
        $telpKeys = [
            'telp', 'telepon', 'no_hp', 'no hp', 'no. hp', 'no.telp',
            'no telp', 'no_telepon', 'nomor hp', 'nomor telepon', 'hp'
        ];

        $kontak = 'N/A';
        foreach ($telpKeys as $key) {
            if (isset($normalized[$key]) && !empty($normalized[$key])) {
                $kontak = (string) $normalized[$key];
                break;
            }
        }

        $normalized['no_hp'] = $kontak; // disimpan di field no_hp
        return $normalized;
    })
    ->filter(fn($row) => !empty($row['snd'])) // hanya ambil baris yang punya SND
    ->values()
    ->toArray();
}


    private function saveRowsToDatabase($fileId, $modelClass, $importClass, $replace = false)
    {
        $rows = $this->getRowsFromFile($fileId, $importClass);
        if (!$rows) return ['processed' => 0, 'duplicates' => []];

        $processed = 0;
        $duplicates = [];

        DB::beginTransaction();
        try {
        foreach ($rows as $row) {
            $snd = $row['snd'] ?? null;
            if (empty($snd)) continue;

            // Tentukan status bayar berdasarkan TGL_BAYAR
            $tglBayar = $this->parseExcelDate($row['tgl_bayar'] ?? null);
            $statusBayar = $tglBayar ? 'Paid' : 'Unpaid';

            // Ambil kontak dari kolom TELP
            $kontak = $row['no_hp'] ?? 'N/A';

            if ($modelClass == Harian::class) {
                // For Harian, update existing records cumulatively
                $exists = Harian::where('snd', $snd)->first();
                if ($exists && $tglBayar) {
                    $exists->update([
                        'payment_date' => $tglBayar,
                        'status_bayar' => 'Paid',
                        'updated_at' => now(),
                    ]);
                    // Update corresponding CaringTelepon status if exists
                    CaringTelepon::where('snd', $snd)->update([
                        'status_bayar' => 'Paid',
                        'updated_at' => now(),
                    ]);
                    $processed++;
                } else {
                    $duplicates[] = $snd;
                }
            } else {
                // For Bulanan, keep original insert/update logic
                $rowData = [
                    'witel' => $row['witel'] ?? 'N/A',
                    'type' => $row['type'] ?? 'N/A',
                    'produk_bundling' => $row['produk_bundling'] ?? 'N/A',
                    'fi_home' => $row['fi_home'] ?? 'N/A',
                    'account_num' => $row['account_num'] ?? 'N/A',
                    'snd' => $snd,
                    'snd_group' => $row['snd_group'] ?? 'N/A',
                    'nama' => $row['nama_ncli'] ?? 'N/A', // Company name from NAMA_NCLI
                    'alamat' => $row['alamat'] ?? 'N/A',
                    'ncli' => $row['ncli'] ?? 'N/A',
                    'nama_ncli' => $row['nama_ncli'] ?? 'N/A',
                    'cp' => $kontak,
                    'datel' => $row['datel'] ?? 'N/A',
                    'payment_date' => $tglBayar,
                    'status_bayar' => $statusBayar,
                    'telp' => $kontak,
                    'nama_real' => $row['nama_real'] ?? 'N/A',
                    'segmen_real' => $row['segmen_real'] ?? 'N/A',
                    'uploaded_file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $exists = $modelClass::where('snd', $snd)->first();
                if ($exists) {
                    if ($replace) {
                        // Update existing record
                        $exists->update($rowData);
                        $duplicates[] = $snd;
                        $processed++;
                    } else {
                        $duplicates[] = $snd;
                        continue;
                    }
                } else {
                    $modelClass::create($rowData);
                    $processed++;
                }
            }
        }

            DB::commit();
            return ['processed' => $processed, 'duplicates' => $duplicates];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("saveRowsToDatabase error: " . $e->getMessage());
            throw $e;
        }
    }

    private function parseExcelDate($value)
    {
        if (!$value) return null;

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d'];
        foreach ($formats as $fmt) {
            $date = \DateTime::createFromFormat($fmt, $value);
            if ($date) return $date->format('Y-m-d');
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}
