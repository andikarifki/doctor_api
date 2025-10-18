<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PasienController extends Controller
{
    /**
     * 🩺 Menampilkan daftar semua pasien (dengan praktik & rekam medis).
     */
    public function index(): JsonResponse
    {
        $pasiens = Pasien::with(['praktik', 'medicalRecords'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berhasil diambil.',
            'data' => $pasiens,
        ]);
    }

    /**
     * 🔗 Menampilkan daftar pasien berdasarkan ID praktik.
     */
    public function indexByPraktikId($praktik_id): JsonResponse
    {
        $pasiens = Pasien::with(['praktik', 'medicalRecords']) // Tambahkan 'praktik' untuk konsistensi
            ->where('praktik_id', $praktik_id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berdasarkan praktik.',
            'data' => $pasiens,
        ]);
    }

    /**
     * 🏥 Menyimpan data pasien baru.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'praktik_id' => 'required|exists:pendaftaran_praktik,id',
                'nama' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif,Meninggal',
            ]);

            $pasien = Pasien::create($validated);
            $pasien->load('praktik');

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil ditambahkan.',
                'data' => $pasien,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * 🔍 Menampilkan data pasien spesifik berdasarkan ID pasien.
     * Catatan: Untuk keamanan, Anda mungkin ingin menggunakan showByPasienAndPraktik.
     */
    public function show($id): JsonResponse
    {
        try {
            // Logika tetap sama (hanya mencari berdasarkan ID pasien)
            $pasien = Pasien::with(['praktik', 'medicalRecords'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien ditemukan.',
                'data' => $pasien,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }
    }

    // --- FUNGSI BARU UNTUK KEAMANAN DAN KONSISTENSI ---

    /**
     * 🔑 Menampilkan data pasien spesifik berdasarkan ID pasien DAN ID praktik.
     * Ini disarankan untuk API multi-tenancy.
     */
    public function showByPasienAndPraktik($id, $praktikId): JsonResponse
    {
        try {
            // Filter ganda: Pasien harus memiliki ID=$id DAN praktik_id=$praktikId
            $pasien = Pasien::where('praktik_id', $praktikId)
                ->with(['praktik', 'medicalRecords'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien ditemukan dalam praktik yang spesifik.',
                'data' => $pasien,
            ]);
        } catch (ModelNotFoundException $e) {
            // Jika ID pasien tidak ada, atau jika ID pasien ada tapi praktik_id tidak cocok
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan dalam praktik ini.',
            ], 404);
        }
    }

    /**
     * ✏️ Memperbarui data pasien.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $pasien = Pasien::findOrFail($id);

            $validated = $request->validate([
                'praktik_id' => 'sometimes|exists:pendaftaran_praktik,id',
                'nama' => 'sometimes|string|max:255',
                'tanggal' => 'sometimes|date|before_or_equal:today',
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif,Meninggal',
            ]);

            $pasien->update($validated);
            $pasien->load(['praktik', 'medicalRecords']);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil diperbarui.',
                'data' => $pasien,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * 🗑️ Menghapus data pasien spesifik.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $pasien = Pasien::findOrFail($id);
            $pasien->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil dihapus.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }
    }
}
