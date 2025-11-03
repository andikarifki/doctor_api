<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranPraktikController; // Pastikan ini diimpor!
use Illuminate\Support\Facades\Route; // Pastikan ini diimpor!

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
Route::get('pasien/praktik/{praktik_id}', [PasienController::class, 'indexByPraktikId']);
Route::get('pasien/{id}/praktik/{praktikId}', [PasienController::class, 'showByPasienAndPraktik']);
Route::get('/pasien/{name}', [PasienController::class, 'searchByName']);
Route::get('/pasien', [PasienController::class, 'index']);
Route::post('/pasien/{id}/praktik', [PasienController::class, 'tambahPraktik']);

Route::prefix('pasien')->group(function () {
    // GET /api/pasien -> index (Menampilkan semua pasien)
    Route::get('/', [PasienController::class, 'index']);

    // POST /api/pasien -> store (Menyimpan pasien baru)
    Route::post('/', [PasienController::class, 'store']);

    // GET /api/pasien/{pasien} -> show (Menampilkan pasien spesifik)
    Route::get('/{pasien}', [PasienController::class, 'show']);
    Route::get('/search/{name}', [PasienController::class, 'searchByName']);

    // PUT/PATCH /api/pasien/{pasien} -> update (Memperbarui pasien)
    Route::match(['put', 'patch'], '/{pasien}', [PasienController::class, 'update']);

    // DELETE /api/pasien/{pasien} -> destroy (Menghapus pasien)
    Route::delete('/{pasien}', [PasienController::class, 'destroy']);
});

Route::prefix('medical-records')->group(function () {
    // POST /api/medical-records
    // Menyimpan riwayat medis baru untuk pasien tertentu
    Route::post('/', [MedicalRecordController::class, 'store']);
    // PUT/PATCH /api/medical-records/{record}
    // Memperbarui riwayat medis berdasarkan ID
    Route::match(['put', 'patch'], '/{record}', [MedicalRecordController::class, 'update']);

    Route::delete('/{record}', [MedicalRecordController::class, 'destroy']);

});
