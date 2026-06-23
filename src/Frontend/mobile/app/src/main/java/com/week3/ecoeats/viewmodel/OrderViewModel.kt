package com.week3.ecoeats.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.model.OrderResponse
import com.week3.ecoeats.data.model.OrderStatus
import com.week3.ecoeats.data.model.PaymentMethod
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.OrderResult
import com.week3.ecoeats.data.remote.UploadProofResult
import kotlinx.coroutines.delay
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.MultipartBody
import okhttp3.RequestBody.Companion.asRequestBody
import java.io.File

sealed class OrderUiState {
    object Idle : OrderUiState()
    object Loading : OrderUiState()
    data class Resolved(val order: OrderResponse) : OrderUiState()
    data class Error(val message: String) : OrderUiState()
}

sealed class UploadProofUiState {
    object Idle : UploadProofUiState()
    object Loading : UploadProofUiState()
    data class Success(val proofUrl: String?) : UploadProofUiState()
    data class Error(val message: String) : UploadProofUiState()
}

class OrderViewModel(private val repository: OrderRepository) : ViewModel() {

    private val _uiState = MutableStateFlow<OrderUiState>(OrderUiState.Idle)
    val uiState: StateFlow<OrderUiState> = _uiState.asStateFlow()

    private val _uploadState = MutableStateFlow<UploadProofUiState>(UploadProofUiState.Idle)
    val uploadState: StateFlow<UploadProofUiState> = _uploadState.asStateFlow()

    fun createOrder(foodListingId: Int, quantity: Int, paymentMethod: PaymentMethod) {
        _uiState.value = OrderUiState.Loading
        viewModelScope.launch {
            when (val result = repository.createOrder(foodListingId, quantity, paymentMethod)) {
                is OrderResult.Success -> _uiState.value = OrderUiState.Resolved(result.order)
                is OrderResult.Error -> _uiState.value = OrderUiState.Error(result.message)
            }
        }
    }

    /**
     * Fetch order sekali tanpa polling. Dipakai di OrderDetailScreen dan
     * QrCodeScreen, karena di titik itu order sudah confirmed — tidak perlu
     * tunggu status berubah.
     */
    fun loadOrder(orderId: Int) {
        _uiState.value = OrderUiState.Loading
        viewModelScope.launch {
            when (val result = repository.getOrderDetail(orderId)) {
                is OrderResult.Success -> _uiState.value = OrderUiState.Resolved(result.order)
                is OrderResult.Error -> _uiState.value = OrderUiState.Error(result.message)
            }
        }
    }

    /**
     * Poll terus sampai status bukan PENDING. Dipakai di WaitingVerification
     * dan PaymentScreen — selama merchant belum konfirmasi, kita tunggu.
     */
    fun pollStatus(orderId: Int) {
        _uiState.value = OrderUiState.Loading
        viewModelScope.launch {
            var resolved = false
            while (!resolved) {
                when (val result = repository.getOrderDetail(orderId)) {
                    is OrderResult.Success -> {
                        if (result.order.status != OrderStatus.PENDING) {

                            if (result.order.status == OrderStatus.CONFIRMED) {
                                repository.initPayment(orderId)
                            }

                            _uiState.value = OrderUiState.Resolved(result.order)
                            resolved = true

                        } else {
                            delay(3000)
                        }
                    }

                    is OrderResult.Error -> {
                        _uiState.value = OrderUiState.Error(result.message)
                        resolved = true
                    }
                }
            }
        }
    }

    fun uploadPaymentProof(orderId: Int, file: File) {
        viewModelScope.launch {
            android.util.Log.d("UPLOAD", "START")
            _uploadState.value = UploadProofUiState.Loading
            val requestBody = file.asRequestBody("image/*".toMediaTypeOrNull())
            // "payment_proof" harus cocok persis sama $request->file('payment_proof')
            // di PaymentController.php backend
            val proofPart = MultipartBody.Part.createFormData(
                name = "payment_proof",
                filename = file.name,
                body = requestBody
            )
            when (val result = repository.uploadPaymentProof(orderId, proofPart)) {

                is UploadProofResult.Success -> {
                    android.util.Log.d("UPLOAD", "SUCCESS")
                    _uploadState.value = UploadProofUiState.Success(result.proofUrl)
                }

                is UploadProofResult.Error -> {
                    android.util.Log.d("UPLOAD", "ERROR = ${result.message}")
                    _uploadState.value = UploadProofUiState.Error(result.message)
                }
            }
        }
    }

    class Factory(private val repository: OrderRepository) : ViewModelProvider.Factory {
        override fun <T : ViewModel> create(modelClass: Class<T>): T {
            @Suppress("UNCHECKED_CAST")
            return OrderViewModel(repository) as T
        }
    }
}