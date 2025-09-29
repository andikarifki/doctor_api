<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranPraktikController;
use App\Http\Controllers\PasienController; // Pastikan ini diimpor!

// Route Public (Tidak memerlukan token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route Protected (Memerlukan token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Contoh route terproteksi lainnya
    // Route::get('/data-rahasia', function () {
    //     return response()->json(['data' => 'Ini data terproteksi.']);
    // });
});

// di routes/api.php
Route::apiResource('pendaftaran-praktik', PendaftaranPraktikController::class);

Route::prefix('pasien')->group(function () {
    // GET /api/pasien -> index (Menampilkan semua pasien)
    Route::get('/', [PasienController::class, 'index']);

    // POST /api/pasien -> store (Menyimpan pasien baru)
    Route::post('/', [PasienController::class, 'store']);

    // GET /api/pasien/{pasien} -> show (Menampilkan pasien spesifik)
    Route::get('/{pasien}', [PasienController::class, 'show']);

    // PUT/PATCH /api/pasien/{pasien} -> update (Memperbarui pasien)
    Route::match(['put', 'patch'], '/{pasien}', [PasienController::class, 'update']);

    // DELETE /api/pasien/{pasien} -> destroy (Menghapus pasien)
    Route::delete('/{pasien}', [PasienController::class, 'destroy']);
});

