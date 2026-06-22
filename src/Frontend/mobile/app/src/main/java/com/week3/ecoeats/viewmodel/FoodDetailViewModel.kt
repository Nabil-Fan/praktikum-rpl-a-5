package com.week3.ecoeats.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.model.FoodListing
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.data.remote.Result
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class FoodDetailUiState(
    val food: FoodListing? = null,
    val quantity: Int = 1,
    val isLoading: Boolean = false,
    val errorMessage: String? = null
)

class FoodDetailViewModel(
    private val repository: DashboardRepository,
    private val foodId: Int
) : ViewModel() {

    private val _uiState = MutableStateFlow(FoodDetailUiState())
    val uiState: StateFlow<FoodDetailUiState> = _uiState.asStateFlow()

    init {
        loadDetail()
    }

    private fun loadDetail() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true) }
            when (val result = repository.getFoodListingById(foodId)) {
                is Result.Success -> _uiState.update { it.copy(food = result.data, isLoading = false) }
                is Result.Error   -> _uiState.update { it.copy(errorMessage = result.message, isLoading = false) }
                else -> Unit
            }
        }
    }

    fun increment() {
        val max = _uiState.value.food?.stock ?: 1
        _uiState.update { if (it.quantity < max) it.copy(quantity = it.quantity + 1) else it }
    }

    fun decrement() {
        _uiState.update { if (it.quantity > 1) it.copy(quantity = it.quantity - 1) else it }
    }

    class Factory(
        private val repository: DashboardRepository,
        private val foodId: Int
    ) : ViewModelProvider.Factory {
        @Suppress("UNCHECKED_CAST")
        override fun <T : ViewModel> create(modelClass: Class<T>): T =
            FoodDetailViewModel(repository, foodId) as T
    }
}