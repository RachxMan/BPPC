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


Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('user.index');
        Route::post('/kelola-akun/tambah', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
        Route::get('/kelola-akun/switch-tab/{tab}', [KelolaAkunController::class, 'switchTab'])->name('kelola-akun.switchTab');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');
        Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::prefix('laporan')->group(function () {
            Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
            Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
        });
    });

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/success', fn() => view('success'))->name('success');
});
