<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = Pengguna::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string'
        ]);

        $user = Pengguna::find($request->user_id);
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil diperbarui'
        ]);
    }
}
