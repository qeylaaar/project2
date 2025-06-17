<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pengguna;
use App\Models\EdukasiBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
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
        $kategori_pengaduan = Pengaduan::select('jenis_pengaduan', DB::raw('count(*) as total'))
            ->groupBy('jenis_pengaduan')
            ->get();

        // Ubah format tanggal di edukasi_terbaru
        foreach ($edukasi_terbaru as $edukasi) {
            $edukasi->tanggal = Carbon::parse($edukasi->tanggal);
        }

        // --- Statistik Pengaduan untuk Chart ---
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $jenis = $request->input('jenis_pengaduan');

        $statistikQuery = Pengaduan::query();
        if ($start && $end) {
            $statistikQuery->whereBetween('tanggal', [$start, $end]);
        }
        if ($jenis) {
            $statistikQuery->where('jenis_pengaduan', $jenis);
        }
        $statistik = $statistikQuery->select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('count(*) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        $chart_labels = $statistik->pluck('tanggal')->map(function($tgl) {
            return Carbon::parse($tgl)->format('d/m/Y');
        });
        $chart_data = $statistik->pluck('total');
        // Ambil semua jenis pengaduan untuk filter
        $all_jenis_pengaduan = Pengaduan::select('jenis_pengaduan')->distinct()->pluck('jenis_pengaduan');

        return view('pages.dashboard', [
            'total_pengaduan' => $total_pengaduan,
            'pengaduan_baru' => $pengaduan_baru,
            'pengaduan_diproses' => $pengaduan_diproses,
            'pengaduan_selesai' => $pengaduan_selesai,
            'pengguna_terdaftar' => $pengguna_terdaftar,
            'pengaduan_terbaru' => $pengaduan_terbaru,
            'edukasi_terbaru' => $edukasi_terbaru,
            'kategori_pengaduan' => $kategori_pengaduan,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'all_jenis_pengaduan' => $all_jenis_pengaduan,
        ]);
    }
}
