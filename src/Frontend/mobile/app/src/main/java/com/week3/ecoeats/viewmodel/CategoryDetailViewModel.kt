package com.week3.ecoeats.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.FoodListing
import com.week3.ecoeats.data.model.FoodListing as FoodListingDto
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.data.remote.Result
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class CategoryDetailUiState(
    val categoryName: String = "",
    val listings: List<FoodListing> = emptyList(),
    val isLoading: Boolean = false,
    val errorMessage: String? = null
)

class CategoryDetailViewModel(
    private val repository: DashboardRepository,
    private val categoryId: Int
) : ViewModel() {

    private val _uiState = MutableStateFlow(CategoryDetailUiState())
    val uiState: StateFlow<CategoryDetailUiState> = _uiState.asStateFlow()

    init {
        loadData()
    }

    private fun loadData() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true) }

            // Ambil nama kategori dari list semua kategori
            when (val result = repository.getCategories()) {
                is Result.Success -> {
                    val name = result.data.find { it.id == categoryId }?.name ?: ""
                    _uiState.update { it.copy(categoryName = name) }
                }
                is Result.Error -> _uiState.update { it.copy(errorMessage = result.message) }
                else -> Unit
            }

            // Ambil listing berdasarkan categoryId
            when (val result = repository.getFoodListings(categoryId = categoryId, search = null)) {
                is Result.Success -> {
                    _uiState.update {
                        it.copy(
                            listings = result.data.map { dto -> toUiFoodListing(dto) },
                            isLoading = false
                        )
                    }
                }
                is Result.Error -> _uiState.update {
                    it.copy(errorMessage = result.message, isLoading = false)
                }
                else -> Unit
            }
        }
    }

    private fun toUiFoodListing(dto: FoodListingDto): FoodListing {
        return FoodListing(
            id = dto.id,
            name = dto.name,
            restoran = dto.merchant?.storeName ?: "-",
            harga = dto.discountedPrice.toInt(),
            photoUrl = dto.imageUrl ?: ""
        )
    }

    class Factory(
        private val repository: DashboardRepository,
        private val categoryId: Int
    ) : ViewModelProvider.Factory {
        @Suppress("UNCHECKED_CAST")
        override fun <T : ViewModel> create(modelClass: Class<T>): T =
            CategoryDetailViewModel(repository, categoryId) as T
    }
}