package com.week3.ecoeats.screens.OrderHistory

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.statusBarsPadding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.OutlinedButton
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import com.week3.ecoeats.data.model.OrderStatus
import com.week3.ecoeats.data.model.OrderSummaryDto
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.viewmodel.OrderHistoryUiState
import com.week3.ecoeats.viewmodel.OrderHistoryViewModel
import java.text.SimpleDateFormat
import java.util.Locale

private val WarmBrownLight = Color(0xFFD2B48C)

@Composable
fun OrderHistoryScreen(
    navController: NavController
) {
    val context = LocalContext.current
    val repository = OrderRepository(api = RetrofitInstance.createOrderApi(context))
    val viewModel: OrderHistoryViewModel = viewModel(
        factory = OrderHistoryViewModel.Factory(repository)
    )
    val uiState by viewModel.uiState.collectAsState()

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        // ── Header ──
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .statusBarsPadding()
                .padding(horizontal = 16.dp, vertical = 12.dp)
        ) {
            Button(
                onClick = { navController.popBackStack() },
                colors = ButtonDefaults.buttonColors(containerColor = Cornsilk),
                modifier = Modifier.size(36.dp).align(Alignment.CenterStart),
                contentPadding = PaddingValues(0.dp),
                shape = RoundedCornerShape(8.dp),
                elevation = ButtonDefaults.buttonElevation(0.dp)
            ) {
                Text("<", color = CharcoalBrown, fontSize = 16.sp, fontWeight = FontWeight.Bold)
            }
            Text(
                text = "RIWAYAT PESANAN",
                color = CharcoalBrown,
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold,
                textAlign = TextAlign.Center,
                modifier = Modifier.align(Alignment.Center)
            )
        }

        when (val state = uiState) {
            is OrderHistoryUiState.Loading -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = DarkOliveGreen)
                }
            }

            is OrderHistoryUiState.Error -> {
                Box(modifier = Modifier.fillMaxSize().padding(24.dp), contentAlignment = Alignment.Center) {
                    Column(horizontalAlignment = Alignment.CenterHorizontally) {
                        Text(
                            text = "Gagal memuat riwayat pesanan",
                            color = Color.Red,
                            fontSize = 14.sp,
                            fontWeight = FontWeight.Bold,
                            textAlign = TextAlign.Center
                        )
                        Text(
                            text = state.message,
                            color = CharcoalBrown.copy(alpha = 0.7f),
                            fontSize = 12.sp,
                            textAlign = TextAlign.Center,
                            modifier = Modifier.padding(top = 6.dp)
                        )
                        Spacer(modifier = Modifier.height(16.dp))
                        OutlinedButton(onClick = { viewModel.loadOrders() }) {
                            Text("Coba Lagi", color = DarkOliveGreen)
                        }
                    }
                }
            }

            is OrderHistoryUiState.Loaded -> {
                if (state.orders.isEmpty()) {
                    Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                        Text(
                            text = "Belum ada pesanan",
                            color = CharcoalBrown.copy(alpha = 0.6f),
                            fontSize = 14.sp
                        )
                    }
                } else {
                    LazyColumn(
                        modifier = Modifier.fillMaxSize(),
                        contentPadding = PaddingValues(horizontal = 20.dp, vertical = 12.dp)
                    ) {
                        items(state.orders, key = { it.id }) { order ->
                            OrderSummaryCard(
                                order = order,
                                onClick = { navController.navigate("checkout/${order.id}") }
                            )
                            Spacer(modifier = Modifier.height(12.dp))
                        }
                    }
                }
            }
        }
    }
}

@Composable
private fun OrderSummaryCard(order: OrderSummaryDto, onClick: () -> Unit) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .clickable(onClick = onClick),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = WarmBrownLight)
    ) {
        Column(modifier = Modifier.padding(16.dp)) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text(
                    text = order.item_name ?: "Pesanan",
                    color = CharcoalBrown,
                    fontWeight = FontWeight.Bold,
                    fontSize = 14.sp,
                    modifier = Modifier.weight(1f)
                )
                StatusBadge(status = order.status)
            }

            Spacer(modifier = Modifier.height(6.dp))

            Text(
                text = order.merchant_name ?: "-",
                color = CharcoalBrown.copy(alpha = 0.7f),
                fontSize = 12.sp
            )

            Spacer(modifier = Modifier.height(4.dp))

            Text(
                text = formatTanggal(order.ordered_at),
                color = CharcoalBrown.copy(alpha = 0.6f),
                fontSize = 11.sp
            )

            Spacer(modifier = Modifier.height(10.dp))

            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                if (!order.pickup_code.isNullOrBlank()) {
                    Text(
                        text = "Kode: ${order.pickup_code}",
                        color = CharcoalBrown,
                        fontSize = 12.sp,
                        fontWeight = FontWeight.SemiBold
                    )
                } else {
                    Spacer(modifier = Modifier)
                }
                Text(
                    text = formatRupiah(order.total_amount.toInt()),
                    color = CharcoalBrown,
                    fontWeight = FontWeight.Bold,
                    fontSize = 13.sp
                )
            }
        }
    }
}

@Composable
private fun StatusBadge(status: OrderStatus) {
    val (label, color) = when (status) {
        OrderStatus.PENDING -> "Menunggu" to Color(0xFF9C7A00)
        OrderStatus.CONFIRMED -> "Dikonfirmasi" to DarkOliveGreen
        OrderStatus.READY -> "Siap Diambil" to DarkOliveGreen
        OrderStatus.COMPLETED -> "Selesai" to Color(0xFF4A5C3F)
        OrderStatus.REJECTED -> "Ditolak" to Color.Red
        OrderStatus.EXPIRED -> "Kedaluwarsa" to CharcoalBrown.copy(alpha = 0.5f)
    }
    Box(
        modifier = Modifier
            .clip(RoundedCornerShape(50))
            .background(color.copy(alpha = 0.15f))
            .padding(horizontal = 10.dp, vertical = 4.dp)
    ) {
        Text(text = label, color = color, fontSize = 10.sp, fontWeight = FontWeight.Bold)
    }
}

private fun formatRupiah(amount: Int): String =
    "Rp" + String.format(Locale.getDefault(), "%,d", amount).replace(',', '.')

private fun formatTanggal(raw: String?): String {
    if (raw == null) return "-"
    return try {
        val inputFmt = SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX", Locale.ENGLISH)
        val outputFmt = SimpleDateFormat("d MMM yyyy, HH:mm", Locale("id", "ID"))
        outputFmt.format(inputFmt.parse(raw)!!)
    } catch (e: Exception) {
        raw
    }
}
