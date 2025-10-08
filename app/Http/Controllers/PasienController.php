<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PasienController extends Controller
{
    /**
     * Menampilkan daftar semua pasien.
     */
    public function index(): JsonResponse
    {
        // Gunakan 'paginate' jika daftar pasien mungkin besar untuk performa yang lebih baik.
        $patients = Pasien::with('medicalRecords')->get();
        return response()->json($patients);
    }

    /**
     * Menyimpan data pasien baru.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validasi data masukan
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                // PERBAIKAN 1: Gunakan nama kolom yang lebih spesifik, misal 'tanggal_lahir'
                'tanggal' => 'required|date|before_or_equal:today',

                // PERBAIKAN 2: Jika skema sudah diubah ke string/VARCHAR, validasi menggunakan nilai string yang baru
                // Nilai ENUM 'Terdaftar' diganti 'Aktif', 'Selesai' diganti 'Tidak Aktif'
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif,Meninggal',
            ]);

            // Jika 'status' tidak ada dalam request, nilai default akan ditangani oleh skema database ('Aktif').
            $pasien = Pasien::create($validatedData);

            return response()->json($pasien, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        }
    }

    /**
     * Menampilkan data pasien spesifik.
     * Menggunakan Route Model Binding ($pasien) untuk konsistensi.
     */
    public function show(Pasien $pasien): JsonResponse
    {
        // Route Model Binding otomatis menemukan pasien. Kita hanya perlu memuat relasi.
        $pasien->load('medicalRecords');

        return response()->json($pasien);
    }

    /**
     * Memperbarui data pasien spesifik.
     */
    public function update(Request $request, Pasien $pasien): JsonResponse
    {
        try {
            // Validasi data masukan
            $validatedData = $request->validate([
                'nama' => 'sometimes|string|max:255',
                'tanggal' => 'sometimes|date', // 'before_or_equal:today' bisa ditambahkan jika ini adalah tanggal lahir
                // PERBAIKAN 3: Validasi status yang diperbarui (menggunakan string baru)
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif,Meninggal',
            ]);

            $pasien->update($validatedData);

            return response()->json($pasien);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Menghapus data pasien spesifik.
     */
    public function destroy(Pasien $pasien): JsonResponse
    {
        // Anda mungkin ingin menambahkan logika untuk memastikan tidak ada medical record yang terkait
        // atau mengandalkan foreign key constraint di database.

        $pasien->delete();
        return response()->json(null, 204); // 204 No Content
    }
}