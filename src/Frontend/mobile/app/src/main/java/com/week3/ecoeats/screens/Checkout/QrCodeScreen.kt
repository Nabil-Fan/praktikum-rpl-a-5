package com.week3.ecoeats.screens.Checkout

import android.graphics.Bitmap
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.heightIn
import androidx.compose.foundation.layout.navigationBarsPadding
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.statusBarsPadding
//import androidx.compose.foundation.layout.weight
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.CircularProgressIndicator
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
import androidx.compose.ui.graphics.asImageBitmap
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import androidx.navigation.compose.currentBackStackEntryAsState
import com.google.zxing.BarcodeFormat
import com.google.zxing.EncodeHintType
import com.google.zxing.qrcode.QRCodeWriter
import com.week3.ecoeats.R
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.screens.components.BottomNavBar
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

@Composable
fun QrCodeScreen(
    orderId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { OrderRepository(api = RetrofitInstance.createOrderApi(context)) }
    val viewModel: OrderViewModel = viewModel(
        factory = OrderViewModel.Factory(repository)
    )
    val uiState by viewModel.uiState.collectAsState()
    val currentRoute = navController.currentBackStackEntryAsState().value?.destination?.route ?: ""

    LaunchedEffect(orderId) {
        viewModel.loadOrder(orderId)
    }

    // Root: Box fullscreen — semua layer di-overlay satu sama lain.
    // LaurelGreen extend sampai bawah layar (tembus navbar).
    // BottomNavBar di-overlay paling atas dengan align(BottomCenter).
    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        when (val state = uiState) {
            is OrderUiState.Idle, is OrderUiState.Loading -> {
                Box(
                    modifier = Modifier.fillMaxSize(),
                    contentAlignment = Alignment.Center
                ) {
                    CircularProgressIndicator(color = DarkOliveGreen)
                }
            }

            is OrderUiState.Error -> {
                Box(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(24.dp),
                    contentAlignment = Alignment.Center
                ) {
                    Text(
                        text = "Gagal memuat pesanan: ${state.message}",
                        color = Color.Red,
                        fontSize = 13.sp,
                        textAlign = TextAlign.Center
                    )
                }
            }

            is OrderUiState.Resolved -> {
                val order = state.order
                val pickupCode = order.pickup_code ?: ""
                val qrBitmap = remember(pickupCode) {
                    generateQrBitmap(content = pickupCode, size = 600)
                }

                // ── Layer 1: Hero image fullscreen di background ──
                Image(
                    painter = painterResource(R.drawable.gambar1),
                    contentDescription = null,
                    contentScale = ContentScale.Crop,
                    modifier = Modifier.fillMaxSize()
                )

                // ── Layer 2: QR Card + Estimated — align BottomCenter ──
                // Column ini nempel ke bawah layar (bukan di atas navbar).
                // Card dibuat besar & margin tipis sesuai wireframe, sudut atas saja
                // yang melengkung supaya nyambung rapat dengan section hijau di bawahnya.
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .align(Alignment.BottomCenter)
                ) {
                    // ── QR Card ──
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 24.dp)
                            .clip(RoundedCornerShape(20.dp))
                            .background(CharcoalBrown)
                            .padding(horizontal = 20.dp, vertical = 40.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {
                        if (qrBitmap != null) {
                            Image(
                                bitmap = qrBitmap.asImageBitmap(),
                                contentDescription = "QR Code Pickup",
                                modifier = Modifier.size(200.dp)
                            )
                        } else {
                            Box(
                                modifier = Modifier
                                    .size(200.dp)
                                    .background(white),
                                contentAlignment = Alignment.Center
                            ) {
                                Text(
                                    "QR tidak tersedia",
                                    color = CharcoalBrown.copy(alpha = 0.5f),
                                    fontSize = 13.sp
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(20.dp))

                        Text(
                            text = "Code PickUp: $pickupCode",
                            color = white,
                            fontWeight = FontWeight.Bold,
                            fontSize = 16.sp
                        )

                        Spacer(modifier = Modifier.height(8.dp))

                        Text(
                            text = "Show this QR to merchant and grab your food!",
                            color = white.copy(alpha = 0.8f),
                            fontSize = 13.sp,
                            textAlign = TextAlign.Center
                        )
                    }

                    Spacer(modifier = Modifier.height(16.dp))

                    // ── Estimated Pick-Up Time ──
                    // Background LaurelGreen extend sampai bawah layar (tembus navbar).
                    // Bottom padding ekstra ditambahkan SEBELUM navigationBarsPadding() supaya
                    // teks naik di atas BottomNavBar (yang overlay di Layer 4), tapi warna
                    // hijau tetap mengisi penuh sampai bawah layar (tidak ada lagi yg ke-cover).
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .clip(RoundedCornerShape(topStart = 24.dp, topEnd = 24.dp))
                            .background(LaurelGreen)
                            .padding(horizontal = 24.dp, vertical = 24.dp)
                            .padding(bottom = 64.dp) // clearance supaya tidak ketutup BottomNavBar
                            .navigationBarsPadding(),
                        horizontalAlignment = Alignment.CenterHorizontally,
                        verticalArrangement = Arrangement.Center
                    ) {
                        Text(
                            text = "Estimated Pick-Up Time",
                            color = Cornsilk.copy(alpha = 0.8f),
                            fontSize = 13.sp,
                            fontWeight = FontWeight.Medium,
                            textAlign = TextAlign.Center
                        )
                        Spacer(modifier = Modifier.height(4.dp))
                        Text(
                            text = formatPickupTime(order.confirmed_at, order.expires_at),
                            color = Color.Black,
                            fontSize = 22.sp,
                            fontWeight = FontWeight.Bold,
                            textAlign = TextAlign.Center
                        )
                    }
                }

                // ── Layer 3: Header (back + title) transparan di atas semua ──
                Box(
                    modifier = Modifier
                        .fillMaxWidth()
                        .statusBarsPadding()
                        .padding(horizontal = 16.dp, vertical = 12.dp)
                ) {
                    Button(
                        onClick = { navController.popBackStack() },
                        colors = ButtonDefaults.buttonColors(
                            containerColor = Color.Transparent
                        ),
                        modifier = Modifier
                            .size(36.dp)
                            .align(Alignment.CenterStart),
                        contentPadding = PaddingValues(0.dp),
                        shape = RoundedCornerShape(8.dp),
                        elevation = ButtonDefaults.buttonElevation(0.dp)
                    ) {
                        Text(
                            "<",
                            color = white,
                            fontSize = 18.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                    Text(
                        text = "QR Code",
                        color = white,
                        fontSize = 18.sp,
                        fontWeight = FontWeight.Bold,
                        textAlign = TextAlign.Center,
                        modifier = Modifier.align(Alignment.Center)
                    )
                }
            }
        }

        // ── Layer 4: BottomNavBar overlay di paling atas, align bawah ──
        // Wrap dengan Box agar align(BottomCenter) bekerja tanpa ubah BottomNavBar.
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .align(Alignment.BottomCenter)
        ) {
            BottomNavBar(
                navController = navController,
                currentRoute = currentRoute
            )
        }
    }
}

private fun generateQrBitmap(content: String, size: Int): Bitmap? {
    if (content.isBlank()) return null
    return try {
        val hints = mapOf(EncodeHintType.MARGIN to 1)
        val bitMatrix = QRCodeWriter().encode(content, BarcodeFormat.QR_CODE, size, size, hints)
        val bitmap = Bitmap.createBitmap(size, size, Bitmap.Config.RGB_565)
        for (x in 0 until size) {
            for (y in 0 until size) {
                bitmap.setPixel(
                    x, y,
                    if (bitMatrix[x, y]) android.graphics.Color.BLACK
                    else android.graphics.Color.WHITE
                )
            }
        }
        bitmap
    } catch (e: Exception) {
        null
    }
}

private fun formatPickupTime(confirmedAt: String?, expiresAt: String?): String {
    if (confirmedAt == null && expiresAt == null) return "-"

    val formats = listOf(
        SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()),
        SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault()),
        SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX", Locale.getDefault())
    )
    val outFmt = SimpleDateFormat("hh.mm a", Locale.getDefault())

    fun parseDate(raw: String?): Date? {
        raw ?: return null
        for (fmt in formats) {
            try { return fmt.parse(raw) } catch (_: Exception) { }
        }
        return null
    }

    val startStr = if (confirmedAt != null) {
        parseDate(confirmedAt)?.let { outFmt.format(it) } ?: "--"
    } else "--"

    val endStr = if (expiresAt != null) {
        parseDate(expiresAt)?.let { outFmt.format(it) } ?: "--"
    } else "--"

    return "$startStr - $endStr"
}