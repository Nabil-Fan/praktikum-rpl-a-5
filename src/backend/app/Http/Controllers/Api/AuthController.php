<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController menangani registrasi, login, logout, dan cek profil user mobile.
 * Validasi input didelegasikan ke Form Request terpisah agar controller tetap ramping.
 */
class AuthController extends Controller
{
    /**
     * Daftarkan user baru dengan role 'user' (pembeli).
     * Merchant daftar lewat portal web, bukan lewat endpoint ini.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $newUser = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'username'      => $request->username,
            'phone'         => $request->phone,
            'password_hash' => Hash::make($request->password),
            'role'          => UserRole::USER,
        ]);

        $accessToken = $newUser->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token'   => $accessToken,
            'user'    => $this->formatUserData($newUser),
        ], 201);
    }

    /**
     * Login user mobile dan kembalikan Sanctum token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)
                    ->where('role', UserRole::USER)
                    ->whereNull('deleted_at')
                    ->first();

        $isPasswordValid = $user && Hash::check($request->password, $user->password_hash);

        if (! $isPasswordValid) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // Hapus token lama supaya tidak menumpuk
        $user->tokens()->delete();

        $accessToken = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token'   => $accessToken,
            'user'    => $this->formatUserData($user),
        ]);
    }

    /**
     * Logout — hapus token yang sedang dipakai.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Ambil data profil user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $this->formatUserData($request->user()),
        ]);
    }

    /**
     * Format data user menjadi array yang konsisten untuk semua response.
     * Satu tempat definisi — tidak ada duplikasi field di register, login, dan me.
     */
    private function formatUserData(User $user): array
    {
        return [
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'username' => $user->username,
            'phone'    => $user->phone,
            'role'     => $user->role->value,
        ];
    }
}
