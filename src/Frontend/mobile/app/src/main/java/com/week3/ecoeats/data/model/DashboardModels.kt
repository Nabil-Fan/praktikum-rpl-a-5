package com.week3.ecoeats.data.model
import com.week3.ecoeats.data.model.Category
import com.week3.ecoeats.data.model.FoodListing

import com.google.gson.annotations.SerializedName

// ── Category ────────────────────────────────────────────────────────────────

data class Category(
    val id: Int,
    val name: String,
    val slug: String? = null,
    @SerializedName("created_at") val createdAt: String? = null
)

data class CategoryListResponse(
    val success: Boolean,
    val data: List<Category>,
    val message: String? = null
)

// ── Merchant

data class MerchantProfile(
    val id: Int,
    @SerializedName("business_name")
    val storeName: String,
    @SerializedName("business_address")
    val businessAddress: String? = null,
    val latitude: String? = null,
    val longitude: String? = null
)
// ── FoodListing ─────────────────────────────────────────────────────────────

data class FoodListing(
    val id: Int,
    val name: String,
    @SerializedName("original_price") val originalPrice: Double,
    @SerializedName("discount_price") val discountedPrice: Double,
    @SerializedName("stock_qty") val stock: Int,
    val status: String,
    val description: String? = null,
    @SerializedName("pickup_start") val pickupStart: String? = null,
    @SerializedName("pickup_end") val pickupEnd: String? = null,
    @SerializedName("photo_url") val imageUrl: String? = null,
    val category: Category? = null,
    val merchant: MerchantProfile? = null,
    @SerializedName("created_at") val createdAt: String? = null
)

data class FoodListingListResponse(
    val success: Boolean,
    val data: List<FoodListing>,
    val message: String? = null
)

data class FoodListingDetailResponse(
    val success: Boolean,
    val data: FoodListing,
    val message: String? = null
)
