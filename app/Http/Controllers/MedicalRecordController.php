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
}