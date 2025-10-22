<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\KelolaAkunController;
use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
| Semua route aplikasi web, termasuk login, register, dashboard, dan fitur admin/ca.
|--------------------------------------------------------------------------
*/

// ======================
// 🔹 AUTENTIKASI
// ======================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// --- Login ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// --- Register ---
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');


// --- Logout ---
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ======================
// 🔹 HALAMAN TERLINDUNGI (Hanya setelah login)
// ======================
Route::middleware(['auth'])->group(function () {

    // --- Dashboard umum ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Dashboard Admin ---
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('user.index');
        Route::post('/kelola-akun/tambah', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
        Route::get('/kelola-akun/switch-tab/{tab}', [KelolaAkunController::class, 'switchTab'])->name('kelola-akun.switchTab');
    });

    // --- Dashboard CA (Customer Account) ---
    Route::middleware(['role:ca'])->group(function () {
        Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');
    });

    // --- Mailing List (boleh untuk semua yang login) ---
    Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

    // --- Report (harian & bulanan) ---
    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    // --- Profil & Pengaturan ---
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

    // --- Halaman sukses umum ---
    Route::get('/success', fn() => view('success'))->name('success');
});
