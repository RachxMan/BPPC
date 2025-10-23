<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\Admin\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaController;
use App\Http\Controllers\Auth\RegisterController;

// ======================
// Auth Routes
// ======================
Route::get('/', fn() => redirect()->route('login'))->name('home');

// --- Login ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::middleware(['auth','status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('upload-data')->name('upload.')->group(function () {
        Route::get('/', [UploadDataController::class, 'index'])->name('index');
        Route::get('/harian', [UploadDataController::class, 'harian'])->name('harian');
        Route::get('/bulanan', [UploadDataController::class, 'bulanan'])->name('bulanan');
        Route::post('/harian/import', [UploadDataController::class, 'importHarian'])->name('harian.import');
        Route::post('/bulanan/import', [UploadDataController::class, 'importBulanan'])->name('bulanan.import');
        Route::get('/harian/review/{fileId}', [UploadDataController::class, 'reviewHarian'])->name('harian.review');
        Route::get('/bulanan/review/{fileId}', [UploadDataController::class, 'reviewBulanan'])->name('bulanan.review');
        Route::post('/harian/submit/{fileId}', [UploadDataController::class, 'submitHarian'])->name('harian.submit');
        Route::post('/bulanan/submit/{fileId}', [UploadDataController::class, 'submitBulanan'])->name('bulanan.submit');
        Route::post('/combine-ca', [UploadDataController::class, 'combineCA'])->name('combine.ca');
    });
    Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

    // Kelola Akun (pindah ke luar dari 'laporan')
    Route::prefix('kelola-akun')->name('kelola.')->group(function () {
        Route::get('/', [KelolaController::class, 'index'])->name('index');
        Route::get('/create', [KelolaController::class, 'create'])->name('create');
        Route::post('/', [KelolaController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [KelolaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [KelolaController::class, 'update'])->name('update');
        Route::delete('/{user}', [KelolaController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle', [KelolaController::class, 'toggleStatus'])->name('toggle');
    });

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::match(['post', 'put'], '/profil/update', [ProfileController::class, 'update'])->name('profile.update');

    // 🔹 Tambahkan route update password di sini
    Route::post('/profil/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // --- Halaman sukses umum ---
    Route::get('/success', fn() => view('success'))->name('success');
});
