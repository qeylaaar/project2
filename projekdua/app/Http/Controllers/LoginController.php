<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Log untuk debugging
        Log::info('Login attempt', [
            'email' => $request->email,
            'credentials' => $credentials
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil data user
            $user = Auth::user();

            // Log untuk debugging
            Log::info('User login attempt', [
                'email' => $request->email,
                'role' => $user->role ?? 'no role',
                'user_id' => $user->id_user ?? 'no id',
                'session_id' => session()->getId()
            ]);

            // Cek role admin
            if ($user && $user->role === 'admin') {
                Log::info('Admin login successful', [
                    'user_id' => $user->id_user,
                    'role' => $user->role
                ]);
                return redirect()->route('dashboard')->with('success', 'Selamat datang! Login berhasil.');
            } else {
                Log::warning('Non-admin login attempt', [
                    'user_id' => $user->id_user,
                    'role' => $user->role
                ]);
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses hanya untuk admin.',
                ]);
            }
        }

        Log::warning('Login failed', [
            'email' => $request->email
        ]);

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan tidak sesuai.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
