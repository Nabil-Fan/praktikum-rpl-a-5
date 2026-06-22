package com.week3.ecoeats.screens.Checkout

import android.net.Uri
import androidx.activity.compose.rememberLauncherForActivityResult
import androidx.activity.result.contract.ActivityResultContracts
import androidx.compose.foundation.background
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
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.viewmodel.OrderUiState
import com.week3.ecoeats.viewmodel.OrderViewModel
import com.week3.ecoeats.viewmodel.UploadProofUiState
import java.io.File
import java.io.FileOutputStream
import androidx.compose.foundation.Image
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import com.week3.ecoeats.R
import com.week3.ecoeats.ui.theme.Camel

private val WarmBrown = Color(0xFFB99470)
private val WarmBrownLight = Color(0xFFD2B48C)

/**
 * Halaman Pembayaran — dibuka dari CheckoutScreen lewat tombol
 * "Lanjut ke Pembayaran". Tampilan instruksi bayar bersifat DUMMY
 * (placeholder QR / nomor rekening), karena backend tidak generate
 * kode pembayaran dinamis — user bayar manual di luar app, lalu upload
 * bukti lewat tombol di bawah.
 *
 * TODO: kalau nanti ada integrasi payment gateway asli (generate QRIS
 * dinamis / virtual account), ganti bagian dummy di bawah dengan data
 * dari response API yang sesuai.
 */
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
                text = "PEMBAYARAN",
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
                Box(modifier = Modifier.fillMaxSize().padding(24.dp), contentAlignment = Alignment.Center) {
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

                Column(
                    modifier = Modifier
                        .fillMaxSize()
                        .verticalScroll(rememberScrollState())
                ) {

                    Box(
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(420.dp)
                    ) {

                        Image(
                            painter = painterResource(R.drawable.gambar1),
                            contentDescription = null,
                            contentScale = ContentScale.Crop,
                            modifier = Modifier.fillMaxSize()
                        )

                        Column(
                            modifier = Modifier.fillMaxSize(),
                            horizontalAlignment = Alignment.CenterHorizontally
                        ) {

                            Spacer(modifier = Modifier.height(20.dp))

                            Text(
                                text = order.payment_method?.uppercase() ?: "PAYMENT",
                                color = CharcoalBrown,
                                fontWeight = FontWeight.Bold,
                                fontSize = 22.sp
                            )

                            Spacer(modifier = Modifier.height(20.dp))

                            when (order.payment_method?.lowercase()) {

                                "qris" -> {
                                    Box(
                                        modifier = Modifier
                                            .background(
                                                Camel,
                                                RoundedCornerShape(4.dp)
                                            )
                                            .padding(20.dp)
                                    ) {

                                        Image(
                                            painter = painterResource(R.drawable.qr_code),
                                            contentDescription = "QRIS",
                                            modifier = Modifier.size(220.dp)
                                        )
                                    }
                                }

                                "transfer" -> {
                                    Box(
                                        modifier = Modifier
                                            .background(
                                                Camel,
                                                RoundedCornerShape(4.dp)
                                            )
                                            .padding(24.dp)
                                    ) {

                                        Column(
                                            horizontalAlignment = Alignment.CenterHorizontally
                                        ) {

                                            Text(
                                                "BANK BCA",
                                                fontWeight = FontWeight.Bold,
                                                color = white
                                            )

                                            Spacer(modifier = Modifier.height(12.dp))

                                            Text(
                                                "1234567890",
                                                fontSize = 26.sp,
                                                color = white,
                                                fontWeight = FontWeight.Bold
                                            )

                                            Spacer(modifier = Modifier.height(8.dp))

                                            Text(
                                                "a.n EcoEats",
                                                color = white
                                            )
                                        }
                                    }
                                }

                                else -> {
                                    Box(
                                        modifier = Modifier
                                            .background(
                                                Camel,
                                                RoundedCornerShape(4.dp)
                                            )
                                            .padding(24.dp)
                                    ) {

                                        Column(
                                            horizontalAlignment = Alignment.CenterHorizontally
                                        ) {

                                            Text(
                                                "E-WALLET",
                                                color = white,
                                                fontWeight = FontWeight.Bold
                                            )

                                            Spacer(modifier = Modifier.height(12.dp))

                                            Text(
                                                "081234567890",
                                                fontSize = 24.sp,
                                                color = white,
                                                fontWeight = FontWeight.Bold
                                            )

                                            Spacer(modifier = Modifier.height(8.dp))

                                            Text(
                                                "a.n EcoEats",
                                                color = white
                                            )
                                        }
                                    }
                                }
                            }

                            Spacer(modifier = Modifier.height(20.dp))

                            Text(
                                text = "Total Amount to pay Rp ${formatRupiah(order.total_amount.toInt())}",
                                color = white,
                                fontSize = 16.sp,
                                fontWeight = FontWeight.Medium
                            )
                        }
                    }

                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .background(Camel)
                            .padding(16.dp)
                    ) {

                        Text(
                            text = "Upload Bukti Pembayaran :",
                            color = CharcoalBrown,
                            fontWeight = FontWeight.Bold
                        )

                        Spacer(modifier = Modifier.height(14.dp))

                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(90.dp)
                                .clip(RoundedCornerShape(8.dp))
                                .background(Cornsilk)
                                .border(
                                    1.dp,
                                    Camel.copy(alpha = 0.4f),
                                    RoundedCornerShape(8.dp)
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
                                    is UploadProofUiState.Success -> "File Uploaded ✓"
                                    is UploadProofUiState.Loading -> "Uploading..."
                                    else -> "Upload File"
                                },
                                color = CharcoalBrown.copy(alpha = 0.6f)
                            )
                        }

                        Spacer(modifier = Modifier.height(12.dp))

                        Button(
                            onClick = {
                                if (uploadState is UploadProofUiState.Success) {
                                    navController.navigate("dashboard") {
                                        popUpTo("dashboard") {
                                            inclusive = false
                                        }
                                    }
                                } else {
                                    imagePicker.launch("image/*")
                                }
                            },
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(48.dp),
                            shape = RoundedCornerShape(8.dp),
                            colors = ButtonDefaults.buttonColors(
                                containerColor = CharcoalBrown,
                                contentColor = white
                            )
                        ) {

                            if (uploadState is UploadProofUiState.Loading) {
                                CircularProgressIndicator(
                                    color = white
                                )
                            } else {
                                Text(
                                    text = "Next",
                                    fontWeight = FontWeight.Bold
                                )
                            }
                        }

                        Spacer(modifier = Modifier.height(18.dp))

                        Text(
                            text = "Enjoy your meal! Lower Budget More Delicious",
                            color = white,
                            fontSize = 11.sp,
                            modifier = Modifier.align(Alignment.CenterHorizontally)
                        )
                    }
                }
            }
            }
        }
    }

//@Composable
//private fun PaymentInstructionCard(paymentMethod: String?) {
//    when (paymentMethod?.lowercase()) {
//        "qris" -> QrisDummyCard()
//        "ewallet" -> EwalletDummyCard()
//        else -> TransferDummyCard() // default ke transfer kalau null/unknown
//    }
//}
//
//@Composable
//private fun QrisDummyCard() {
//    Column(
//        modifier = Modifier
//            .fillMaxWidth()
//            .clip(RoundedCornerShape(16.dp))
//            .background(WarmBrownLight)
//            .padding(20.dp),
//        horizontalAlignment = Alignment.CenterHorizontally
//    ) {
//        Text("Scan QRIS di bawah ini", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
//        Spacer(modifier = Modifier.height(16.dp))
//        // Placeholder kotak QR dummy — ganti dengan Image asli kalau sudah ada source-nya
//        Box(
//            modifier = Modifier
//                .size(200.dp)
//                .clip(RoundedCornerShape(12.dp))
//                .background(white),
//            contentAlignment = Alignment.Center
//        ) {
//            Text("[ QR CODE ]", color = CharcoalBrown.copy(alpha = 0.4f), fontSize = 13.sp)
//        }
//        Spacer(modifier = Modifier.height(12.dp))
//        Text(
//            text = "Buka aplikasi e-banking / e-wallet, lalu scan kode di atas",
//            color = CharcoalBrown.copy(alpha = 0.7f),
//            fontSize = 12.sp,
//            textAlign = TextAlign.Center
//        )
//    }
//}
//
//@Composable
//private fun TransferDummyCard() {
//    Column(
//        modifier = Modifier
//            .fillMaxWidth()
//            .clip(RoundedCornerShape(16.dp))
//            .background(WarmBrownLight)
//            .padding(20.dp)
//    ) {
//        Text("Transfer ke rekening berikut", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
//        Spacer(modifier = Modifier.height(16.dp))
//        Box(
//            modifier = Modifier
//                .fillMaxWidth()
//                .clip(RoundedCornerShape(12.dp))
//                .background(white)
//                .padding(16.dp)
//        ) {
//            Column {
//                Text("Bank EcoEats", color = CharcoalBrown.copy(alpha = 0.6f), fontSize = 12.sp)
//                Text("1234 5678 9012", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 18.sp)
//                Spacer(modifier = Modifier.height(8.dp))
//                Text("a.n. EcoEats Merchant", color = CharcoalBrown.copy(alpha = 0.6f), fontSize = 12.sp)
//            }
//        }
//        Spacer(modifier = Modifier.height(12.dp))
//        Text(
//            text = "Transfer sesuai nominal total, lalu upload bukti transfer",
//            color = CharcoalBrown.copy(alpha = 0.7f),
//            fontSize = 12.sp
//        )
//    }
//}
//
//@Composable
//private fun EwalletDummyCard() {
//    Column(
//        modifier = Modifier
//            .fillMaxWidth()
//            .clip(RoundedCornerShape(16.dp))
//            .background(WarmBrownLight)
//            .padding(20.dp)
//    ) {
//        Text("Bayar via E-Wallet", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 14.sp)
//        Spacer(modifier = Modifier.height(16.dp))
//        Box(
//            modifier = Modifier
//                .fillMaxWidth()
//                .clip(RoundedCornerShape(12.dp))
//                .background(white)
//                .padding(16.dp)
//        ) {
//            Column {
//                Text("Nomor E-Wallet", color = CharcoalBrown.copy(alpha = 0.6f), fontSize = 12.sp)
//                Text("0812-3456-7890", color = CharcoalBrown, fontWeight = FontWeight.Bold, fontSize = 18.sp)
//                Spacer(modifier = Modifier.height(8.dp))
//                Text("a.n. EcoEats Merchant", color = CharcoalBrown.copy(alpha = 0.6f), fontSize = 12.sp)
//            }
//        }
//        Spacer(modifier = Modifier.height(12.dp))
//        Text(
//            text = "Kirim sesuai nominal total, lalu upload bukti pembayaran",
//            color = CharcoalBrown.copy(alpha = 0.7f),
//            fontSize = 12.sp
//        )
//    }
//}

private fun formatRupiah(amount: Int): String =
    String.format(java.util.Locale.getDefault(), "%,d", amount).replace(',', '.')

/** Salin isi Uri (hasil pilih foto) ke file sementara di cache dir, biar bisa di-upload via Multipart. */
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
