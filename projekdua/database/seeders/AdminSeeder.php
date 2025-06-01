<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama_user' => 'Admin',
            'no_telepon' => '08123456789',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin'
        ]);
    }
}
