package com.week3.ecoeats.data.model

import com.google.gson.annotations.SerializedName

/**
 * Sesuai constant STATUS_* di app/Models/Order.php (backend).
 */
enum class OrderStatus {
    @SerializedName("pending") PENDING,
    @SerializedName("confirmed") CONFIRMED,
    @SerializedName("ready") READY,
    @SerializedName("completed") COMPLETED,
    @SerializedName("rejected") REJECTED,
    @SerializedName("expired") EXPIRED
}

/**
 * Metode pembayaran yang dikirim ke backend.
 * TODO: cek app/Enums/PaymentMethod.php — pastiin value string
 * "qris", "transfer", "ewallet" cocok persis sama yang backend terima.
 */
enum class PaymentMethod(val apiValue: String) {
    QRIS("qris"),
    TRANSFER("transfer"),
    EWALLET("ewallet")
}

/** Body POST /orders */
data class CreateOrderRequest(
    val food_listing_id: Int,
    val quantity: Int,
    val payment_method: String
)

/** Response POST /orders -> { "message": "...", "order": {...} } */
data class CreateOrderResponse(
    val message: String? = null,
    val order: OrderResponse? = null
)

/** Response GET /orders/{id} -> { "order": {...} } */
data class OrderDetailResponse(
    val order: OrderResponse? = null
)

/** Response GET /orders -> { "message"?: "...", "orders": [...] } */
data class OrderListResponse(
    val message: String? = null,
    val orders: List<OrderSummaryDto> = emptyList()
)

data class OrderSummaryDto(
    val id: Int,
    val pickup_code: String?,
    val status: OrderStatus,
    val total_amount: Double,
    val payment_method: String?,
    val ordered_at: String?,
    val merchant_name: String?,
    val item_name: String?,
    val item_quantity: Int?
)

data class OrderMerchantDto(
    val id: Int?,
    val business_name: String?,
    val business_address: String?,
    val latitude: String?,
    val longitude: String?
)

data class OrderItemDto(
    val id: Int,
    val listing_name: String,
    val quantity: Int,
    val unit_price: Double,
    val subtotal: Double
)

data class OrderPaymentDto(
    val gateway: String?,
    val status: String?,
    val payment_proof_url: String?
)

/**
 * Sesuai formatOrderDetail() di OrderController.php.
 * adminFee dihitung dari selisih total_amount - jumlah subtotal item.
 */
data class OrderResponse(
    val id: Int,
    val pickup_code: String?,
    val status: OrderStatus,
    val total_amount: Double,
    val payment_method: String?,
    val ordered_at: String?,
    val expires_at: String?,
    val confirmed_at: String?,
    val completed_at: String?,
    val rejected_at: String?,
    val merchant: OrderMerchantDto?,
    val items: List<OrderItemDto> = emptyList(),
    val payment: OrderPaymentDto?
) {
    val itemsSubtotal: Double
        get() = items.sumOf { it.subtotal }

    val adminFee: Double
        get() = (total_amount - itemsSubtotal).coerceAtLeast(0.0)
}

/** Response POST /orders/{id}/payment-proof */
data class UploadPaymentProofResponse(
    val message: String? = null,
    val payment_proof_url: String? = null
)