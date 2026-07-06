package com.week3.ecoeats.data.model

/**
 * Body request untuk POST /api/v1/auth/register.
 * password_confirmation diasumsikan wajib (pola rule "confirmed" di Laravel) —
 */

data class RegisterRequest(
    val name: String,
    val email: String,
    val username: String,
    val phone: String?,
    val password: String,
    val password_confirmation: String
)

/**
 * Body request untuk POST /api/v1/auth/login.
 */
data class LoginRequest(
    val email: String,
    val password: String
)

data class AuthResponse(
    val message: String? = null,
    val token: String? = null,
    val user: UserDto? = null
)

data class UserDto(
    val id: Int,
    val name: String,
    val email: String,
    val username: String?,
    val phone: String?,
    val role: String
)

/**
 * Bentuk error standar Laravel saat validasi gagal (HTTP 422):
 * { "message": "...", "errors": { "email": ["..."] } }
 */
data class ApiErrorResponse(
    val message: String? = null,
    val errors: Map<String, List<String>>? = null
)
