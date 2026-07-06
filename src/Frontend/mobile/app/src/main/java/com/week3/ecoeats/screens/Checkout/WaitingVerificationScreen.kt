package com.week3.ecoeats.screens.Checkout

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel

/**
 * Halaman terpisah yang tampil setelah klik "Place Order" di FoodDetailScreen,
 * sebelum sampai ke CheckoutScreen. Selama di sini, app menunggu (polling)
 */
@Composable
fun WaitingVerificationScreen(
    orderId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = OrderRepository(api = RetrofitInstance.createOrderApi(context))
    val viewModel: OrderViewModel = viewModel(
        factory = OrderViewModel.Factory(repository)
    )
    val uiState by viewModel.uiState.collectAsState()

    LaunchedEffect(orderId) {
        viewModel.pollStatus(orderId)
    }

    // merchat acc, pindah ke Checkout
    LaunchedEffect(uiState) {
        val state = uiState
        if (state is OrderUiState.Resolved) {
            navController.navigate("checkout/${state.order.id}") {
                popUpTo("food-detail/{foodId}") { inclusive = false }
            }
        }
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
            .padding(24.dp),
        horizontalAlignment = Alignment.CenterHorizontally,
        verticalArrangement = Arrangement.Center
    ) {
        when (val state = uiState) {
            is OrderUiState.Error -> {
                Text(
                    text = "Gagal memuat status order",
                    color = Color.Red,
                    fontSize = 15.sp,
                    fontWeight = FontWeight.Bold,
                    textAlign = TextAlign.Center
                )
                Text(
                    text = state.message,
                    color = CharcoalBrown.copy(alpha = 0.7f),
                    fontSize = 13.sp,
                    textAlign = TextAlign.Center,
                    modifier = Modifier.padding(top = 8.dp)
                )
            }
            is OrderUiState.Idle, is OrderUiState.Loading, is OrderUiState.Resolved -> {
                CircularProgressIndicator(
                    color = DarkOliveGreen,
                    modifier = Modifier.size(48.dp)
                )
                Spacer(modifier = Modifier.height(20.dp))
                Text(
                    text = "Menunggu Verifikasi Merchant",
                    color = DarkOliveGreen,
                    fontSize = 16.sp,
                    fontWeight = FontWeight.Bold,
                    textAlign = TextAlign.Center
                )
                Text(
                    text = "Mohon tunggu, pesananmu sedang diperiksa oleh merchant",
                    color = CharcoalBrown.copy(alpha = 0.7f),
                    fontSize = 13.sp,
                    textAlign = TextAlign.Center,
                    modifier = Modifier.padding(top = 8.dp)
                )
            }
        }
    }
}