<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/mailing-list', function () {
    return view('mailing_list');
});

Route::get('/uploaddata', function () {
    return view('upload_data');
});

Route::get('/harian', function () {
    return view('harian');
});

Route::get('/bulanan', function () {
    return view('bulanan');
});

Route::get('/kelola-akun', function () {
    return view('kelola-akun');
});

Route::get('/profil&pengaturan', function () {
    return view('profil_pengaturan');
});

Route::get('/success', function () {
    return view('success');
});
