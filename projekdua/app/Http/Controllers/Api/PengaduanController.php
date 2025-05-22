<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $pengaduans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pelapor' => 'required|string|max:255',
            'jenis_pengaduan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Menunggu,Proses,Selesai'
        ]);

        $pengaduan = Pengaduan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil ditambahkan',
            'data' => $pengaduan
        ], 201);
    }

    public function show(Pengaduan $pengaduan)
    {
        return response()->json([
            'success' => true,
            'data' => $pengaduan
        ]);
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pelapor' => 'required|string|max:255',
            'jenis_pengaduan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Menunggu,Proses,Selesai'
        ]);

        $pengaduan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diperbarui',
            'data' => $pengaduan
        ]);
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dihapus'
        ]);
    }
}
