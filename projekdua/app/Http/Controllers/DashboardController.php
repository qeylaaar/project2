<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pengguna;
use App\Models\EdukasiBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pengaduan
        $total_pengaduan = Pengaduan::count();

        // Pengaduan Baru Hari Ini
        $pengaduan_baru = Pengaduan::whereDate('created_at', now()->toDateString())->count();

        // Pengaduan Diproses
        $pengaduan_diproses = Pengaduan::where('status', 'Proses')->count();

        // Pengaduan Selesai
        $pengaduan_selesai = Pengaduan::where('status', 'Selesai')->count();

        // Pengguna Terdaftar
        $pengguna_terdaftar = Pengguna::count();

        // Pengaduan Terbaru (untuk tabel)
        $pengaduan_terbaru = Pengaduan::latest()->take(5)->get();

        // Edukasi Terbaru
        $edukasi_terbaru = EdukasiBencana::latest()->take(5)->get();

        // Kategori Pengaduan
        $kategori_pengaduan = Pengaduan::select('jenis_pengaduan', \DB::raw('count(*) as total'))
            ->groupBy('jenis_pengaduan')
            ->get();

        return view('pages.dashboard', [
            'total_pengaduan' => $total_pengaduan,
            'pengaduan_baru' => $pengaduan_baru,
            'pengaduan_diproses' => $pengaduan_diproses,
            'pengaduan_selesai' => $pengaduan_selesai,
            'pengguna_terdaftar' => $pengguna_terdaftar,
            'pengaduan_terbaru' => $pengaduan_terbaru,
            'edukasi_terbaru' => $edukasi_terbaru,
            'kategori_pengaduan' => $kategori_pengaduan,
        ]);
    }
}
