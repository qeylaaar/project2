<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updatePhoto(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada
                if ($user->foto_profil && file_exists(public_path('storage/' . $user->foto_profil))) {
                    unlink(public_path('storage/' . $user->foto_profil));
                }

                // Upload foto baru
                $file = $request->file('foto_profil');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/profile', $filename);

                $user->foto_profil = 'profile/' . $filename;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui',
                    'data' => [
                        'foto_profil' => asset('storage/' . $user->foto_profil)
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang diupload'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
