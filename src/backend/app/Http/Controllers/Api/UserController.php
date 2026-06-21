<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * UserController menangani profil user mobile.
 * Sesuai UI: tampil profil, edit profil (nama/email/username), dan ganti password.
 */
class UserController extends Controller
{
    /**
     * Tampilkan profil user yang sedang login.
     * GET /api/v1/user/profile
     */
    public function showProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => $this->formatUserProfile($user),
        ]);
    }

    /**
     * Perbarui data profil user.
     * PUT /api/v1/user/profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->only(['name', 'email', 'username', 'phone']));

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user'    => $this->formatUserProfile($user->fresh()),
        ]);
    }

    /**
     * Ganti password user.
     * PUT /api/v1/user/password
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        $isCurrentPasswordValid = Hash::check($request->current_password, $user->password_hash);

        if (! $isCurrentPasswordValid) {
            return response()->json([
                'message' => 'Password saat ini salah.',
            ], 422);
        }

        $user->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        // Hapus semua token lama setelah ganti password — paksa login ulang
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password berhasil diubah. Silakan login kembali.',
        ]);
    }

    /**
     * Format data profil user secara konsisten.
     * Satu tempat — dipakai di showProfile dan updateProfile.
     */
    private function formatUserProfile($user): array
    {
        return [
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'username' => $user->username,
            'phone'    => $user->phone,
            'role'     => $user->role,
        ];
    }
}
