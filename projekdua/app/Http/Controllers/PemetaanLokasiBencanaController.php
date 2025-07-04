<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PemetaanLokasiBencanaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua pengaduan yang punya koordinat lokasi
        $pengaduans = Pengaduan::whereNotNull('alamat')->get();
        $markers = [];
        foreach ($pengaduans as $p) {
            // Parsing koordinat dari field alamat
            $alamat = $p->alamat;
            $lat = null;
            $lng = null;
            if (strpos($alamat, '|||') !== false) {
                $parts = explode('|||', $alamat);
                $coords = isset($parts[1]) ? explode(',', $parts[1]) : null;
                if ($coords && count($coords) == 2) {
                    $lat = floatval(trim($coords[0]));
                    $lng = floatval(trim($coords[1]));
                }
            }
            if ($lat && $lng) {
                $markers[] = [
                    'lat' => $lat,
                    'lng' => $lng,
                    'jenis' => $p->jenis_pengaduan,
                    'nama' => $p->nama_pelapor,
                    'alamat' => $alamat,
                ];
            }
        }
        // Daftar jenis bencana unik untuk dropdown
        $jenisBencanaList = Pengaduan::distinct()->pluck('jenis_pengaduan')->toArray();
        return view('pages.pemetaan.index', compact('markers', 'jenisBencanaList'));
    }
}
