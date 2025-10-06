<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use App\Models\Patient; // Import model Patient

class MedicalRecordController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data Riwayat Medis
        $validatedData = $request->validate([
            'pasien_id' => 'required|exists:pasien,id', // Wajib ada dan harus ada di tabel 'patients'
            'tanggal_periksa' => 'required|date',

            'diagnosis' => 'required|string',
            'obat' => 'required|string',
            'lokasi_berobat' => 'required|string',
        ]);

        // 2. Buat Riwayat Medis baru
        $record = MedicalRecord::create($validatedData);

        // 3. Beri respons sukses
        return response()->json($record, 201);
    }

    public function update(Request $request, MedicalRecord $record)
    {
        // 1. Validasi data yang akan diupdate
        $validatedData = $request->validate([
            // Rule 'sometimes' digunakan agar field tidak wajib diisi dalam request
            // kecuali jika Anda ingin membuatnya wajib diupdate. 
            // 'exists' tetap harus dipertahankan untuk memastikan pasien_id valid jika dikirim.
            'pasien_id' => 'sometimes|required|exists:pasien,id',
            'tanggal_periksa' => 'sometimes|required|date',
            'diagnosis' => 'sometimes|required|string',
            'obat' => 'sometimes|required|string',
            'lokasi_berobat' => 'sometimes|required|string',
        ]);

        // 2. Perbarui Riwayat Medis
        $record->update($validatedData);

        // 3. Beri respons sukses (HTTP 200 OK) dengan data yang telah diperbarui
        return response()->json($record, 200);
    }
    public function destroy(MedicalRecord $record)
    {
        // Hapus Riwayat Medis dari database
        $record->delete();

        // Beri respons sukses tanpa konten (HTTP 204 No Content)
        return response()->json(null, 204);
    }
}