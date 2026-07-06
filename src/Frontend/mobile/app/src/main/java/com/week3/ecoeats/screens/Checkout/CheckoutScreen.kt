package com.week3.ecoeats.screens.Checkout

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.OutlinedButton
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
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
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel

private val WarmBrownLight = Color(0xFFD2B48C)


@Composable
fun CheckoutScreen(
    orderId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { OrderRepository(api = RetrofitInstance.createOrderApi(context)) }
    val viewModel: OrderViewModel = viewModel(
        factory = OrderViewModel.Factory(repository)
    )
    val uiState by viewModel.uiState.collectAsState()

    LaunchedEffect(orderId) {
        viewModel.pollStatus(orderId)
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
            .verticalScroll(rememberScrollState())
            .padding(horizontal = 20.dp, vertical = 24.dp)
    ) {
        Text(
            text = "Checkout",
            color = CharcoalBrown,
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold,
            textAlign = TextAlign.Center,
            modifier = Modifier.fillMaxWidth()
        )

        Spacer(modifier = Modifier.height(20.dp))

        when (val state = uiState) {
            is OrderUiState.Idle, is OrderUiState.Loading -> {
                Box(
                    modifier = Modifier.fillMaxWidth().padding(32.dp),
                    contentAlignment = Alignment.Center
                ) {
                    CircularProgressIndicator(color = DarkOliveGreen)
                }
            }

            is OrderUiState.Error -> {
                Text(
                    text = "Gagal memuat detail order: ${state.message}",
                    color = Color.Red,
                    fontSize = 13.sp
                )
            }

            is OrderUiState.Resolved -> {
                val order = state.order

                // ── Status ──────────────────────────────────────────────
                Text("Status", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                Spacer(modifier = Modifier.height(8.dp))
                StatusBox(status = order.status)

                if (order.status == OrderStatus.REJECTED || order.status == OrderStatus.EXPIRED) {
                    Spacer(modifier = Modifier.height(32.dp))
                    OutlinedButton(
                        onClick = {
                            navController.navigate("dashboard") {
                                popUpTo("dashboard") { inclusive = false }
                            }
                        },
                        modifier = Modifier.fillMaxWidth().height(48.dp),
                        shape = RoundedCornerShape(12.dp)
                    ) {
                        Text("Kembali ke Menu", color = DarkOliveGreen)
                    }
                    return@Column
                }

                // ── Status confirmed/ready/completed -> ringkasan lengkap ──
                Spacer(modifier = Modifier.height(20.dp))

                Text("Location to pick", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                Spacer(modifier = Modifier.height(8.dp))
                Box(
                    modifier = Modifier
                        .fillMaxWidth()
                        .clip(RoundedCornerShape(12.dp))
                        .background(WarmBrownLight)
                        .padding(16.dp)
                ) {
                    Text(
                        text = order.merchant?.business_address ?: "-",
                        color = CharcoalBrown,
                        fontSize = 13.sp,
                        lineHeight = 18.sp
                    )
                }

                Spacer(modifier = Modifier.height(20.dp))

                Text("Payments Method", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                Spacer(modifier = Modifier.height(8.dp))
                Box(
                    modifier = Modifier
                        .fillMaxWidth()
                        .clip(RoundedCornerShape(12.dp))
                        .background(WarmBrownLight)
                        .padding(horizontal = 16.dp, vertical = 14.dp)
                ) {
                    Text(
                        text = (order.payment_method ?: "-").uppercase(),
                        color = CharcoalBrown,
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp
                    )
                }

                Spacer(modifier = Modifier.height(20.dp))

                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .clip(RoundedCornerShape(12.dp))
                        .background(WarmBrownLight)
                        .padding(16.dp)
                ) {
                    Text("Detail Shipping", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 13.sp)
                    Spacer(modifier = Modifier.height(10.dp))
                    PriceRow("Harga", "Rp ${formatRupiah(order.itemsSubtotal.toInt())}")
                    HorizontalDivider(color = CharcoalBrown.copy(alpha = 0.2f), modifier = Modifier.padding(vertical = 8.dp))
                    PriceRow("Admin", "Rp ${formatRupiah(order.adminFee.toInt())}")
                }

                Spacer(modifier = Modifier.height(16.dp))

                Text(
                    text = "Total Ammount to pay Rp ${formatRupiah(order.total_amount.toInt())}",
                    color = CharcoalBrown,
                    fontSize = 13.sp,
                    modifier = Modifier.fillMaxWidth(),
                    textAlign = TextAlign.Start
                )

                Spacer(modifier = Modifier.height(28.dp))

                // ── Status sudah ada pembayaran terkonfirmasi -> tidak perlu bayar lagi ──
                val alreadyPaid = order.payment?.status == "paid"

                if (alreadyPaid) {
                    Text(
                        text = "Pembayaran sudah dikonfirmasi \u2713",
                        color = DarkOliveGreen,
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp,
                        modifier = Modifier.fillMaxWidth(),
                        textAlign = TextAlign.Center
                    )
                } else {
                    Button(
                        onClick = { navController.navigate("payment/${order.id}") },
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(52.dp),
                        shape = RoundedCornerShape(26.dp),
                        colors = ButtonDefaults.buttonColors(
                            containerColor = CharcoalBrown,
                            contentColor = white
                        )
                    ) {
                        Text("Lanjut ke Pembayaran", fontWeight = FontWeight.Bold, fontSize = 15.sp)
                    }
                }
            }
        }
    }
}

@Composable
private fun StatusBox(status: OrderStatus) {
    Box(
        modifier = Modifier
            .fillMaxWidth()
            .clip(RoundedCornerShape(12.dp))
            .background(WarmBrownLight)
            .padding(16.dp)
    ) {
        val (text, color) = when (status) {
            OrderStatus.REJECTED -> "Ditolak" to Color.Red
            OrderStatus.EXPIRED -> "Kedaluwarsa" to Color.Red
            OrderStatus.CONFIRMED -> "Diterima" to DarkOliveGreen
            OrderStatus.READY -> "Siap Diambil" to DarkOliveGreen
            OrderStatus.COMPLETED -> "Selesai" to DarkOliveGreen
            OrderStatus.PENDING -> "Menunggu verifikasi..." to CharcoalBrown
        }
        Text(text = text, color = color, fontWeight = FontWeight.Bold, fontSize = 14.sp)
    }
}

@Composable
private fun PriceRow(label: String, value: String) {
    Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
        Text(label, color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 13.sp)
        Text(value, color = CharcoalBrown, fontSize = 13.sp)
    }
}

private fun formatRupiah(amount: Int): String =
    String.format(java.util.Locale.getDefault(), "%,d", amount).replace(',', '.')