package com.week3.ecoeats.data.remote

import com.week3.ecoeats.data.model.Category
import com.week3.ecoeats.data.model.FoodListing
import com.week3.ecoeats.data.remote.FoodListingApi

sealed class Result<out T> {
    data class Success<T>(val data: T) : Result<T>()
    data class Error(val message: String) : Result<Nothing>()
    object Loading : Result<Nothing>()
}

class DashboardRepository(private val api: FoodListingApi) {

    suspend fun getCategories(): Result<List<Category>> = safeCall {
        val response = api.getCategories()
        if (response.isSuccessful) {
            Result.Success(response.body()?.data ?: emptyList())
        } else {
            Result.Error("Gagal memuat kategori: ${response.code()}")
        }
    }

    suspend fun getFoodListings(
        categoryId: Int? = null,
        search: String? = null
    ): Result<List<FoodListing>> = safeCall {
        val response = api.getFoodListings(categoryId, search)
        if (response.isSuccessful) {
            Result.Success(response.body()?.data ?: emptyList())
        } else {
            Result.Error("Gagal memuat menu: ${response.code()}")
        }
    }

    suspend fun getFoodListingById(id: Int): Result<FoodListing> = safeCall {
        val response = api.getFoodListingById(id)
        if (response.isSuccessful && response.body()?.data != null) {
            Result.Success(response.body()!!.data)
        } else {
            Result.Error("Menu tidak ditemukan")
        }
    }

    private inline fun <T> safeCall(block: () -> Result<T>): Result<T> = try {
        block()
    } catch (e: Exception) {
        Result.Error(e.localizedMessage ?: "Terjadi kesalahan")
    }
}
