package com.week3.ecoeats.data.remote

import com.week3.ecoeats.data.model.CreateOrderRequest
import com.week3.ecoeats.data.model.OrderResponse
import com.week3.ecoeats.data.model.OrderSummaryDto
import com.week3.ecoeats.data.model.PaymentMethod
import okhttp3.MultipartBody

sealed class OrderResult {
    data class Success(val order: OrderResponse) : OrderResult()
    data class Error(val message: String) : OrderResult()
}

sealed class OrderListResult {
    data class Success(val orders: List<OrderSummaryDto>) : OrderListResult()
    data class Error(val message: String) : OrderListResult()
}

sealed class UploadProofResult {
    data class Success(val proofUrl: String?) : UploadProofResult()
    data class Error(val message: String) : UploadProofResult()
}

/**
 * Repository untuk fitur Order: buat order, cek status (polling), lihat
 * riwayat, dan upload bukti pembayaran. Semua method di sini memanggil
 * OrderApi (interface terpisah di OrderApi.kt) — tidak ada duplikasi
 * interface OrderApi di file ini.
 */
class OrderRepository(private val api: OrderApi) {

    suspend fun createOrder(
        foodListingId: Int,
        quantity: Int,
        paymentMethod: PaymentMethod
    ): OrderResult = safeCall {
        val response = api.createOrder(
            CreateOrderRequest(
                food_listing_id = foodListingId,
                quantity = quantity,
                payment_method = paymentMethod.apiValue
            )
        )
        val order = response.body()?.order
        if (response.isSuccessful && order != null) {
            OrderResult.Success(order)
        } else {
            OrderResult.Error("Gagal membuat order (${response.code()})")
        }
    }

    /**
     * Dipakai untuk polling status order (menunggu verifikasi merchant)
     * dan untuk ambil detail lengkap di CheckoutScreen.
     */
    suspend fun getOrderDetail(orderId: Int): OrderResult = safeCall {
        val response = api.getOrderDetail(orderId)
        val order = response.body()?.order
        if (response.isSuccessful && order != null) {
            OrderResult.Success(order)
        } else {
            OrderResult.Error("Gagal memuat detail order (${response.code()})")
        }
    }

    /**
     * Riwayat Pesanan — belum dipakai sekarang, disiapkan untuk nanti.
     */
    suspend fun getOrders(): OrderListResult = try {
        val response = api.getOrders()
        val orders = response.body()?.orders
        if (response.isSuccessful && orders != null) {
            OrderListResult.Success(orders)
        } else {
            OrderListResult.Error("Gagal memuat riwayat pesanan (${response.code()})")
        }
    } catch (e: Exception) {
        OrderListResult.Error(e.localizedMessage ?: "Tidak bisa terhubung ke server")
    }

    suspend fun uploadPaymentProof(
        orderId: Int,
        proof: MultipartBody.Part
    ): UploadProofResult = try {
        val response = api.uploadPaymentProof(orderId, proof)
        val body = response.body()
        if (response.isSuccessful && body != null) {
            UploadProofResult.Success(body.payment_proof_url)
        } else {
            UploadProofResult.Error("Gagal mengunggah bukti pembayaran (${response.code()})")
        }
    } catch (e: Exception) {
        UploadProofResult.Error(e.localizedMessage ?: "Tidak bisa terhubung ke server")
    }

    private suspend fun safeCall(block: suspend () -> OrderResult): OrderResult = try {
        block()
    } catch (e: Exception) {
        OrderResult.Error(e.localizedMessage ?: "Tidak bisa terhubung ke server")
    }
}