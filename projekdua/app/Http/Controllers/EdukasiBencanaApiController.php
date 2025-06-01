<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EdukasiBencana;
use Illuminate\Http\Request;

class EdukasiBencanaApiController extends Controller
{
    public function index()
    {
        return response()->json(EdukasiBencana::all());
    }

    public function byJenis($jenis)
    {
        return response()->json(EdukasiBencana::where('jenis_bencana', $jenis)->get());
    }
}
