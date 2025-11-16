<?php

namespace App\Http\Controllers;

use App\Models\RequestObatInternal;
use App\Models\StokObat;
use Illuminate\Http\Request;

class RequestObatInternalController extends Controller
{
    public function index()
    {
        $requests = RequestObatInternal::with('obat')->get();
        $data = $requests->map(function ($r) {
            return [
                'id' => $r->id,
                'tanggal' => $r->tanggal,
                'obat_id' => $r->obat_id,
                'nama_obat' => $r->obat->nama_obat,
                'jumlah' => $r->jumlah,
                'keterangan' => $r->keterangan,
                'status' => $r->status,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:stok_obat,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $requestObat = RequestObatInternal::create([
            'obat_id' => $validated['obat_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal' => $validated['tanggal'] ?? now(),
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $requestObat]);
    }

    public function destroy($id)
    {
        $requestObat = RequestObatInternal::findOrFail($id);

        if ($requestObat->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya request pending yang bisa dibatalkan.',
            ], 400);
        }

        $requestObat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil dibatalkan.',
        ]);
    }

    public function approve($id)
    {
        $requestObat = RequestObatInternal::findOrFail($id);
        if ($requestObat->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Request sudah diproses.'], 400);
        }

        $obat = StokObat::findOrFail($requestObat->obat_id);
        if ($obat->stok < $requestObat->jumlah) {
            return response()->json(['success' => false, 'message' => 'Stok obat tidak cukup.'], 400);
        }

        $obat->stok -= $requestObat->jumlah;
        $obat->save();

        $requestObat->status = 'approved';
        $requestObat->save();

        return response()->json(['success' => true, 'message' => 'Request disetujui.']);
    }

    public function reject($id)
    {
        $requestObat = RequestObatInternal::findOrFail($id);
        if ($requestObat->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Request sudah diproses.'], 400);
        }

        $requestObat->status = 'rejected';
        $requestObat->save();

        return response()->json(['success' => true, 'message' => 'Request ditolak.']);
    }
}
