<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaController;

Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.store');

Route::middleware(['auth', 'status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/upload-data', [UploadDataController::class, 'index'])->name('upload.index');
    Route::get('/mailing-list', [MailingListController::class, 'index'])->name('mailing.index');

    Route::prefix('kelola-akun')->name('kelola.')->group(function () {
        Route::get('/', [KelolaController::class, 'index'])->name('index');
        Route::get('/create', [KelolaController::class, 'create'])->name('create');
        Route::post('/', [KelolaController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [KelolaController::class, 'edit'])->name('edit');
        Route::put('/{user}', [KelolaController::class, 'update'])->name('update');
        Route::delete('/{user}', [KelolaController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle', [KelolaController::class, 'toggleStatus'])->name('toggle');
    });

    // Laporan tetap terpisah
    Route::prefix('laporan')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
        Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
    });

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});
