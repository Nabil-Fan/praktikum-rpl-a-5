package com.week3.ecoeats.data.remote

import android.content.Context
import com.week3.ecoeats.data.local.TokenManager
import kotlinx.coroutines.runBlocking
import okhttp3.Interceptor
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import kotlin.jvm.java

object RetrofitInstance {

    private const val BASE_URL = "http://127.0.0.1:8000/api/v1/"

    // Diturunkan otomatis dari BASE_URL di atas — supaya cuma ada SATU tempat
    // yang nyimpen alamat server. Dipakai buat gabungin path foto dari backend
    // (misal "food-listings/abc.jpg") jadi URL lengkap yang bisa di-load Coil.

    val storageBaseUrl: String
        get() = BASE_URL.substringBefore("/api/") + "/storage/"

    // Shared OkHttpClient builder
    private fun buildClient(context: Context): OkHttpClient {
        val tokenManager = TokenManager(context)

        val authInterceptor = Interceptor { chain ->
            val token = runBlocking { tokenManager.getToken() }
            val request = chain.request().newBuilder()
                .addHeader("Accept", "application/json")
                .apply { if (!token.isNullOrBlank()) addHeader("Authorization", "Bearer $token") }
                .build()
            chain.proceed(request)
        }

        val logging = HttpLoggingInterceptor().apply {
            level = HttpLoggingInterceptor.Level.BODY
        }

        return OkHttpClient.Builder()
            .addInterceptor(authInterceptor)
            .addInterceptor(logging)
            .build()
    }

    // Retrofit factory ────────────────────────────────────────────────────────
    private fun buildRetrofit(context: Context): Retrofit =
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(buildClient(context))
            .addConverterFactory(GsonConverterFactory.create())
            .build()

    // API instances ───────────────────────────────────────────────────────────
    fun create(context: Context): AuthApi =
        buildRetrofit(context).create(AuthApi::class.java)

    fun createFoodListingApi(context: Context): FoodListingApi =
        buildRetrofit(context).create(FoodListingApi::class.java)

    // TODO: endpoint order di backend belum ada, jadi OrderApi ini belum
    // pernah dipanggil sungguhan (OrderRepository masih full simulasi).
    // Begitu OrderController & routes/api.php untuk order sudah jadi,
    // tidak perlu ubah apapun di sini — cukup uncomment kode asli di
    // OrderRepository.kt, method ini sudah siap dipakai.
    fun createOrderApi(context: Context): OrderApi =
        buildRetrofit(context).create(OrderApi::class.java)
}