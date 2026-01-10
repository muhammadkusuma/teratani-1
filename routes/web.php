<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/fitur', function () {return view('landing.fitur');});
    Route::get('/harga', function () {return view('landing.harga');});
    Route::get('/studi-kasus', function () {return view('landing.studi-kasus');});
    Route::get('/tentang-kami', function () {return view('landing.tentang');});
    Route::get('/karir', function () {return view('landing.karir');});
    Route::get('/kontak', function () {return view('landing.kontak');});
    Route::get('/privasi', function () {return view('landing.privasi');});
    Route::get('/syarat-ketentuan', function () {return view('landing.syarat');});
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->names('users');

    Route::resource('tenants', TenantController::class);

    Route::resource('settings', SettingController::class);
});
