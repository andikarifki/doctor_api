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
                // Pastikan format tanggal dan tidak di masa lalu
                'tanggal' => 'required|date',
                // Status hanya boleh 'Terdaftar' atau 'Selesai', dengan default 'Terdaftar'
                'status' => 'sometimes|in:Terdaftar,Selesai',
            ]);

            // Jika status tidak dikirim, Model dan Migrasi akan menggunakan nilai default 'Terdaftar'.
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
     * Menggunakan Route Model Binding ($pasien).
     */
    public function show(string $id)
    {
        // Menggunakan 'with' untuk memuat (eager load) relasi 'medicalRecords'
        $pasien = Pasien::with('medicalRecords')->findOrFail($id);

        // Response akan mencakup pasien dan array 'medical_records'
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
                'tanggal' => 'sometimes|date', // Tidak perlu after_or_equal:today jika hanya update
                'status' => 'sometimes|in:Terdaftar,Selesai',
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
        $pasien->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
