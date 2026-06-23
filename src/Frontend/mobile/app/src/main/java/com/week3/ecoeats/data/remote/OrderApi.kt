package com.week3.ecoeats.data.remote

import com.week3.ecoeats.data.model.CreateOrderRequest
import com.week3.ecoeats.data.model.CreateOrderResponse
import com.week3.ecoeats.data.model.OrderDetailResponse
import com.week3.ecoeats.data.model.OrderListResponse
import com.week3.ecoeats.data.model.UploadPaymentProofResponse
import okhttp3.MultipartBody
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Multipart
import retrofit2.http.POST
import retrofit2.http.Part
import retrofit2.http.Path

interface OrderApi {

    @POST("orders")
    suspend fun createOrder(@Body request: CreateOrderRequest): Response<CreateOrderResponse>

    @GET("orders")
    suspend fun getOrders(): Response<OrderListResponse>

    @GET("orders/{id}")
    suspend fun getOrderDetail(@Path("id") id: Int): Response<OrderDetailResponse>

    @Multipart
    @POST("orders/{orderId}/payment-proof")
    suspend fun uploadPaymentProof(
        @Path("orderId") orderId: Int,
        @Part proof: MultipartBody.Part
    ): Response<UploadPaymentProofResponse>

    @POST("orders/{orderId}/init-payment")
    suspend fun initPayment(
        @Path("orderId") orderId: Int
    ): Response<Unit>
}