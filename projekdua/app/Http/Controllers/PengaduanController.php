<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();
        return view('pages.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pages.pengaduan.create');
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

        Pengaduan::create($request->all());

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil ditambahkan');
    }

    public function show(Pengaduan $pengaduan)
    {
        return view('pages.pengaduan.show', compact('pengaduan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        return view('pages.pengaduan.edit', compact('pengaduan'));
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

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }
}
