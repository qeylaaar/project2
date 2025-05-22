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
            'status' => 'required|in:draft,published'
        ]);

        EdukasiBencana::create($request->all());

        return redirect()->route('edukasi-bencana.index')
            ->with('success', 'Data edukasi bencana berhasil ditambahkan');
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
            'status' => 'required|in:draft,published'
        ]);

        $edukasiBencana->update($request->all());

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
