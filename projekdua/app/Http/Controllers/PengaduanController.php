<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::query();

        // Filter berdasarkan jenis pengaduan
        if ($request->has('jenis_pengaduan') && $request->jenis_pengaduan != '') {
            $query->where('jenis_pengaduan', $request->jenis_pengaduan);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nama pelapor
        if ($request->has('nama_pelapor') && $request->nama_pelapor != '') {
            $query->where('nama_pelapor', 'like', '%' . $request->nama_pelapor . '%');
        }

        $pengaduans = $query->latest()->get();

        // Untuk dropdown filter
        $jenisPengaduan = Pengaduan::distinct()->pluck('jenis_pengaduan');
        $statuses = ['Menunggu', 'Proses', 'Selesai'];

        return view('pages.pengaduan.index', compact('pengaduans', 'jenisPengaduan', 'statuses'));
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
        $pengaduan->load('user');
        $mediaUris = json_decode($pengaduan->media_uri, true) ?? [];
        $mediaTypes = json_decode($pengaduan->media_type, true) ?? [];
        return view('pages.pengaduan.show', compact('pengaduan', 'mediaUris', 'mediaTypes'));
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
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'feedback' => 'nullable|string',
            'bukti.*' => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240'
        ]);

        $data = $request->except('bukti');

        $buktiLama = [];
        if ($pengaduan->bukti) {
            $buktiLama = json_decode($pengaduan->bukti, true) ?? [];
        }
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $buktiLama[] = $file->store('bukti', 'public');
            }
        }
        $data['bukti'] = json_encode($buktiLama);

        $pengaduan->update($data);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    public function uploadMedia(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('public/uploads');
            $url = asset(str_replace('public/', 'storage/', $path));
            return response()->json(['success' => true, 'url' => $url]);
        }
        return response()->json(['success' => false, 'error' => 'No file uploaded']);
    }

    public function printRekap(Request $request)
    {
        $query = Pengaduan::query();

        // Filter berdasarkan jenis pengaduan
        if ($request->has('jenis_pengaduan') && $request->jenis_pengaduan != '') {
            $query->where('jenis_pengaduan', $request->jenis_pengaduan);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nama pelapor
        if ($request->has('nama_pelapor') && $request->nama_pelapor != '') {
            $query->where('nama_pelapor', 'like', '%' . $request->nama_pelapor . '%');
        }

        $pengaduans = $query->latest()->get();

        return view('pages.pengaduan.print', compact('pengaduans'));
    }
}
