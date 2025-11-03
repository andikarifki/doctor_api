<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranPraktikController;
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

Route::apiResource('pendaftaran-praktik', PendaftaranPraktikController::class);

Route::prefix('pasien')->group(function () {
    Route::get('/', [PasienController::class, 'index']); // semua pasien
    Route::post('/', [PasienController::class, 'store']); // tambah pasien

    Route::get('/praktik/{praktik_id}', [PasienController::class, 'indexByPraktikId']); // pasien per praktik
    Route::get('/{id}/praktik/{praktikId}', [PasienController::class, 'showByPasienAndPraktik']); // 1 pasien di praktik
    Route::get('/show/{id}', [PasienController::class, 'show']); // tampil 1 pasien
    Route::get('/search/{name}', [PasienController::class, 'searchByName']); // cari nama

    Route::post('/{id}/praktik', [PasienController::class, 'tambahPraktik']); // tambah praktik ke pasien
    Route::match(['put', 'patch'], '/{id}', [PasienController::class, 'update']); // update pasien
    Route::delete('/{id}', [PasienController::class, 'destroy']); // hapus pasien
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
