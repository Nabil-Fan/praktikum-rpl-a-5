package com.week3.ecoeats.data.local

import android.content.Context
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.map

private val Context.authDataStore by preferencesDataStore(name = "auth_prefs")

/**
 * Nyimpen Sanctum token (plainTextToken dari Laravel) di DataStore lokal.
 */
class TokenManager(private val context: Context) {

    private val tokenKey = stringPreferencesKey("auth_token")

    suspend fun saveToken(token: String) {
        context.authDataStore.edit { prefs -> prefs[tokenKey] = token }
    }

    suspend fun getToken(): String? {
        return context.authDataStore.data.map { prefs -> prefs[tokenKey] }.first()
    }

    suspend fun clearToken() {
        context.authDataStore.edit { prefs -> prefs.remove(tokenKey) }
    }
}
