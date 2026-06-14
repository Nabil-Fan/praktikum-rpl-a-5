package com.week3.ecoeats.data

data class FoodListing(
    val id: Int,
    val name: String,
    val restoran: String,
    val harga: Int,
    val photoUrl: String    // ← URL dari backend, diload pakai Coil
)