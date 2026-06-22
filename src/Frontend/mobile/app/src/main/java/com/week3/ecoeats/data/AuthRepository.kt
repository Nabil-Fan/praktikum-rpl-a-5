package com.week3.ecoeats.data

import com.google.gson.Gson
import com.week3.ecoeats.data.local.TokenManager
import com.week3.ecoeats.data.model.ApiErrorResponse
import com.week3.ecoeats.data.model.AuthResponse
import com.week3.ecoeats.data.model.LoginRequest
import com.week3.ecoeats.data.model.RegisterRequest
import com.week3.ecoeats.data.remote.AuthApi
import retrofit2.Response

sealed class AuthResult {
    data class Success(val response: AuthResponse) : AuthResult()
    data class Error(val message: String) : AuthResult()
}

class AuthRepository(
    private val api: AuthApi,
    private val tokenManager: TokenManager
) {

    suspend fun register(
        name: String,
        email: String,
        username: String,
        phone: String?,
        password: String,
        passwordConfirmation: String
    ): AuthResult = safeCall {
        api.register(
            RegisterRequest(
                name = name,
                email = email,
                username = username,
                phone = phone,
                password = password,
                password_confirmation = passwordConfirmation
            )
        )
    }

    suspend fun login(email: String, password: String): AuthResult = safeCall {
        api.login(LoginRequest(email = email, password = password))
    }

    private suspend fun safeCall(
        block: suspend () -> Response<AuthResponse>
    ): AuthResult {
        return try {
            val response = block()
            val body = response.body()

            if (response.isSuccessful && body != null) {
                body.token?.let { tokenManager.saveToken(it) }
                AuthResult.Success(body)
            } else {
                AuthResult.Error(extractErrorMessage(response))
            }
        } catch (e: Exception) {
            AuthResult.Error(e.localizedMessage ?: "Tidak bisa terhubung ke server")
        }
    }

    private fun extractErrorMessage(response: Response<AuthResponse>): String {
        val rawError = response.errorBody()?.string()
        val parsed = try {
            Gson().fromJson(rawError, ApiErrorResponse::class.java)
        } catch (e: Exception) {
            null
        }
        val firstFieldError = parsed?.errors?.values?.firstOrNull()?.firstOrNull()
        return firstFieldError ?: parsed?.message ?: "Terjadi kesalahan (${response.code()})"
    }
}
