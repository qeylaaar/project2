<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masyarakat extends Model
{
    use HasFactory;

    protected $table = 'user'; // Nama tabel di database
    protected $primaryKey = 'id_user'; // Primary key
    public $timestamps = false; // Jika tidak ada kolom created_at, updated_at

    protected $fillable = [
        'nama_user', 'no_telepon', 'email', 'password', 'role'
    ];
}
