<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('login');
})->name('home');

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

Route::get('/mailing-list', function () {
    return view('mailing_list');
})->name('mailing.index');

Route::get('/upload-data', function () {
    return view('upload_data');
})->name('upload.index');

Route::prefix('laporan')->group(function () {
    Route::get('/harian', [ReportController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [ReportController::class, 'bulanan'])->name('report.bulanan');
});

Route::get('/kelola-akun', function () {
    return view('kelola-akun');
})->name('user.index');

Route::get('/profil', function () {
    return view('profil_pengaturan');
})->name('profile.index');

Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/success', function () {
    return view('success');
})->name('success');
