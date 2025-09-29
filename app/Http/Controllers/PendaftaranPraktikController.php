<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPraktik;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PendaftaranPraktikController extends Controller
{
    /**
     * Menampilkan daftar semua pendaftaran. (GET /api/pendaftaran-praktik)
     */
    public function index(): JsonResponse
    {
        // Mengambil semua data pendaftaran
        $pendaftaran = PendaftaranPraktik::all();
        return response()->json($pendaftaran);
    }

    /**
     * Menyimpan pendaftaran baru. (POST /api/pendaftaran-praktik)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validasi data masukan
            $validatedData = $request->validate([
                // lokasi_praktik bersifat opsional (karena punya nilai default di migrasi)
                'lokasi_praktik' => 'sometimes|string|max:255',
                // tanggal_daftar wajib, harus berupa tanggal, dan tidak boleh di masa lalu (hari ini atau setelahnya)
                'tanggal_daftar' => 'required|date|after_or_equal:today',
            ]);

            // Membuat entri baru di database
            $pendaftaran = PendaftaranPraktik::create($validatedData);

            return response()->json($pendaftaran, 201); // 201 Created
        } catch (ValidationException $e) {
            // Menangani kegagalan validasi
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        }
    }

    /**
     * Menampilkan pendaftaran spesifik. (GET /api/pendaftaran-praktik/{id})
     * Menggunakan Route Model Binding.
     */
    public function show(PendaftaranPraktik $pendaftaranPraktik): JsonResponse
    {
        return response()->json($pendaftaranPraktik);
    }

    /**
     * Memperbarui pendaftaran spesifik. (PUT/PATCH /api/pendaftaran-praktik/{id})
     */
    public function update(Request $request, PendaftaranPraktik $pendaftaranPraktik): JsonResponse
    {
        try {
            // Validasi data masukan (menggunakan 'sometimes' untuk update)
            $validatedData = $request->validate([
                'lokasi_praktik' => 'sometimes|string|max:255',
                'tanggal_daftar' => 'sometimes|date|after_or_equal:today',
            ]);

            // Memperbarui data yang ada
            $pendaftaranPraktik->update($validatedData);

            return response()->json($pendaftaranPraktik);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal saat update.',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Menghapus pendaftaran spesifik. (DELETE /api/pendaftaran-praktik/{id})
     */
    public function destroy(PendaftaranPraktik $pendaftaranPraktik): JsonResponse
    {
        $pendaftaranPraktik->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
