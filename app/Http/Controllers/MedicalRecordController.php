<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Pasien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Ambil semua data rekam medis.
     */
    public function index(): JsonResponse
    {
        $records = MedicalRecord::with(['pasien', 'praktik'])->get();

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }

    public function indexByPasien(): JsonResponse
    {
        $patients = Pasien::with(['medicalRecords.praktik'])->get();

        return response()->json([
            'success' => true,
            'data' => $patients,
        ]);
    }

    public function getByPasien($pasienId)
    {
        $records = MedicalRecord::with(['pasien', 'praktik'])
            ->where('pasien_id', $pasienId)
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'tanggal_periksa' => $record->tanggal_periksa,
                    'diagnosis' => $record->diagnosis,
                    'obat' => $record->obat,
                    'lokasi_berobat' => $record->praktik->lokasi_praktik ?? '-',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $records,
        ]);
    }

    /**
     * Ambil detail satu rekam medis.
     */
    public function show(MedicalRecord $record): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $record->load(['pasien', 'praktik']),
        ]);
    }

    /**
     * Simpan data rekam medis baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'praktik_id' => 'required|exists:pendaftaran_praktik,id',
            'tanggal_periksa' => 'required|date',
            'diagnosis' => 'required|string',
            'obat' => 'required|string',
        ]);

        $record = MedicalRecord::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil ditambahkan.',
            'data' => $record->load(['pasien', 'praktik']),
        ], 201);
    }

    public function storeByPasien(Request $request, $pasienId): JsonResponse
    {
        $validated = $request->validate([
            'praktik_id' => 'required|exists:pendaftaran_praktik,id',
            'tanggal_periksa' => 'required|date',
            'diagnosis' => 'required|string',
            'obat' => 'required|string',
        ]);

        // Tambahkan pasien_id otomatis dari URL
        $validated['pasien_id'] = $pasienId;

        $record = MedicalRecord::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil ditambahkan untuk pasien.',
            'data' => $record->load(['pasien', 'praktik']),
        ], 201);
    }

    /**
     * Update data rekam medis.
     */
    public function update(Request $request, MedicalRecord $record): JsonResponse
    {
        $validated = $request->validate([
            'pasien_id' => 'sometimes|required|exists:pasien,id',
            'praktik_id' => 'sometimes|required|exists:pendaftaran_praktik,id',
            'tanggal_periksa' => 'sometimes|required|date',
            'diagnosis' => 'sometimes|required|string',
            'obat' => 'sometimes|required|string',
        ]);

        $record->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil diperbarui.',
            'data' => $record->load(['pasien', 'praktik']),
        ], 200);
    }

    /**
     * Hapus data rekam medis.
     */
    public function destroy(MedicalRecord $record): JsonResponse
    {
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dihapus.',
        ], 200);
    }
}
