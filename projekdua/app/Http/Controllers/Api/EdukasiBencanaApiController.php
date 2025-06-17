<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EdukasiBencana;
use Illuminate\Http\Request;

class EdukasiBencanaApiController extends Controller
{
    public function index()
    {
        $edukasiBencanas = EdukasiBencana::where('status', 'published')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $edukasiBencanas
        ]);
    }

    public function byJenis($jenis)
    {
        return response()->json(
            EdukasiBencana::whereRaw('LOWER(jenis_bencana) = ?', [strtolower($jenis)])
                ->where('status', 'published')
                ->get()
        );
    }

    public function show($id)
    {
        $edukasi = EdukasiBencana::findOrFail($id);
        return response()->json($edukasi);
    }
}
