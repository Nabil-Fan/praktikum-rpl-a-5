package com.week3.ecoeats.viewmodel

import android.app.Application
import androidx.lifecycle.AndroidViewModel
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.AuthRepository
import com.week3.ecoeats.data.AuthResult
import com.week3.ecoeats.data.local.TokenManager
import com.week3.ecoeats.data.remote.RetrofitInstance
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

sealed class AuthUiState {
    object Idle : AuthUiState()
    object Loading : AuthUiState()
    object Success : AuthUiState()
    data class Failure(val message: String) : AuthUiState()
}

class AuthViewModel(application: Application) : AndroidViewModel(application) {

    private val repository = AuthRepository(
        api = RetrofitInstance.create(application),
        tokenManager = TokenManager(application)
    )

    private val _uiState = MutableStateFlow<AuthUiState>(AuthUiState.Idle)
    val uiState: StateFlow<AuthUiState> = _uiState.asStateFlow()

    fun login(email: String, password: String) {
        if (email.isBlank() || password.isBlank()) {
            _uiState.value = AuthUiState.Failure("Email & password wajib diisi")
            return
        }
        _uiState.value = AuthUiState.Loading
        viewModelScope.launch {
            _uiState.value = when (val result = repository.login(email, password)) {
                is AuthResult.Success -> AuthUiState.Success
                is AuthResult.Error -> AuthUiState.Failure(result.message)
            }
        }
    }

    fun register(
        name: String,
        email: String,
        username: String,
        phone: String?,
        password: String,
        passwordConfirmation: String
    ) {
        if (name.isBlank() || email.isBlank() || username.isBlank() || password.isBlank()) {
            _uiState.value = AuthUiState.Failure("Semua field wajib diisi")
            return
        }
        if (password != passwordConfirmation) {
            _uiState.value = AuthUiState.Failure("Konfirmasi password tidak cocok")
            return
        }
        _uiState.value = AuthUiState.Loading
        viewModelScope.launch {
            _uiState.value = when (val result = repository.register(
                name, email, username, phone, password, passwordConfirmation
            )) {
                is AuthResult.Success -> AuthUiState.Success
                is AuthResult.Error -> AuthUiState.Failure(result.message)
            }
        }
    }

    fun resetState() {
        _uiState.value = AuthUiState.Idle
    }
}
