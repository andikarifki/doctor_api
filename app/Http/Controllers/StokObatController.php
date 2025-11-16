<?php

namespace App\Http\Controllers;

use App\Models\RequestObatInternal;
use App\Models\StokObat;
use Illuminate\Http\Request;

class StokObatController extends Controller
{
    // Tampilkan semua stok obat
    public function index()
    {
        $obat = StokObat::all();

        return response()->json([
            'success' => true,
            'data' => $obat,
        ]);
    }

    // Tambah obat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'stok' => 'required|integer',
        ]);

        $obat = StokObat::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Obat berhasil ditambahkan',
            'data' => $obat,
        ]);
    }

    // Tampilkan detail obat
    public function show($id)
    {
        $obat = StokObat::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $obat,
        ]);
    }

    // Update obat
    public function update(Request $request, $id)
    {
        $obat = StokObat::findOrFail($id);

        $request->validate([
            'nama_obat' => 'sometimes|string',
            'stok' => 'sometimes|integer',
        ]);

        $obat->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Obat berhasil diupdate',
            'data' => $obat,
        ]);
    }

    // Hapus obat
    public function destroy($id)
    {
        $obat = StokObat::findOrFail($id);
        $obat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Obat berhasil dihapus',
        ]);
    }

    public function approve($id)
    {
        $request = RequestObatInternal::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Request sudah diproses']);
        }

        $request->status = 'approved';
        $request->save();

        // Tambahkan ke stok obat
        $stok = StokObat::find($request->obat_id);
        if ($stok) {
            $stok->stok += $request->jumlah;
            $stok->save();
        }

        return response()->json(['success' => true, 'message' => 'Request disetujui dan stok diperbarui']);
    }

    public function reject($id)
    {
        $request = RequestObatInternal::findOrFail($id);

        if ($request->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Request sudah diproses']);
        }

        $request->status = 'rejected';
        $request->save();

        return response()->json(['success' => true, 'message' => 'Request ditolak']);
    }
}
