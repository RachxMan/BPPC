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
    return redirect()->route('dashboard');
})->name('login.submit');

// Logout dummy (belum aktif)
Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

// ================= DASHBOARD =================
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ================= MODULES =================
// Mailing List
Route::get('/mailing-list', function () {
    return view('mailing_list');
})->name('mailing.index');

// Upload Data
Route::get('/upload-data', function () {
    return view('upload_data');
})->name('upload.index');

// ================= LAPORAN =================
Route::prefix('laporan')->group(function () {
    Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
});

// ================= KELOLA AKUN =================
Route::get('/kelola-akun', function () {
    return view('kelola-akun');
})->name('user.index');

// ================= PROFIL =================
Route::get('/profil', function () {
    return view('profil_pengaturan');
})->name('profile.index');

Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

// ================= SUCCESS PAGE =================
Route::get('/success', function () {
    return view('success');
})->name('success');
