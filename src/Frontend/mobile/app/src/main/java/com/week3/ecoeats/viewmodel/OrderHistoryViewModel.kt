package com.week3.ecoeats.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.model.OrderSummaryDto
import com.week3.ecoeats.data.remote.OrderListResult
import com.week3.ecoeats.data.remote.OrderRepository
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

sealed class OrderHistoryUiState {
    object Loading : OrderHistoryUiState()
    data class Loaded(val orders: List<OrderSummaryDto>) : OrderHistoryUiState()
    data class Error(val message: String) : OrderHistoryUiState()
}

class OrderHistoryViewModel(private val repository: OrderRepository) : ViewModel() {

    private val _uiState = MutableStateFlow<OrderHistoryUiState>(OrderHistoryUiState.Loading)
    val uiState: StateFlow<OrderHistoryUiState> = _uiState.asStateFlow()

    init {
        loadOrders()
    }

    fun loadOrders() {
        _uiState.value = OrderHistoryUiState.Loading
        viewModelScope.launch {
            _uiState.value = when (val result = repository.getOrders()) {
                is OrderListResult.Success -> OrderHistoryUiState.Loaded(result.orders)
                is OrderListResult.Error -> OrderHistoryUiState.Error(result.message)
            }
        }
    }

    class Factory(private val repository: OrderRepository) : ViewModelProvider.Factory {
        @Suppress("UNCHECKED_CAST")
        override fun <T : ViewModel> create(modelClass: Class<T>): T =
            OrderHistoryViewModel(repository) as T
    }
}
