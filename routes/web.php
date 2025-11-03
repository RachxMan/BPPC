<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CaringController;
use App\Http\Controllers\Admin\UploadDataController;
use App\Http\Controllers\Admin\ReportController;
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
// Protected Routes (auth + status)
// ======================
Route::middleware(['auth', 'status'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update-followup-status', [DashboardController::class, 'updateFollowupStatus'])->name('admin.dashboard.updateFollowupStatus');
    Route::post('/dashboard/update-payment-status', [DashboardController::class, 'updatePaymentStatus'])->name('admin.dashboard.updatePaymentStatus');

    // ======================
    // Caring Pelanggan
    // ======================
    Route::prefix('caring')->group(function () {
        Route::get('/telepon', [CaringController::class, 'telepon'])->name('caring.telepon');
        Route::post('/telepon/update-status', [CaringController::class, 'updateStatus'])->name('caring.telepon.update');
    });

    // ======================
    // Upload Data (Admin Only, kontrol di Controller)
    // ======================
    Route::prefix('upload-data')->name('upload.')->group(function () {
        // Halaman utama Upload Data
        Route::get('/', [UploadDataController::class, 'index'])->name('index');
        Route::post('/store', [UploadDataController::class, 'store'])->name('store');

        // Harian
        Route::prefix('harian')->group(function () {
            Route::get('/', [UploadDataController::class, 'harian'])->name('harian');
            Route::post('/import', [UploadDataController::class, 'importHarian'])->name('harian.import');
            Route::get('/review/{fileId}', [UploadDataController::class, 'reviewHarian'])->name('harian.review');
            Route::post('/submit/{fileId}', [UploadDataController::class, 'submitHarian'])->name('harian.submit');
            Route::post('/combineCA', [UploadDataController::class, 'combineCA'])->name('combineCA');
        });

        // Bulanan
        Route::prefix('bulanan')->group(function () {
            Route::get('/', [UploadDataController::class, 'bulanan'])->name('bulanan');
            Route::post('/import', [UploadDataController::class, 'importBulanan'])->name('bulanan.import');
            Route::get('/review/{fileId}', [UploadDataController::class, 'reviewBulanan'])->name('bulanan.review');
            Route::post('/submit/{fileId}', [UploadDataController::class, 'submitBulanan'])->name('bulanan.submit');
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
    // Profil & Pengaturan
    // ======================
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::match(['post', 'put'], '/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/update-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ======================
    // Halaman sukses umum
    // ======================
    Route::get('/success', fn() => view('success'))->name('success');
});
