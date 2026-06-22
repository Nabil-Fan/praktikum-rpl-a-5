package com.week3.ecoeats.data.remote

import com.week3.ecoeats.data.model.AuthResponse
import com.week3.ecoeats.data.model.LoginRequest
import com.week3.ecoeats.data.model.RegisterRequest
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST

/**
 * Path di sini ditulis tanpa "/api/v1/" karena prefix itu sudah
 * dimasukkan ke BASE_URL di RetrofitInstance.
 */
interface AuthApi {

    @POST("auth/register")
    suspend fun register(@Body request: RegisterRequest): Response<AuthResponse>

    @POST("auth/login")
    suspend fun login(@Body request: LoginRequest): Response<AuthResponse>

    @POST("auth/logout")
    suspend fun logout(): Response<AuthResponse>

    @GET("auth/me")
    suspend fun me(): Response<AuthResponse>
}
