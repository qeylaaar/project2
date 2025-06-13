<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduans';

    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'tanggal',
        'waktu',
        'jenis_pengaduan',
        'kecamatan',
        'desa',
        'alamat',
        'media_uri',
        'media_type',
        'deskripsi',
        'status',
        'feedback',
        'bukti'
    ];

    protected $dates = ['tanggal', 'created_at', 'updated_at'];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
