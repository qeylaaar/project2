<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\EdukasiBencanaController;
use App\Http\Controllers\PenyuluhanController;

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.perform');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
    Route::get('/reset-password', [ResetPassword::class, 'show'])->name('reset-password');
    Route::post('/reset-password', [ResetPassword::class, 'send'])->name('reset.perform');
    Route::get('/change-password', [ChangePassword::class, 'show'])->name('change-password');
    Route::post('/change-password', [ChangePassword::class, 'update'])->name('change.perform');
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // Halaman statis
    Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
    Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
    Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
    Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');

    // Resource Pengaduan
    Route::resource('pengaduan', PengaduanController::class);

    // Resource Routes
    Route::resource('pengguna', PenggunaController::class);
    Route::resource('edukasi-bencana', EdukasiBencanaController::class);

    Route::resource('penyuluhan', PenyuluhanController::class);
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Halaman dinamis (harus di bagian bawah)
    Route::get('/{page}', [PageController::class, 'index'])->name('page');

    // Route untuk Penyuluhan
    Route::get('/penyuluhan', [App\Http\Controllers\PenyuluhanController::class, 'index'])->name('penyuluhan.index');
    Route::get('/penyuluhan/create', [App\Http\Controllers\PenyuluhanController::class, 'create'])->name('penyuluhan.create');
    Route::post('/penyuluhan', [App\Http\Controllers\PenyuluhanController::class, 'store'])->name('penyuluhan.store');
    Route::get('/penyuluhan/{id}/edit', [App\Http\Controllers\PenyuluhanController::class, 'edit'])->name('penyuluhan.edit');
    Route::put('/penyuluhan/{id}', [App\Http\Controllers\PenyuluhanController::class, 'update'])->name('penyuluhan.update');
    Route::delete('/penyuluhan/{id}', [App\Http\Controllers\PenyuluhanController::class, 'destroy'])->name('penyuluhan.destroy');
});
