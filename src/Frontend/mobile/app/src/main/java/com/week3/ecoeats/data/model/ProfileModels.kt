package com.week3.ecoeats.data.model

import com.google.gson.annotations.SerializedName

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

data class UserProfile(
    val id: Long,
    val name: String,
    val email: String,
    val username: String?,
    val phone: String?,
    val role: String?
)

data class MessageResponse(
    val message: String?
)

data class ErrorResponse(
    val message: String? = null,
    val errors: Map<String, List<String>>? = null
)