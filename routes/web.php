<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\Admin\UploadDataController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routing untuk seluruh halaman aplikasi BPPC
|
*/

// ======================
// Halaman Auth
// ======================
Route::get('/', fn() => view('login'))->name('home');
Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', fn() => redirect()->route('dashboard'))->name('login.submit');
Route::post('/logout', fn() => redirect()->route('login'))->name('logout');

// ======================
// Halaman Dashboard
// ======================
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

// ======================
// Halaman Mailing List
// ======================
Route::get('/mailing-list', fn() => view('mailing_list'))->name('mailing.index');

// ======================
// Halaman Upload Data (Admin)
// ======================
Route::prefix('upload-data')->group(function () {

    // ------------------------------
    // Halaman utama Upload Data
    // ------------------------------
    Route::get('/', [UploadDataController::class, 'index'])->name('upload.index');

    // ------------------------------
    // Upload Data Harian
    // ------------------------------
    Route::prefix('harian')->group(function () {
        Route::get('/', [UploadDataController::class, 'harian'])->name('upload.harian');
        Route::post('/import', [UploadDataController::class, 'importHarian'])->name('upload.harian.import');
        Route::get('/review/{fileId}', [UploadDataController::class, 'reviewHarian'])->name('upload.harian.review');
        Route::post('/submit/{fileId}', [UploadDataController::class, 'submitHarian'])->name('upload.harian.submit');

        // 🔹 Kombinasi Data Harian ke CA (Admin + CA)
        Route::post('/combineCA', [UploadDataController::class, 'combineCA'])->name('upload.combineCA');
    });

    // ------------------------------
    // Upload Data Bulanan
    // ------------------------------
    Route::prefix('bulanan')->group(function () {
        Route::get('/', [UploadDataController::class, 'bulanan'])->name('upload.bulanan');
        Route::post('/import', [UploadDataController::class, 'importBulanan'])->name('upload.bulanan.import');
        Route::get('/review/{fileId}', [UploadDataController::class, 'reviewBulanan'])->name('upload.bulanan.review');
        Route::post('/submit/{fileId}', [UploadDataController::class, 'submitBulanan'])->name('upload.bulanan.submit');
    });
});

// ======================
// Halaman Kelola Akun
// ======================
Route::prefix('kelola-akun')->group(function () {
    Route::get('/', [KelolaAkunController::class, 'index'])->name('user.index');
    Route::post('/tambah', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
    Route::get('/switch-tab/{tab}', [KelolaAkunController::class, 'switchTab'])->name('kelola-akun.switchTab');
});

// ======================
// Halaman Profil Pengguna
// ======================
Route::get('/profil', fn() => view('profil_pengaturan'))->name('profile.index');
Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

// ======================
// Halaman Success
// ======================
Route::get('/success', fn() => view('success'))->name('success');
