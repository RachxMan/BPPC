<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaController;

Route::get('/', fn() => redirect()->route('login'));
Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.submit');

Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Middleware khusus admin
Route::middleware(['role:admin'])->group(function () {
    // Upload Data & Mailing List
    Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');
    Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Semua user login bisa akses ini
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('user.index');
    Route::post('/kelola-akun/tambah', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
    Route::get('/kelola-akun/switch-tab/{tab}', [KelolaAkunController::class, 'switchTab'])->name('kelola-akun.switchTab');

    Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');
    Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/success', fn() => view('success'))->name('success');
    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    // Halaman Kelola Akun
    Route::prefix('kelola-akun')->name('kelola.')->group(function () {
        Route::get('/', [KelolaController::class, 'index'])->name('index');
        Route::get('/create', [KelolaController::class, 'create'])->name('create');
        Route::post('/', [KelolaController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [KelolaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [KelolaController::class, 'update'])->name('update');
        Route::delete('/{user}', [KelolaController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle', [KelolaController::class, 'toggleStatus'])->name('toggle');
    });
});

// Profil
Route::get('/profil', function () {
    return view('profil_pengaturan');
})->name('profile.index');

Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/success', function () {
    return view('success');
})->name('success');
