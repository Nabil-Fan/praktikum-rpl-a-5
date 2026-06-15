<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register user baru (khusus role 'user' — pembeli)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // butuh field password_confirmation
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password), // simpan ke password_hash, bukan password
            'phone'         => $request->phone,
            'role'          => 'user', // selalu 'user' — merchant daftar lewat web
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ], 201);
    }

    /**
     * Login user mobile dan kembalikan token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user, pastikan tidak soft-deleted, dan hanya role 'user'
        $user = User::where('email', $request->email)
                    ->where('role', 'user')
                    ->whereNull('deleted_at')
                    ->first();

        // Cek apakah user ada dan password cocok
        // Ingat: kolom password kita adalah password_hash
        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Hapus token lama (opsional — supaya tidak menumpuk)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Logout — hapus token aktif
     */
    public function logout(Request $request)
    {
        // Hapus hanya token yang dipakai sekarang
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Ambil data profil user yang sedang login
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
