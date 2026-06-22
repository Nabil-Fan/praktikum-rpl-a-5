package com.week3.ecoeats.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.week3.ecoeats.data.model.Category
import com.week3.ecoeats.data.model.FoodListing
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.data.remote.Result
import kotlinx.coroutines.FlowPreview
import kotlinx.coroutines.flow.*
import kotlinx.coroutines.launch
import android.util.Log

data class DashboardUiState(
    val categories: List<Category> = emptyList(),
    val listings: List<FoodListing> = emptyList(),
    val selectedCategoryId: Int? = null,   // null = SEMUA
    val searchQuery: String = "",
    val isLoading: Boolean = false,
    val errorMessage: String? = null
)

@OptIn(FlowPreview::class)
class DashboardViewModel(
    private val repository: DashboardRepository
) : ViewModel() {

    private val _uiState = MutableStateFlow(DashboardUiState())
    val uiState: StateFlow<DashboardUiState> = _uiState.asStateFlow()

    // Debounce search input
    private val _searchQuery = MutableStateFlow("")

    init {
        Log.d("TEST_VM", "ViewModel KEPAKAI")
        loadCategories()

        // React to search query with 400ms debounce
        viewModelScope.launch {
            _searchQuery
                .debounce(400)
                .distinctUntilChanged()
                .collect {
                    loadListings()
                }
        }
    }

    fun onSearchQueryChange(query: String) {
        _uiState.update {
            it.copy(searchQuery = query)
        }
        _searchQuery.value = query
    }

    fun onCategorySelected(categoryId: Int?) {
        _uiState.update { it.copy(selectedCategoryId = categoryId) }
        loadListings()
    }

    fun refresh() {
        loadCategories()
        loadListings()
    }

    private fun loadCategories() {
        viewModelScope.launch {
            when (val result = repository.getCategories()) {
                is Result.Success -> _uiState.update { it.copy(categories = result.data) }
                is Result.Error -> _uiState.update { it.copy(errorMessage = result.message) }
                else -> Unit
            }
        }
    }

    private fun loadListings() {
        viewModelScope.launch {
            _uiState.update { it.copy(isLoading = true, errorMessage = null) }

            val state = _uiState.value

            Log.d("API_TEST", "state search = ${state.searchQuery}")

            val result = repository.getFoodListings(
                categoryId = state.selectedCategoryId,
                search = state.searchQuery.ifBlank { null }
            )
            when (result) {
                is Result.Success -> _uiState.update {
                    it.copy(listings = result.data, isLoading = false)
                }

                is Result.Error -> _uiState.update {
                    it.copy(errorMessage = result.message, isLoading = false)
                }

                else -> Unit
            }
        }
    }

    // ── Factory ───────────────────────────────────────────────────────────────
    class Factory(private val repository: DashboardRepository) : ViewModelProvider.Factory {
        @Suppress("UNCHECKED_CAST")
        override fun <T : ViewModel> create(modelClass: Class<T>): T =
            DashboardViewModel(repository) as T
    }
}