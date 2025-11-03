<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\PendaftaranPraktik;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PasienController extends Controller
{
    /**
     * 🩺 Menampilkan daftar semua pasien (dengan semua praktik & rekam medis).
     */
    public function index(): JsonResponse
    {
        $pasiens = Pasien::with(['praktiks', 'medicalRecords'])->get();

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
        $praktik = PendaftaranPraktik::find($praktik_id);

        if (! $praktik) {
            return response()->json([
                'success' => false,
                'message' => 'Praktik tidak ditemukan.',
            ], 404);
        }

        $pasiens = $praktik->pasiens()->with(['medicalRecords'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berdasarkan praktik.',
            'data' => $pasiens,
        ]);
    }

    /**
     * 🏥 Menyimpan data pasien baru + mendaftarkan ke praktik tertentu.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nik' => 'required|string|size:16|unique:pasien,nik',
                'nama' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif',
                'praktik_id' => 'required|exists:pendaftaran_praktik,id',
                'tanggal_daftar' => 'sometimes|date',
            ]);

            $pasien = Pasien::create([
                'nik' => $validated['nik'],
                'nama' => $validated['nama'],
                'tanggal' => $validated['tanggal'],
                'status' => $validated['status'] ?? 'Aktif',
            ]);

            $pasien->praktiks()->attach($validated['praktik_id'], [
                'tanggal_daftar' => $validated['tanggal_daftar'] ?? now(),
                'status' => 'Aktif',
            ]);

            $pasien->load(['praktiks', 'medicalRecords']);

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil ditambahkan dan didaftarkan ke praktik.',
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
     * 🔍 Menampilkan data pasien spesifik (dengan semua praktik & rekam medis).
     */
    public function show($id): JsonResponse
    {
        try {
            $pasien = Pasien::with(['praktiks', 'medicalRecords'])->findOrFail($id);

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

    /**
     * 🔎 Menampilkan data pasien berdasarkan nama (pencarian parsial).
     */
    public function searchByName($name): JsonResponse
    {
        $pasiens = Pasien::with(['praktiks', 'medicalRecords'])
            ->where('nama', 'LIKE', '%'.$name.'%')
            ->get();

        if ($pasiens->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien dengan nama "'.$name.'" tidak ditemukan.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berdasarkan nama berhasil diambil.',
            'data' => $pasiens,
        ]);
    }

    /**
     * 🔑 Menampilkan data pasien spesifik berdasarkan ID pasien DAN ID praktik.
     */
    public function showByPasienAndPraktik($id, $praktikId): JsonResponse
    {
        try {
            $pasien = Pasien::with(['praktiks' => function ($q) use ($praktikId) {
                $q->where('pendaftaran_praktik.id', $praktikId);
            }, 'medicalRecords'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pasien ditemukan untuk praktik ini.',
                'data' => $pasien,
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan dalam praktik ini.',
            ], 404);
        }
    }

    /**
     * ✏️ Update data pasien.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $pasien = Pasien::findOrFail($id);

            $validated = $request->validate([
                'nik' => 'sometimes|string|size:16|unique:pasien,nik,'.$id,
                'nama' => 'sometimes|string|max:255',
                'tanggal' => 'sometimes|date|before_or_equal:today',
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif,Meninggal',
            ]);

            $pasien->update($validated);
            $pasien->load(['praktiks', 'medicalRecords']);

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
     * 🗑️ Menghapus data pasien.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $pasien = Pasien::findOrFail($id);

            $pasien->praktiks()->detach();
            $pasien->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil dihapus beserta relasi praktiknya.',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * ➕ Menambahkan pasien yang sudah ada ke praktik baru.
     */
    public function tambahPraktik(Request $request, $pasien_id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'praktik_id' => 'required|exists:pendaftaran_praktik,id',
                'tanggal_daftar' => 'sometimes|date',
                'status' => 'sometimes|string|in:Aktif,Tidak Aktif',
            ]);

            $pasien = Pasien::findOrFail($pasien_id);

            // Cek apakah pasien sudah terdaftar di praktik tersebut
            $sudahTerdaftar = $pasien->praktiks()
                ->where('praktik_id', $validated['praktik_id'])
                ->exists();

            if ($sudahTerdaftar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pasien sudah terdaftar di praktik ini.',
                ], 409);
            }

            // Tambahkan ke pivot
            $pasien->praktiks()->attach($validated['praktik_id'], [
                'tanggal_daftar' => $validated['tanggal_daftar'] ?? now(),
                'status' => $validated['status'] ?? 'Aktif',
            ]);

            $pasien->load('praktiks');

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil ditambahkan ke praktik baru.',
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
}
