<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CaringController;
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

// --- Register ---
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// ======================
// Protected Routes
// ======================
Route::middleware(['auth', 'status'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ======================
    // Caring Pelanggan
    // ======================
    Route::prefix('caring')->group(function () {
        Route::get('/telepon', [CaringController::class, 'telepon'])->name('caring.telepon');
        Route::post('/telepon/update-status', [CaringController::class, 'updateStatus'])->name('caring.telepon.update');
    });

    // ======================
    // Upload Data
    // ======================
    Route::prefix('upload-data')->group(function () {

        Route::get('/', [UploadDataController::class, 'index'])->name('upload.index');

        // Harian
        Route::prefix('harian')->group(function () {
            Route::get('/', [UploadDataController::class, 'harian'])->name('upload.harian');
            Route::post('/import', [UploadDataController::class, 'importHarian'])->name('upload.harian.import');
            Route::get('/review/{fileId}', [UploadDataController::class, 'reviewHarian'])->name('upload.harian.review');
            Route::post('/submit/{fileId}', [UploadDataController::class, 'submitHarian'])->name('upload.harian.submit');
            Route::post('/combineCA', [UploadDataController::class, 'combineCA'])->name('upload.combineCA');
        });

        // Bulanan
        Route::prefix('bulanan')->group(function () {
            Route::get('/', [UploadDataController::class, 'bulanan'])->name('upload.bulanan');
            Route::post('/import', [UploadDataController::class, 'importBulanan'])->name('upload.bulanan.import');
            Route::get('/review/{fileId}', [UploadDataController::class, 'reviewBulanan'])->name('upload.bulanan.review');
            Route::post('/submit/{fileId}', [UploadDataController::class, 'submitBulanan'])->name('upload.bulanan.submit');
        });
    });

    // ======================
    // Kelola Akun
    // ======================
    Route::prefix('kelola-akun')->name('kelola.')->group(function () {
        Route::get('/', [KelolaController::class, 'index'])->name('index');
        Route::get('/create', [KelolaController::class, 'create'])->name('create');
        Route::post('/', [KelolaController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [KelolaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [KelolaController::class, 'update'])->name('update');
        Route::delete('/{user}', [KelolaController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle', [KelolaController::class, 'toggleStatus'])->name('toggle');
    });

    // ======================
    // Laporan
    // ======================
    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    // ======================
    // Profil
    // ======================
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::match(['post', 'put'], '/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ======================
    // Halaman sukses umum
    // ======================
    Route::get('/success', fn() => view('success'))->name('success');
});
