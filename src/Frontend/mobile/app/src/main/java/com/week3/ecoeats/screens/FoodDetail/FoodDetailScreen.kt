package com.week3.ecoeats.screens.FoodDetail

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
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import androidx.navigation.compose.currentBackStackEntryAsState
import coil.compose.AsyncImage
import com.week3.ecoeats.R
import com.week3.ecoeats.data.model.FoodListing
import com.week3.ecoeats.data.model.PaymentMethod
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.data.util.resolvePhotoUrl
import com.week3.ecoeats.screens.components.BottomNavBar
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.viewmodel.FoodDetailViewModel
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel
import java.text.SimpleDateFormat
import java.util.Locale

private val WarmBrown = Color(0xFFB99470)

@Composable
fun FoodDetailScreen(
    foodId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { DashboardRepository(api = RetrofitInstance.createFoodListingApi(context)) }
    val viewModel: FoodDetailViewModel = viewModel(
        factory = FoodDetailViewModel.Factory(repository, foodId)
    )
    val uiState by viewModel.uiState.collectAsState()
    val currentRoute = navController.currentBackStackEntryAsState().value?.destination?.route ?: ""

    // ── ViewModel order terpisah, buat bikin order pas "Place Order" diklik ──
    val orderRepository = remember { OrderRepository(api = RetrofitInstance.createOrderApi(context)) }
    val orderViewModel: OrderViewModel = viewModel(factory = OrderViewModel.Factory(orderRepository))
    val orderState by orderViewModel.uiState.collectAsState()

    var selectedPayment by remember { mutableStateOf(PaymentMethod.QRIS) }

    // Begitu order berhasil dibuat (status awal "pending"), pindah ke
    // halaman Waiting Verification sambil bawa orderId-nya.
    LaunchedEffect(orderState) {
        val state = orderState
        if (state is OrderUiState.Resolved) {
            navController.navigate("waiting-verification/${state.order.id}")
        }
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        // ── Header sticky ──
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .background(Cornsilk)
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
                text = "DETAIL MENU",
                color = CharcoalBrown,
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold,
                textAlign = TextAlign.Center,
                modifier = Modifier.align(Alignment.Center)
            )
        }

        when {
            uiState.isLoading -> {
                Box(modifier = Modifier.weight(1f).fillMaxWidth(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = DarkOliveGreen)
                }
            }
            uiState.errorMessage != null -> {
                Box(modifier = Modifier.weight(1f).fillMaxWidth(), contentAlignment = Alignment.Center) {
                    Text(uiState.errorMessage ?: "", color = Color.Red)
                }
            }
            uiState.food != null -> {
                val food = uiState.food!!

                // ── Konten scroll (gambar, nama, info, jumlah, metode bayar) ──
                Column(
                    modifier = Modifier
                        .weight(1f)
                        .verticalScroll(rememberScrollState())
                ) {
                    AsyncImage(
                        model = if (food.imageUrl.isNullOrEmpty()) R.drawable.gambar2 else resolvePhotoUrl(food.imageUrl),
                        contentDescription = food.name,
                        contentScale = ContentScale.Crop,
                        modifier = Modifier.fillMaxWidth().height(220.dp)
                    )

                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 20.dp, vertical = 16.dp)
                    ) {
                        Text(
                            text = food.name.uppercase(),
                            fontSize = 18.sp,
                            fontWeight = FontWeight.Bold,
                            color = CharcoalBrown
                        )

                        if (!food.description.isNullOrBlank()) {
                            Spacer(modifier = Modifier.height(6.dp))
                            Text(
                                text = food.description,
                                fontSize = 12.sp,
                                color = CharcoalBrown.copy(alpha = 0.7f),
                                lineHeight = 17.sp
                            )
                        }

                        Spacer(modifier = Modifier.height(20.dp))

                        Column(
                            modifier = Modifier
                                .fillMaxWidth()
                                .clip(RoundedCornerShape(12.dp))
                                .background(WarmBrown)
                                .padding(horizontal = 16.dp, vertical = 12.dp)
                        ) {
                            Text("DETAIL INFORMATION", color = Cornsilk, fontWeight = FontWeight.Bold, fontSize = 12.sp)
                            Spacer(modifier = Modifier.height(10.dp))
                            DetailRow("Harga", "Rp. ${formatHarga(food.discountedPrice.toInt())}")
                            HorizontalDivider(color = Cornsilk.copy(alpha = 0.3f), modifier = Modifier.padding(vertical = 6.dp))
                            DetailRow("Stok", "${food.stock} tersisa")
                            HorizontalDivider(color = Cornsilk.copy(alpha = 0.3f), modifier = Modifier.padding(vertical = 6.dp))
                            DetailRow("Waktu", formatWaktu(food.pickupStart, food.pickupEnd))
                        }

                        Spacer(modifier = Modifier.height(20.dp))

                        // ── Metode Pembayaran — wajib dipilih sebelum order dibuat ──
                        Text("Metode Pembayaran", fontSize = 14.sp, fontWeight = FontWeight.SemiBold, color = CharcoalBrown)
                        Spacer(modifier = Modifier.height(8.dp))
                        PaymentMethodOption(
                            label = "Qris",
                            selected = selectedPayment == PaymentMethod.QRIS,
                            onClick = { selectedPayment = PaymentMethod.QRIS }
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        PaymentMethodOption(
                            label = "Transfer",
                            selected = selectedPayment == PaymentMethod.TRANSFER,
                            onClick = { selectedPayment = PaymentMethod.TRANSFER }
                        )
                        Spacer(modifier = Modifier.height(8.dp))
                        PaymentMethodOption(
                            label = "E-Wallet",
                            selected = selectedPayment == PaymentMethod.EWALLET,
                            onClick = { selectedPayment = PaymentMethod.EWALLET }
                        )

                        Spacer(modifier = Modifier.height(20.dp))

                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            verticalAlignment = Alignment.CenterVertically,
                            horizontalArrangement = Arrangement.SpaceBetween
                        ) {
                            Text("Jumlah", fontSize = 14.sp, fontWeight = FontWeight.SemiBold, color = CharcoalBrown)
                            Row(verticalAlignment = Alignment.CenterVertically) {
                                Button(
                                    onClick = { viewModel.increment() },
                                    colors = ButtonDefaults.buttonColors(containerColor = WarmBrown),
                                    modifier = Modifier.size(32.dp),
                                    contentPadding = PaddingValues(0.dp),
                                    shape = RoundedCornerShape(8.dp)
                                ) {
                                    Text("+", color = Cornsilk, fontWeight = FontWeight.Bold)
                                }
                                Text(
                                    text = " ${uiState.quantity} ",
                                    fontSize = 16.sp,
                                    fontWeight = FontWeight.Bold,
                                    color = CharcoalBrown,
                                    modifier = Modifier.padding(horizontal = 12.dp)
                                )
                                Button(
                                    onClick = { viewModel.decrement() },
                                    colors = ButtonDefaults.buttonColors(containerColor = WarmBrown),
                                    modifier = Modifier.size(32.dp),
                                    contentPadding = PaddingValues(0.dp),
                                    shape = RoundedCornerShape(8.dp)
                                ) {
                                    Text("-", color = Cornsilk, fontWeight = FontWeight.Bold)
                                }
                            }
                        }

                        if (orderState is OrderUiState.Error) {
                            Spacer(modifier = Modifier.height(10.dp))
                            Text(
                                text = (orderState as OrderUiState.Error).message,
                                color = Color.Red,
                                fontSize = 12.sp
                            )
                        }
                    }
                }

                // ── Place Order sticky di atas navbar ──
                Button(
                    onClick = {
                        orderViewModel.createOrder(
                            foodListingId = food.id,
                            quantity = uiState.quantity,
                            paymentMethod = selectedPayment
                        )
                    },
                    enabled = orderState !is OrderUiState.Loading,
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 20.dp, vertical = 12.dp)
                        .height(52.dp),
                    shape = RoundedCornerShape(12.dp),
                    colors = ButtonDefaults.buttonColors(
                        containerColor = CharcoalBrown,
                        contentColor = Cornsilk
                    )
                ) {
                    if (orderState is OrderUiState.Loading) {
                        CircularProgressIndicator(modifier = Modifier.height(20.dp), color = Cornsilk)
                    } else {
                        Text("Place Order", fontWeight = FontWeight.Bold, fontSize = 15.sp)
                    }
                }
            }
        }

        // ── Bottom Nav Bar sticky ──
        BottomNavBar(
            navController = navController,
            currentRoute = currentRoute
        )
    }
}

@Composable
fun PaymentMethodOption(label: String, selected: Boolean, onClick: () -> Unit) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .clip(RoundedCornerShape(12.dp))
            .background(WarmBrown)
            .clickable(onClick = onClick)
            .padding(horizontal = 16.dp, vertical = 12.dp),
        horizontalArrangement = Arrangement.SpaceBetween,
        verticalAlignment = Alignment.CenterVertically
    ) {
        Text(label, color = Cornsilk, fontWeight = FontWeight.Bold, fontSize = 14.sp)
        Box(
            modifier = Modifier
                .size(20.dp)
                .clip(RoundedCornerShape(50))
                .background(if (selected) CharcoalBrown else Cornsilk)
        )
    }
}

@Composable
fun DetailRow(label: String, value: String) {
    Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
        Text(label, color = Cornsilk.copy(alpha = 0.8f), fontSize = 13.sp)
        Text(value, color = Cornsilk, fontSize = 13.sp, fontWeight = FontWeight.SemiBold)
    }
}

private fun formatHarga(harga: Int): String =
    String.format(Locale.getDefault(), "%,d", harga).replace(',', '.')

private fun formatWaktu(start: String?, end: String?): String {
    if (start == null || end == null) return "-"
    return try {
        val inputFmt = SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX", Locale.ENGLISH)
        val outputFmt = SimpleDateFormat("hh.mm a", Locale.ENGLISH)
        val s = outputFmt.format(inputFmt.parse(start)!!)
        val e = outputFmt.format(inputFmt.parse(end)!!)
        "$s - $e"
    } catch (e: Exception) { "-" }
}