package com.week3.ecoeats.data.model

import com.google.gson.annotations.SerializedName

/**
 * Body untuk PUT /api/v1/user/profile.
 * Semua field nullable & default null karena sifatnya partial update —
 * cuma field yang diisi user yang perlu dikirim.
 */
data class UpdateProfileRequest(
    val name: String? = null,
    val email: String? = null,
    val username: String? = null,
    val phone: String? = null
)

/**
 * Body untuk PUT /api/v1/user/update-password.
 * Ketiganya wajib diisi sesuai UpdatePasswordRequest di backend
 * (current_password, new_password min 8 karakter, new_password_confirmation).
 */
data class UpdatePasswordRequest(
    @SerializedName("current_password") val currentPassword: String,
    @SerializedName("new_password") val newPassword: String,
    @SerializedName("new_password_confirmation") val newPasswordConfirmation: String
)

/**
 * Sesuai UserController::showProfile()/updateProfile() — keduanya balikin
 * {"message": "...", "user": {...}}. "message" cuma ada di updateProfile(),
 * tapi dibuat nullable jadi tetap aman dipakai buat showProfile() juga.
 */
data class ProfileResponse(
    val message: String? = null,
    val user: UserProfile?
)

/**
 * Sesuai UserController::formatUserProfile().
 */
data class UserProfile(
    val id: Long,
    val name: String,
    val email: String,
    val username: String?,
    val phone: String?,
    val role: String?
)

/**
 * Sesuai UserController::updatePassword() — sukses maupun gagal
 * (current_password salah) sama-sama cuma balikin {"message": "..."}.
 */
data class MessageResponse(
    val message: String?
)

/**
 * Bentuk error response Laravel buat endpoint ini ada dua kemungkinan:
 * - Gagal validasi FormRequest (UpdateProfileRequest/UpdatePasswordRequest):
 *   {"message": "...", "errors": {"email": ["Email sudah dipakai akun lain."]}}
 * - Gagal manual (current_password salah di updatePassword()):
 *   cuma {"message": "Password saat ini salah."}, tanpa "errors".
 * Class ini nampung keduanya — "errors" nullable karena gak selalu ada.
 */
data class ErrorResponse(
    val message: String? = null,
    val errors: Map<String, List<String>>? = null
)