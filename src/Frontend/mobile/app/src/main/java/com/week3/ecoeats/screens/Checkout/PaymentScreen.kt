package com.week3.ecoeats.screens.Checkout

import android.net.Uri
import androidx.activity.compose.rememberLauncherForActivityResult
import androidx.activity.result.contract.ActivityResultContracts
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
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
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import com.week3.ecoeats.R
import com.week3.ecoeats.data.remote.OrderRepository
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.ui.theme.Camel
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel
import com.week3.ecoeats.viewmodel.UploadProofUiState
import java.io.File
import java.io.FileOutputStream

@Composable
fun PaymentScreen(
    orderId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { OrderRepository(api = RetrofitInstance.createOrderApi(context)) }
    val viewModel: OrderViewModel = viewModel(
        factory = OrderViewModel.Factory(repository)
    )
    val uiState by viewModel.uiState.collectAsState()
    val uploadState by viewModel.uploadState.collectAsState()

    LaunchedEffect(orderId) {
        viewModel.pollStatus(orderId)
    }

    val imagePicker = rememberLauncherForActivityResult(
        contract = ActivityResultContracts.GetContent()
    ) { uri: Uri? ->
        if (uri != null) {
            val file = uriToFile(context, uri)
            if (file != null) {
                viewModel.uploadPaymentProof(orderId, file)
            }
        }
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Camel)
    ) {
        when (val state = uiState) {
            is OrderUiState.Idle, is OrderUiState.Loading -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
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
                        text = "Gagal memuat order: ${state.message}",
                        color = Color.Red,
                        fontSize = 13.sp,
                        textAlign = TextAlign.Center
                    )
                }
            }

            is OrderUiState.Resolved -> {
                val order = state.order
                val paymentLabel = order.payment_method?.uppercase() ?: "PAYMENT"

                Column(
                    modifier = Modifier
                        .fillMaxSize()
                        .verticalScroll(rememberScrollState())
                ) {
                    // ── Hero section: foto + header + QR/payment card ──
                    Box(
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(460.dp)
                    ) {
                        // Background foto
                        Image(
                            painter = painterResource(R.drawable.gambar1),
                            contentDescription = null,
                            contentScale = ContentScale.Crop,
                            modifier = Modifier.fillMaxSize()
                        )

                        // Overlay gelap tipis supaya teks terbaca
                        Box(
                            modifier = Modifier
                                .fillMaxSize()
                                .background(Color.Black.copy(alpha = 0.25f))
                        )

                        // Header: "PEMBAYARAN QRIS/TRANSFER/etc"
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
                                text = "PEMBAYARAN $paymentLabel",
                                color = white,
                                fontSize = 16.sp,
                                fontWeight = FontWeight.Bold,
                                textAlign = TextAlign.Center,
                                modifier = Modifier.align(Alignment.Center)
                            )
                        }

                        // Konten tengah: label payment + card
                        Column(
                            modifier = Modifier
                                .fillMaxSize()
                                .padding(top = 60.dp), // beri jarak dari header
                            horizontalAlignment = Alignment.CenterHorizontally
                        ) {
                            Spacer(modifier = Modifier.height(20.dp))
                            Text(
                                text = paymentLabel,
                                color = white,
                                fontWeight = FontWeight.Bold,
                                fontSize = 22.sp
                            )

                            Spacer(modifier = Modifier.height(20.dp))

                            // ── Payment card (CharcoalBrown, rounded) ──
                            Column(
                                modifier = Modifier
                                    .clip(RoundedCornerShape(16.dp))
                                    .background(CharcoalBrown)
                                    .padding(24.dp),
                                horizontalAlignment = Alignment.CenterHorizontally
                            ) {
                                when (order.payment_method?.lowercase()) {
                                    "qris" -> {
                                        Image(
                                            painter = painterResource(R.drawable.qr_code),
                                            contentDescription = "QRIS",
                                            modifier = Modifier
                                                .size(220.dp)
                                                .background(white)
                                                .padding(8.dp)
                                        )
                                    }

                                    "transfer" -> {
                                        Column(
                                            horizontalAlignment = Alignment.CenterHorizontally
                                        ) {
                                            Text(
                                                "BANK BCA",
                                                fontWeight = FontWeight.Bold,
                                                color = white,
                                                fontSize = 16.sp
                                            )
                                            Spacer(modifier = Modifier.height(12.dp))
                                            Text(
                                                "1234567890",
                                                fontSize = 26.sp,
                                                color = white,
                                                fontWeight = FontWeight.Bold
                                            )
                                            Spacer(modifier = Modifier.height(8.dp))
                                            Text("a.n EcoEats", color = white.copy(alpha = 0.8f))
                                        }
                                    }

                                    else -> {
                                        Column(
                                            horizontalAlignment = Alignment.CenterHorizontally
                                        ) {
                                            Text(
                                                "E-WALLET",
                                                color = white,
                                                fontWeight = FontWeight.Bold,
                                                fontSize = 16.sp
                                            )
                                            Spacer(modifier = Modifier.height(12.dp))
                                            Text(
                                                "081234567890",
                                                fontSize = 24.sp,
                                                color = white,
                                                fontWeight = FontWeight.Bold
                                            )
                                            Spacer(modifier = Modifier.height(8.dp))
                                            Text("a.n EcoEats", color = white.copy(alpha = 0.8f))
                                        }
                                    }
                                }

                                Spacer(modifier = Modifier.height(16.dp))

                                // Total amount DI DALAM card
                                Text(
                                    text = "Total Amount to pay Rp ${formatRupiah(order.total_amount.toInt())}",
                                    color = white,
                                    fontSize = 15.sp,
                                    fontWeight = FontWeight.Medium,
                                    textAlign = TextAlign.Center
                                )
                            }
                        }
                    }

                    // ── Bottom section: upload + next ──
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .background(Camel)
                            .padding(horizontal = 20.dp, vertical = 20.dp)
                    ) {
                        Text(
                            text = "Upload Bukti Pembayaran :",
                            color = CharcoalBrown,
                            fontWeight = FontWeight.Bold,
                            fontSize = 14.sp
                        )

                        Spacer(modifier = Modifier.height(20.dp))

                        // Upload box
                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(90.dp)
                                .clip(RoundedCornerShape(10.dp))
                                .background(Cornsilk)
                                .border(
                                    1.dp,
                                    CharcoalBrown.copy(alpha = 0.2f),
                                    RoundedCornerShape(10.dp)
                                )
                                .clickable(
                                    enabled = uploadState !is UploadProofUiState.Loading
                                ) {
                                    imagePicker.launch("image/*")
                                },
                            contentAlignment = Alignment.Center
                        ) {
                            Text(
                                text = when (uploadState) {
                                    is UploadProofUiState.Success -> "File Uploaded"
                                    is UploadProofUiState.Loading -> "Uploading..."
                                    else -> "Upload File"
                                },
                                color = CharcoalBrown.copy(alpha = 0.55f),
                                fontSize = 14.sp
                            )
                        }

                        Spacer(modifier = Modifier.height(20.dp))

                        // Next button
                        Button(
                            onClick = {
                                if (uploadState is UploadProofUiState.Success) {
                                    navController.navigate("order-detail/$orderId")
                                } else {
                                    imagePicker.launch("image/*")
                                }
                            },
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(50.dp),
                            shape = RoundedCornerShape(10.dp),
                            colors = ButtonDefaults.buttonColors(
                                containerColor = CharcoalBrown,
                                contentColor = white
                            )
                        ) {
                            if (uploadState is UploadProofUiState.Loading) {
                                CircularProgressIndicator(color = white, modifier = Modifier.size(22.dp))
                            } else {
                                Text(
                                    text = "Next",
                                    fontWeight = FontWeight.Bold,
                                    fontSize = 15.sp
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(20.dp))

                        Text(
                            text = "Enjoy your meal! Lower Budget More Delicious",
                            color = white,
                            fontSize = 11.sp,
                            textAlign = TextAlign.Center,
                            modifier = Modifier.align(Alignment.CenterHorizontally)
                        )

                        Spacer(modifier = Modifier.height(8.dp))
                    }
                }
            }
        }
    }
}

private fun formatRupiah(amount: Int): String =
    String.format(java.util.Locale.getDefault(), "%,d", amount).replace(',', '.')

private fun uriToFile(context: android.content.Context, uri: Uri): File? {
    return try {
        val inputStream = context.contentResolver.openInputStream(uri) ?: return null
        val tempFile = File(context.cacheDir, "payment_proof_${System.currentTimeMillis()}.jpg")
        FileOutputStream(tempFile).use { output ->
            inputStream.copyTo(output)
        }
        inputStream.close()
        tempFile
    } catch (e: Exception) {
        null
    }
}