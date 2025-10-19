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

// ================= HALAMAN UTAMA =================
// Saat user membuka "/", tampilkan halaman login langsung
Route::get('/', function () {
    return view('login');
})->name('home');

// ================= LOGIN & LOGOUT (sementara tanpa validasi) =================
// Klik tombol login langsung menuju dashboard tanpa autentikasi
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login.submit');

// Logout dummy (belum aktif)
Route::post('/logout', function () {
    return redirect('/login');
})->name('logout');

// ================= DASHBOARD =================
// Arahkan langsung ke view dashboard.blade.php tanpa middleware auth
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ================= MODULES =================
Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');
Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');

// ================= LAPORAN =================
Route::prefix('laporan')->group(function () {
    Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
});

// ================= KELOLA AKUN =================
Route::get('/kelola-akun', [UserController::class, 'index'])->name('user.index');

// ================= PROFIL =================
Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');

// ================= SUCCESS PAGE =================
Route::get('/success', fn() => view('success'))->name('success');
