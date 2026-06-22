package com.week3.ecoeats.data.remote

import com.week3.ecoeats.data.model.CategoryListResponse
import com.week3.ecoeats.data.model.FoodListingListResponse
import com.week3.ecoeats.data.model.FoodListingDetailResponse
import retrofit2.Response
import retrofit2.http.GET
import retrofit2.http.Path
import retrofit2.http.Query

interface FoodListingApi {

    // ── Categories ─────────────────────────────────────────────────────────
    @GET("categories")
    suspend fun getCategories(): Response<CategoryListResponse>

    @GET("categories/{id}")
    suspend fun getCategoryById(@Path("id") id: Int): Response<CategoryListResponse>

    // ── Food Listings ───────────────────────────────────────────────────────
    @GET("food-listings")
    suspend fun getFoodListings(
        @Query("category_id") categoryId: Int? = null,
        @Query("search") search: String? = null
    ): Response<FoodListingListResponse>

    @GET("food-listings/{id}")
    suspend fun getFoodListingById(@Path("id") id: Int): Response<FoodListingDetailResponse>
}
