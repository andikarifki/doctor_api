<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranPraktikController;
use App\Http\Controllers\StokObatController;
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
Route::apiResource('stok-obat', StokObatController::class);

Route::get('/pasien/{pasien_id}/praktik', [App\Http\Controllers\PasienController::class, 'listPraktiks']);
Route::get('/praktik/{id}/pasiens', [PasienController::class, 'indexByPraktikId']);
Route::get('/praktik/semua', [PasienController::class, 'semuaLokasiDenganPasien']);
Route::delete('/praktik/{praktikId}/pasien/{pasienId}', [PendaftaranPraktikController::class, 'hapusPasienDariPraktik']);
Route::post('/pasien/{pasien}/medical-records', [MedicalRecordController::class, 'storeByPasien']);

Route::prefix('pasien')->group(function () {
    Route::get('/', [PasienController::class, 'index']); // semua pasien
    Route::post('/', [PasienController::class, 'store']); // tambah pasien

    Route::get('/{id}/praktik/{praktikId}', [PasienController::class, 'showByPasienAndPraktik']); // 1 pasien di praktik
    Route::get('/show/{id}', [PasienController::class, 'show']); // tampil 1 pasien
    Route::get('/search/{name}', [PasienController::class, 'searchByName']); // cari nama

    Route::post('/{id}/praktik', [PasienController::class, 'tambahPraktik']); // tambah praktik ke pasien
    Route::match(['put', 'patch'], '/{id}', [PasienController::class, 'update']); // update pasien
    Route::delete('/{id}', [PasienController::class, 'destroy']); // hapus pasien
});

Route::prefix('medical-records')->group(function () {
    Route::get('/', [MedicalRecordController::class, 'index']);
    Route::post('/', [MedicalRecordController::class, 'store']);
    Route::match(['put', 'patch'], '/{record}', [MedicalRecordController::class, 'update']);
    Route::delete('/{record}', [MedicalRecordController::class, 'destroy']);
    Route::get('/grouped/pasien', [MedicalRecordController::class, 'indexByPasien']);
});

Route::post('/pasien/{pasien}/medical-records', [MedicalRecordController::class, 'storeByPasien']);
Route::get('/pasien/{pasien}/medical-records', [MedicalRecordController::class, 'getByPasien']);
