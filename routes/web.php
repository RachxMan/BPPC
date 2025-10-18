<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes - BPPC Telkom
|--------------------------------------------------------------------------
| Semua halaman utama sistem Business Process Payment & Collection.
|--------------------------------------------------------------------------
*/

// ================= LOGIN & LOGOUT =================

// Halaman login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.page');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout sementara
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Halaman dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ================= MAILING LIST =================
Route::get('/mailing-list', [MailingListController::class, 'index'])
    ->name('mailing.index'); // <--- titik koma ditambahkan

// ================= UPLOAD DATA =================
Route::get('/upload-data', [UploadDataController::class, 'index'])
    ->name('upload.index'); // <--- titik koma ditambahkan

// ================= LAPORAN (HARIAN & BULANAN) =================
Route::prefix('laporan')->middleware('auth')->group(function () {
    Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
});

 HEAD
// ================= KELOLA AKUN =================
Route::get('/kelola-akun', [UserController::class, 'index'])
    ->name('user.index'); // <--- titik koma ditambahkan
// ========== KELOLA AKUN ==========
Route::get('/kelola-akun', [UserController::class, 'index'])->name('kelola.index');
Route::get('/kelola-akun/create', [UserController::class, 'create'])->name('kelola.create');
Route::post('/kelola-akun', [UserController::class, 'store'])->name('kelola.store');8ccdb0b (update halaman kelola akun)

// ================= PROFIL & PENGATURAN =================
Route::get('/profil', [ProfileController::class, 'index'])
    ->name('profile.index'); // <--- titik koma ditambahkan

// ================= SUCCESS PAGE =================
Route::get('/success', function () {
    return view('success');
});
