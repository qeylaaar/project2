<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('pages.pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('pages.pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'no_telepon' => 'required|string|max:20',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        Pengguna::create($data);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil ditambahkan');
    }

    public function show(Pengguna $pengguna)
    {
        return view('pages.pengguna.show', compact('pengguna'));
    }

    public function edit(Pengguna $pengguna)
    {
        return view('pages.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $pengguna->id_user . ',id_user',
            'no_telepon' => 'required|string|max:20',
            'role' => 'required|in:admin,user'
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus');
    }
}
