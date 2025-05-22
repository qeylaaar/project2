<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyuluhan extends Model
{
    use HasFactory;

    protected $table = 'penyuluhan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal',
        'waktu',
        'lokasi',
        'pemateri',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime',
    ];
}
