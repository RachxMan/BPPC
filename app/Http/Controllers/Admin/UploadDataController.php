<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Harian;
use App\Models\Bulanan;
use App\Models\User;
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
    // =======================
    // Halaman Upload
    // =======================
    public function index()
    {
        return view('admin.upload_data.upload_data');
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

    // =======================
    // Upload File
    // =======================
    public function importHarian(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $fileId = $this->storeFile($request->file('file'), 'harian');

        return redirect()->route('upload.harian')
            ->with('success', 'File Harian berhasil diupload!')
            ->with('uploaded_file_name', $request->file('file')->getClientOriginalName())
            ->with('lastFileId', $fileId);
    }

    public function importBulanan(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $fileId = $this->storeFile($request->file('file'), 'bulanan');

        return redirect()->route('upload.bulanan')
            ->with('success', 'File Bulanan berhasil diupload!')
            ->with('uploaded_file_name', $request->file('file')->getClientOriginalName())
            ->with('lastFileId', $fileId);
    }

    // =======================
    // Review Tabel
    // =======================
    public function reviewHarian($fileId)
    {
        return $this->review($fileId, 'harian', HarianImport::class);
    }

    public function reviewBulanan($fileId)
    {
        return $this->review($fileId, 'bulanan', BulananImport::class);
    }

    private function review($fileId, $type, $importClass)
    {
        $rows = $this->getRowsFromFile($fileId, $importClass);
        if ($rows === null) {
            return redirect()->route("upload.$type")->with('error', 'File tidak ditemukan atau rusak.');
        }

        $filename = DB::table('uploaded_files')->where('id', $fileId)->value('filename');
        $dataExists = $type === 'harian' ? Harian::exists() : Bulanan::exists();

        return view('admin.upload_data.review_tabel', [
            'rows' => $rows,
            'fileId' => $fileId,
            'filename' => $filename,
            'type' => $type,
            'dataExists' => $dataExists,
        ]);
    }

    // =======================
    // Submit Data
    // =======================
    public function submitHarian(Request $request, $fileId)
    {
        return $this->submitData($request, $fileId, Harian::class, HarianImport::class, 'Harian');
    }

    public function submitBulanan(Request $request, $fileId)
    {
        return $this->submitData($request, $fileId, Bulanan::class, BulananImport::class, 'Bulanan');
    }

    private function submitData(Request $request, $fileId, $modelClass, $importClass, $typeName)
    {
        try {
            $count = $this->saveRowsToDatabase($fileId, $modelClass, $importClass);

            $message = "✅ Data $typeName berhasil disimpan ke database.";

            return response()->json(['success' => true, 'message' => $message, 'imported' => $count]);
        } catch (\Throwable $e) {
            Log::error("submitData gagal ($typeName): " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    // =======================
    // Kombinasi ke CA (Admin + CA)
    // =======================
    public function combineCA(Request $request)
    {
        try {
            $users = User::whereIn('role', ['ca', 'admin'])->get();
            if ($users->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada user dengan role CA atau Admin ditemukan.'], 400);
            }

            $data = Harian::inRandomOrder()->get();
            if ($data->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data harian untuk dibagikan.'], 400);
            }

            $totalUsers = $users->count();
            $assignments = [];
            $assignedCount = 0;

            try {
                DB::table('harian_user')->truncate();
            } catch (\Throwable $e) {
                DB::table('harian_user')->delete();
            }

            foreach ($data as $index => $row) {
                $assignedUser = $users[$index % $totalUsers];
                $assignments[] = [
                    'snd' => $row->snd,
                    'user_id' => $assignedUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $assignedCount++;
            }

            foreach (array_chunk($assignments, 500) as $chunk) {
                DB::table('harian_user')->insert($chunk);
            }

            Log::info("✅ Kombinasi CA berhasil: {$assignedCount} data dibagikan ke {$totalUsers} user.");

            return response()->json([
                'success' => true,
                'message' => "Berhasil membagikan {$assignedCount} data ke {$totalUsers} user."
            ]);
        } catch (\Throwable $e) {
            Log::error("combineCA gagal: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Proses gagal: ' . $e->getMessage()], 500);
        }
    }

    // =======================
    // Helper Functions
    // =======================
    private function storeFile($file, $type)
    {
        $folder = "upload/admin/excel_files/$type";
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder, 0755, true);
        }

        $filename = Carbon::now()->format('d_m_Y_His') . '_' . $file->getClientOriginalName();
        $file->storeAs($folder, $filename, 'public');

        $fileId = DB::table('uploaded_files')->insertGetId([
            'filename' => $filename,
            'path' => "$folder/$filename",
            'type' => $type,
            'uploaded_by' => Auth::id() ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("Upload $type berhasil: $filename");
        return $fileId;
    }

    private function getRowsFromFile($fileId, $importClass)
    {
        $fileRecord = DB::table('uploaded_files')->where('id', $fileId)->first();
        if (!$fileRecord) return null;

        $filePath = storage_path('app/public/' . $fileRecord->path);
        if (!file_exists($filePath)) return null;

        try {
            $collection = Excel::toCollection(new $importClass, $filePath)[0];
        } catch (\Throwable $e) {
            Log::error("getRowsFromFile gagal: " . $e->getMessage());
            return null;
        }

        return $collection->filter(fn($row) => !empty($row['account_num']))->values()->toArray();
    }

    private function saveRowsToDatabase($fileId, $modelClass, $importClass)
    {
        $rows = $this->getRowsFromFile($fileId, $importClass);
        if (!$rows) return 0;

        $processed = 0;
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $rowData = [
                    'witel' => $row['witel'] ?? null,
                    'type' => $row['type'] ?? null,
                    'produk_bundling' => $row['produk_bundling'] ?? null,
                    'fi_home' => $row['fi_home'] ?? null,
                    'account_num' => $row['account_num'] ?? null,
                    'snd' => $row['snd'] ?? null,
                    'snd_group' => $row['snd_group'] ?? null,
                    'nama' => $row['nama'] ?? null,
                    'cp' => $row['cp'] ?? null,
                    'datel' => $row['datel'] ?? null,
                    'payment_date' => $this->parseExcelDate($row['payment_date'] ?? null),
                    'status_bayar' => $row['status_bayar'] ?? null,
                    'no_hp' => $row['no_hp'] ?? null,
                    'nama_real' => $row['nama_real'] ?? null,
                    'segmen_real' => $row['segmen_real'] ?? null,
                    'uploaded_file_id' => $fileId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (empty($rowData['account_num'])) continue;

                $existing = $modelClass::where('account_num', $rowData['account_num'])->first();
                if ($existing) $existing->update($rowData);
                else $modelClass::create($rowData);

                $processed++;
            }

            DB::commit();
            return $processed;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("saveRowsToDatabase gagal: " . $e->getMessage());
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

        $date = \DateTime::createFromFormat('d/m/Y', $value);
        if ($date) return $date->format('Y-m-d');

        $date = \DateTime::createFromFormat('d-m-Y', $value);
        if ($date) return $date->format('Y-m-d');

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}
