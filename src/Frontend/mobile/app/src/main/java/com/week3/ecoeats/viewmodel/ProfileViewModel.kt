package com.week3.ecoeats.viewmodel

import android.app.Application
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.google.gson.Gson
import com.week3.ecoeats.data.local.TokenManager
import com.week3.ecoeats.data.model.ErrorResponse
import com.week3.ecoeats.data.model.UpdatePasswordRequest
import com.week3.ecoeats.data.model.UpdateProfileRequest
import com.week3.ecoeats.data.remote.RetrofitInstance
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch
import retrofit2.Response

data class ProfileUiState(
    // data profil yang lagi ditampilkan
    val name: String = "",
    val email: String = "",
    val username: String = "",
    val phone: String = "",
    val isLoading: Boolean = false,
    val errorMessage: String? = null,
    val isLoggedOut: Boolean = false,

    val editName: String = "",
    val editEmail: String = "",
    val editUsername: String = "",
    val editPhone: String = "",
    val isSaving: Boolean = false,
    val saveSuccess: Boolean = false,
    val saveError: String? = null,

    val currentPassword: String = "",
    val newPassword: String = "",
    val newPasswordConfirmation: String = "",
    val isChangingPassword: Boolean = false,
    val passwordChangeSuccess: Boolean = false,
    val passwordError: String? = null
)

class ProfileViewModel(application: Application) : AndroidViewModel(application) {

    private val api = RetrofitInstance.create(application)
    private val tokenManager = TokenManager(application)

    private val _uiState = MutableStateFlow(ProfileUiState())
    val uiState: StateFlow<ProfileUiState> = _uiState.asStateFlow()

    init {
        loadProfile()
    }

    fun loadProfile() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, errorMessage = null) }
            try {
                val response = api.getProfile()
                val user = response.body()?.user
                if (response.isSuccessful && user != null) {
                    _uiState.update {
                        it.copy(
                            name = user.name,
                            email = user.email,
                            username = user.username ?: "",
                            phone = user.phone ?: "",
                            // form edit di-prefill sama data yang lagi ada
                            editName = user.name,
                            editEmail = user.email,
                            editUsername = user.username ?: "",
                            editPhone = user.phone ?: "",
                            isLoading = false
                        )
                    }
                } else {
                    _uiState.update { it.copy(isLoading = false, errorMessage = "Gagal memuat profil") }
                }
            } catch (e: Exception) {
                _uiState.update { it.copy(isLoading = false, errorMessage = e.localizedMessage ?: "Tidak bisa terhubung ke server") }
            }
        }
    }

    fun onEditNameChange(value: String) = _uiState.update { it.copy(editName = value) }
    fun onEditEmailChange(value: String) = _uiState.update { it.copy(editEmail = value) }
    fun onEditUsernameChange(value: String) = _uiState.update { it.copy(editUsername = value) }
    fun onEditPhoneChange(value: String) = _uiState.update { it.copy(editPhone = value) }

    fun saveProfile() {
        viewModelScope.launch {
            val state = _uiState.value
            _uiState.update { it.copy(isSaving = true, saveError = null) }

            try {
                val response = api.updateProfile(
                    UpdateProfileRequest(
                        name = state.editName.ifBlank { null },
                        email = state.editEmail.ifBlank { null },
                        username = state.editUsername.ifBlank { null },
                        phone = state.editPhone.ifBlank { null }
                    )
                )
                val user = response.body()?.user
                if (response.isSuccessful && user != null) {
                    _uiState.update {
                        it.copy(
                            name = user.name,
                            email = user.email,
                            username = user.username ?: "",
                            phone = user.phone ?: "",
                            isSaving = false,
                            saveSuccess = true
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isSaving = false,
                            saveError = parseErrorMessage(response, "Gagal menyimpan profil.")
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isSaving = false,
                        saveError = e.localizedMessage ?: "Tidak bisa terhubung ke server"
                    )
                }
            }
        }
    }

    fun resetSaveSuccess() {
        _uiState.update { it.copy(saveSuccess = false) }
    }

    fun onCurrentPasswordChange(value: String) = _uiState.update { it.copy(currentPassword = value) }
    fun onNewPasswordChange(value: String) = _uiState.update { it.copy(newPassword = value) }
    fun onNewPasswordConfirmationChange(value: String) = _uiState.update { it.copy(newPasswordConfirmation = value) }


    fun changePassword() {
        viewModelScope.launch {
            val state = _uiState.value

            if (state.newPassword != state.newPasswordConfirmation) {
                _uiState.update { it.copy(passwordError = "Konfirmasi password baru tidak sama") }
                return@launch
            }

            _uiState.update { it.copy(isChangingPassword = true, passwordError = null) }

            try {
                val response = api.updatePassword(
                    UpdatePasswordRequest(
                        currentPassword = state.currentPassword,
                        newPassword = state.newPassword,
                        newPasswordConfirmation = state.newPasswordConfirmation
                    )
                )
                if (response.isSuccessful) {
                    tokenManager.clearToken()
                    _uiState.update {
                        it.copy(
                            isChangingPassword = false,
                            passwordChangeSuccess = true,
                            isLoggedOut = true
                        )
                    }
                } else {
                    _uiState.update {
                        it.copy(
                            isChangingPassword = false,
                            passwordError = parseErrorMessage(response, "Gagal ganti password.")
                        )
                    }
                }
            } catch (e: Exception) {
                _uiState.update {
                    it.copy(
                        isChangingPassword = false,
                        passwordError = e.localizedMessage ?: "Tidak bisa terhubung ke server"
                    )
                }
            }
        }
    }


    private fun parseErrorMessage(response: Response<*>, fallback: String): String {
        return try {
            val errorBody = response.errorBody()?.string()
            val errorResponse = Gson().fromJson(errorBody, ErrorResponse::class.java)
            errorResponse?.errors?.values?.firstOrNull()?.firstOrNull()
                ?: errorResponse?.message
                ?: fallback
        } catch (e: Exception) {
            fallback
        }
    }

    fun logout() {
        viewModelScope.launch {
            try {
                api.logout()
            } catch (e: Exception) {
                // gak masalah kalau gagal (misal koneksi putus) — tetep hapus token lokal di bawah
            }
            tokenManager.clearToken()
            _uiState.update { it.copy(isLoggedOut = true) }
        }
    }
}