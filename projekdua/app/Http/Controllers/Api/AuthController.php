<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Masyarakat;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nama_user' => 'required|string',
                'no_telepon' => 'required|string',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:6',
            ]);

            // Simpan user
            $user = User::create([
                'nama_user' => $request->nama_user,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user' // Set default role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Register berhasil',
                'data' => $user
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in register:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in register:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Masyarakat::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $user,
                'token' => 'dummy-token'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }
}
