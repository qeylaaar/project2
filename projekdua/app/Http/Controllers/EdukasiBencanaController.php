<?php

namespace App\Http\Controllers;

use App\Models\EdukasiBencana;
use Illuminate\Http\Request;

class EdukasiBencanaController extends Controller
{
    public function index()
    {
        $edukasiBencanas = EdukasiBencana::latest()->get();
        return view('pages.edukasi-bencana.index', compact('edukasiBencanas'));
    }

    public function create()
    {
        return view('pages.edukasi-bencana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_bencana' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,published',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/edukasi'), $filename);
                $data['gambar'] = 'uploads/edukasi/'.$filename;
            }

            EdukasiBencana::create($data);

            return redirect()->route('edukasi-bencana.index')
                ->with('success', 'Data edukasi bencana berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('edukasi-bencana.create')
                ->with('error', 'Gagal menambah data edukasi bencana: ' . $e->getMessage());
        }
    }

    public function show(EdukasiBencana $edukasiBencana)
    {
        return view('pages.edukasi-bencana.show', compact('edukasiBencana'));
    }

    public function edit(EdukasiBencana $edukasiBencana)
    {
        return view('pages.edukasi-bencana.edit', compact('edukasiBencana'));
    }

    public function update(Request $request, EdukasiBencana $edukasiBencana)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_bencana' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'status' => 'required|in:draft,published',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/edukasi'), $filename);
            $data['gambar'] = 'uploads/edukasi/'.$filename;
        }

        $edukasiBencana->update($data);

        return redirect()->route('edukasi-bencana.index')
            ->with('success', 'Data edukasi bencana berhasil diperbarui');
    }

    public function destroy(EdukasiBencana $edukasiBencana)
    {
        $edukasiBencana->delete();

        return redirect()->route('edukasi-bencana.index')
            ->with('success', 'Data edukasi bencana berhasil dihapus');
    }
}
