<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PenyuluhanController;

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

// Penyuluhan routes
Route::get('/penyuluhan', [PenyuluhanController::class, 'apiIndex']);
Route::post('/penyuluhan', [PenyuluhanController::class, 'apiStore']);
Route::get('/penyuluhan/{id}', [PenyuluhanController::class, 'apiShow']);
Route::put('/penyuluhan/{id}', [PenyuluhanController::class, 'apiUpdate']);
Route::delete('/penyuluhan/{id}', [PenyuluhanController::class, 'apiDestroy']);
