<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PenyuluhanController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\EdukasiBencanaApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/halo', function () {
    return response()->json([
        'pesan' => 'Halo dari Laravel API!'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// User routes
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/update-fcm-token', [UserController::class, 'updateFcmToken']);
Route::post('/user/update-photo/{id}', [UserController::class, 'updatePhoto']);

// Penyuluhan routes
Route::get('/penyuluhan', [PenyuluhanController::class, 'apiIndex']);
Route::post('/penyuluhan', [PenyuluhanController::class, 'apiStore']);
Route::get('/penyuluhan/{id}', [PenyuluhanController::class, 'apiShow']);
Route::put('/penyuluhan/{id}', [PenyuluhanController::class, 'apiUpdate']);
Route::delete('/penyuluhan/{id}', [PenyuluhanController::class, 'apiDestroy']);

// Pengaduan routes
Route::post('/pengaduans', [PengaduanController::class, 'store']);
Route::get('/pengaduans/user/{user_id}', [PengaduanController::class, 'byUser']);
Route::post('/upload-media', [PengaduanController::class, 'uploadMedia']);

// Endpoint untuk polling notifikasi pengaduan terbaru
Route::get('/pengaduan/latest', function () {
    $latest = \App\Models\Pengaduan::orderBy('created_at', 'desc')->first();
    return response()->json([
        'id' => $latest ? $latest->id : null,
        'created_at' => $latest ? $latest->created_at : null,
    ]);
});

// Edukasi Bencana routes
Route::get('/edukasi-bencana', [EdukasiBencanaApiController::class, 'index']);
Route::get('/edukasi-bencana/{jenis}', [EdukasiBencanaApiController::class, 'byJenis']);
Route::get('/edukasi-bencana/detail/{id}', [EdukasiBencanaApiController::class, 'show']);
