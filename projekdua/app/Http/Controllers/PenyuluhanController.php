<?php

namespace App\Http\Controllers;

use App\Models\Penyuluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyuluhanController extends Controller
{
    public function index()
    {
        $penyuluhan = Penyuluhan::orderBy('tanggal', 'desc')->get();
        return view('penyuluhan.index', compact('penyuluhan'));
    }

    public function create()
    {
        return view('penyuluhan.create');
    }

    public function store(Request $request)
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Penyuluhan::create($request->all());

        return redirect()->route('penyuluhan.index')
            ->with('success', 'Jadwal penyuluhan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $penyuluhan = Penyuluhan::findOrFail($id);
        return view('penyuluhan.edit', compact('penyuluhan'));
    }

    public function update(Request $request, $id)
    {
        $penyuluhan = Penyuluhan::findOrFail($id);

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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $penyuluhan->update($request->all());

        return redirect()->route('penyuluhan.index')
            ->with('success', 'Jadwal penyuluhan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $penyuluhan = Penyuluhan::findOrFail($id);
        $penyuluhan->delete();

        return redirect()->route('penyuluhan.index')
            ->with('success', 'Jadwal penyuluhan berhasil dihapus');
    }
}