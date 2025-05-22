<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyuluhanController extends Controller
{
    public function apiIndex()
    {
        $penyuluhan = Penyuluhan::orderBy('tanggal', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $penyuluhan
        ]);
    }

    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string',
            'pemateri' => 'required|string',
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $penyuluhan = Penyuluhan::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal penyuluhan berhasil ditambahkan',
            'data' => $penyuluhan
        ], 201);
    }

    public function apiShow($id)
    {
        $penyuluhan = Penyuluhan::find($id);

        if (!$penyuluhan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal penyuluhan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $penyuluhan
        ]);
    }

    public function apiUpdate(Request $request, $id)
    {
        $penyuluhan = Penyuluhan::find($id);

        if (!$penyuluhan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal penyuluhan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'string|max:255',
            'deskripsi' => 'string',
            'tanggal' => 'date',
            'waktu' => 'string',
            'lokasi' => 'string',
            'pemateri' => 'string',
            'status' => 'in:aktif,selesai,dibatalkan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $penyuluhan->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal penyuluhan berhasil diperbarui',
            'data' => $penyuluhan
        ]);
    }

    public function apiDestroy($id)
    {
        $penyuluhan = Penyuluhan::find($id);

        if (!$penyuluhan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal penyuluhan tidak ditemukan'
            ], 404);
        }

        $penyuluhan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal penyuluhan berhasil dihapus'
        ]);
    }
}
