<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdukasiBencana extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'jenis_bencana',
        'tanggal',
        'status'
    ];

    protected $dates = ['tanggal'];
}
