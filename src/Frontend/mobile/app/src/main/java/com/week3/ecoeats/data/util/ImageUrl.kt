package com.week3.ecoeats.data.util

import com.week3.ecoeats.data.remote.RetrofitInstance

/**
 * Backend Laravel cuma kasih path relatif buat foto, contoh:
 *   "food-listings/abc123.jpg"
 * Itu hasil Storage::store(), bukan URL lengkap yang bisa di-load Coil/AsyncImage.
 * File aslinya bisa diakses lewat /storage/<path> (symlink storage Laravel),
 * jadi fungsi ini gabungin alamat server (storageBaseUrl, dari RetrofitInstance)
 * + path itu.
 *
 * storageBaseUrl diturunkan dari BASE_URL yang sama dipakai buat API call,
 * jadi kalau BASE_URL diganti (pindah emulator / device lain / domain asli),
 * URL gambar otomatis ikut berubah juga — gak perlu diubah manual di 2 tempat.
 *
 * Kalau path sudah berupa URL lengkap (mulai dari http), dipakai apa adanya.
 */
fun resolvePhotoUrl(rawPath: String?): String {
    if (rawPath.isNullOrBlank()) return ""
    if (rawPath.startsWith("http://") || rawPath.startsWith("https://")) return rawPath
    return RetrofitInstance.storageBaseUrl + rawPath
}
