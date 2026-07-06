package com.week3.ecoeats.screens.Checkout

import android.R.color.black
import android.preference.PreferenceManager
import androidx.compose.foundation.background
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
import androidx.compose.foundation.layout.width
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
import androidx.compose.runtime.remember
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
import androidx.compose.ui.viewinterop.AndroidView
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import coil.compose.AsyncImage
import com.week3.ecoeats.R
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.data.util.resolvePhotoUrl
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.ui.theme.black
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel
import org.osmdroid.config.Configuration
import org.osmdroid.tileprovider.tilesource.TileSourceFactory
import org.osmdroid.util.GeoPoint
import org.osmdroid.views.MapView
import org.osmdroid.views.overlay.Marker as OsmMarker
import java.text.SimpleDateFormat
import java.util.Locale

@Composable
fun OrderDetailScreen(
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
        viewModel.loadOrder(orderId)
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        // ── Header ──
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
                modifier = Modifier
                    .size(36.dp)
                    .align(Alignment.CenterStart),
                contentPadding = PaddingValues(0.dp),
                shape = RoundedCornerShape(8.dp),
                elevation = ButtonDefaults.buttonElevation(0.dp)
            ) {
                Text("<", color = CharcoalBrown, fontSize = 16.sp, fontWeight = FontWeight.Bold)
            }
            Text(
                text = "Detail",
                color = CharcoalBrown,
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold,
                textAlign = TextAlign.Center,
                modifier = Modifier.align(Alignment.Center)
            )
        }

        when (val state = uiState) {
            is OrderUiState.Idle, is OrderUiState.Loading -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = DarkOliveGreen)
                }
            }

            is OrderUiState.Error -> {
                Box(
                    modifier = Modifier.fillMaxSize().padding(24.dp),
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
                val lat = order.merchant?.latitude?.toDoubleOrNull() ?: 0.0
                val lng = order.merchant?.longitude?.toDoubleOrNull() ?: 0.0
                val merchantName = order.merchant?.business_name ?: "-"

                Column(
                    modifier = Modifier
                        .weight(1f)
                        .fillMaxWidth()
                        .background(LaurelGreen)
                ) {
                    Column(
                        modifier = Modifier
                            .weight(1f)
                            .verticalScroll(rememberScrollState())
                    ) {
                        // ── Map full width, tanpa rounding di bawah supaya nempel rapat ke laurel green ──
                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(220.dp)
                                .background(Cornsilk)
                        ) {
                            AndroidView(
                                modifier = Modifier.fillMaxSize(),
                                factory = { ctx ->
                                    Configuration.getInstance().load(
                                        ctx,
                                        PreferenceManager.getDefaultSharedPreferences(ctx)
                                    )
                                    MapView(ctx).apply {
                                        setTileSource(TileSourceFactory.MAPNIK)
                                        setMultiTouchControls(true)
                                        controller.setZoom(16.0)
                                        controller.setCenter(GeoPoint(lat, lng))
                                        val marker = OsmMarker(this)
                                        marker.position = GeoPoint(lat, lng)
                                        marker.title = merchantName
                                        marker.setAnchor(OsmMarker.ANCHOR_CENTER, OsmMarker.ANCHOR_BOTTOM)
                                        overlays.add(marker)
                                    }
                                }
                            )
                        }

                        // ── Estimated Pick-Up Time + Order Details — full width nempel langsung ke map ──
                        Column(
                            modifier = Modifier
                                .fillMaxWidth()
                                .background(LaurelGreen)
                                .padding(horizontal = 20.dp, vertical = 16.dp)
                        ) {
                            // Estimated Pick-Up Time
                            Text(
                                text = "Estimated Pick-Up Time",
                                color = Cornsilk.copy(alpha = 0.85f),
                                fontSize = 12.sp,
                                textAlign = TextAlign.Center,
                                modifier = Modifier.fillMaxWidth()
                            )
                            Spacer(modifier = Modifier.height(4.dp))
                            Text(
                                text = formatPickupTimeRange(order.confirmed_at, order.expires_at),
                                color = white,
                                fontSize = 20.sp,
                                fontWeight = FontWeight.Bold,
                                textAlign = TextAlign.Center,
                                modifier = Modifier.fillMaxWidth()
                            )

                            HorizontalDivider(
                                modifier = Modifier.padding(vertical = 14.dp),
                                color = white.copy(alpha = 0.3f)
                            )

                            // Order Details
                            Text(
                                text = "Order Details",
                                color = white,
                                fontWeight = FontWeight.Bold,
                                fontSize = 15.sp
                            )
                            order.merchant?.business_name?.let { name ->
                                Spacer(modifier = Modifier.height(2.dp))
                                Text(
                                    text = name,
                                    color = white.copy(alpha = 0.75f),
                                    fontSize = 12.sp
                                )
                            }

                            Spacer(modifier = Modifier.height(14.dp))

                            order.items.forEach { item ->
                                Row(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(vertical = 6.dp),
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    AsyncImage(
                                        model = R.drawable.gambar2,
                                        contentDescription = item.listing_name,
                                        contentScale = ContentScale.Crop,
                                        modifier = Modifier
                                            .size(56.dp)
                                            .clip(RoundedCornerShape(8.dp))
                                    )
                                    Spacer(modifier = Modifier.width(12.dp))
                                    Column(modifier = Modifier.weight(1f)) {
                                        Text(
                                            text = item.listing_name.uppercase(),
                                            color = white,
                                            fontWeight = FontWeight.Bold,
                                            fontSize = 12.sp
                                        )
                                        Text(
                                            text = merchantName,
                                            color = white.copy(alpha = 0.7f),
                                            fontSize = 11.sp
                                        )
                                        Text(
                                            text = "Rp.${formatRupiah(item.unit_price.toInt())}",
                                            color = Cornsilk,
                                            fontSize = 11.sp,
                                            fontWeight = FontWeight.SemiBold
                                        )
                                    }
                                    Text(
                                        text = "${item.quantity}x",
                                        color = white.copy(alpha = 0.8f),
                                        fontSize = 12.sp
                                    )
                                }
                            }

                            HorizontalDivider(
                                modifier = Modifier.padding(vertical = 10.dp),
                                color = white.copy(alpha = 0.3f)
                            )

                            Row(
                                modifier = Modifier.fillMaxWidth(),
                                horizontalArrangement = Arrangement.SpaceBetween
                            ) {
                                Text("Total", color = white, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                                Text("Rp ${formatRupiah(order.total_amount.toInt())}", color = white, fontWeight = FontWeight.Bold, fontSize = 14.sp)
                            }
                        }

                        Spacer(modifier = Modifier.height(16.dp))
                    }

                    // ── Tombol QR Code sticky di bawah ──
                    Button(
                        onClick = { navController.navigate("qr-code/$orderId") },
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(start = 20.dp, end = 20.dp, top = 8.dp, bottom = 24.dp)
                            .height(52.dp),
                        shape = RoundedCornerShape(12.dp),
                        colors = ButtonDefaults.buttonColors(
                            containerColor = DarkOliveGreen,
                            contentColor = Cornsilk
                        )
                    ) {
                        Text("QR Code", fontWeight = FontWeight.Bold, fontSize = 16.sp)
                    }
                }
            }
        }
    }
}

private fun formatPickupTimeRange(confirmedAt: String?, expiresAt: String?): String {
    val formats = listOf(
        SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()),
        SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault()),
        SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX", Locale.getDefault())
    )
    val outFmt = SimpleDateFormat("hh.mm a", Locale.getDefault())

    fun parseDate(raw: String?) = raw?.let {
        formats.firstNotNullOfOrNull { fmt ->
            try { fmt.parse(it) } catch (_: Exception) { null }
        }
    }

    val startStr = parseDate(confirmedAt)?.let { outFmt.format(it) } ?: "--"
    val endStr = parseDate(expiresAt)?.let { outFmt.format(it) } ?: "--"
    return "$startStr - $endStr"
}

private fun formatRupiah(amount: Int): String =
    String.format(java.util.Locale.getDefault(), "%,d", amount).replace(',', '.')