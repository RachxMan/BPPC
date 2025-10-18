<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
| Gunakan middleware auth nanti setelah login siap.
|--------------------------------------------------------------------------
*/

// ========== LOGIN & LOGOUT ==========
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/login', [AuthController::class, 'login'])->name('login.page');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== DASHBOARD ==========
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ========== MAILING LIST REMINDER ==========
Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

// ========== UPLOAD DATA ==========
Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');

// ========== LAPORAN (HARIAN & BULANAN) ==========
Route::prefix('laporan')->group(function () {
    Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
});

// ========== KELOLA AKUN ==========
Route::get('/kelola-akun', [UserController::class, 'index'])->name('user.index');

// ========== PROFIL & PENGATURAN ==========
Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');

// ========== SUCCESS PAGE (notifikasi sukses / upload dsb) ==========
Route::get('/success', function () {
    return view('success');
})->name('success');
