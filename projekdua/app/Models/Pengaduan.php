<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduans';

    protected $fillable = [
        'tanggal',
        'nama_pelapor',
        'jenis_pengaduan',
        'deskripsi',
        'status'
    ];

    protected $dates = ['tanggal', 'created_at', 'updated_at'];
}
