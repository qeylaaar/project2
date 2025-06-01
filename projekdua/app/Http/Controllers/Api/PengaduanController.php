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
            'nama_pelapor' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'jenis_pengaduan' => 'required|string',
            'kecamatan' => 'required|string',
            'desa' => 'required|string',
            'alamat' => 'required|string',
            // tambahkan validasi lain jika perlu
        ]);

        $pengaduan = Pengaduan::create([
            'nama_pelapor' => $request->nama_pelapor,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'jenis_pengaduan' => $request->jenis_pengaduan,
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'alamat' => $request->alamat,
            // tambahkan field lain jika ada
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
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
