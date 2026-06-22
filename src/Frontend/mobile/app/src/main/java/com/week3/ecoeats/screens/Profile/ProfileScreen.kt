package com.week3.ecoeats.screens.Profile

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.statusBarsPadding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.Text
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Person
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
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
import androidx.navigation.compose.currentBackStackEntryAsState
import com.week3.ecoeats.screens.components.BottomNavBar
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.viewmodel.ProfileViewModel

@Composable
fun ProfileScreen(
    navController: NavController,
    profileViewModel: ProfileViewModel = viewModel()
) {
    val uiState by profileViewModel.uiState.collectAsState()
    val currentRoute = navController.currentBackStackEntryAsState().value?.destination?.route ?: "profile"

    // Tarik ulang data profil tiap kali layar ini kembali ke composition
    // (misal abis balik dari Edit Profile). Perlu, karena ProfileScreen dan
    // EditProfileScreen masing-masing punya instance ProfileViewModel sendiri
    // (terikat ke NavBackStackEntry masing-masing), jadi update di satu layar
    // gak otomatis kelihatan di layar lain tanpa di-refresh ulang dari server.
    LaunchedEffect(Unit) {
        profileViewModel.loadProfile()
    }

    // Begitu logout sukses, balik ke layar Auth & hapus seluruh back stack
    // (biar tombol back gak bisa nyasar balik ke halaman yang butuh login)
    LaunchedEffect(uiState.isLoggedOut) {
        if (uiState.isLoggedOut) {
            navController.navigate("auth") {
                popUpTo(0)
            }
        }
    }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        Column(modifier = Modifier.weight(1f)) {
            // ── Header ──
            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .statusBarsPadding()
                    .padding(horizontal = 20.dp, vertical = 16.dp)
            ) {
                Text(
                    text = "My Profile",
                    color = CharcoalBrown,
                    fontSize = 18.sp,
                    fontWeight = FontWeight.Bold,
                    textAlign = TextAlign.Center,
                    modifier = Modifier.align(Alignment.Center)
                )
            }

            // ── Avatar + info ──
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 24.dp, vertical = 12.dp),
                verticalAlignment = Alignment.CenterVertically
            ) {
                Box(
                    modifier = Modifier
                        .size(64.dp)
                        .clip(CircleShape)
                        .background(Color(0xFFD9D9D9)),
                    contentAlignment = Alignment.Center
                ) {
                    Icon(
                        imageVector = Icons.Default.Person,
                        contentDescription = "Avatar",
                        tint = Color(0xFF9E9E9E),
                        modifier = Modifier.size(36.dp)
                    )
                }
                Spacer(modifier = Modifier.width(16.dp))
                Column {
                    Text(
                        text = if (uiState.isLoading) "Memuat..." else uiState.name.ifBlank { "-" },
                        color = CharcoalBrown,
                        fontWeight = FontWeight.Bold,
                        fontSize = 16.sp
                    )
                    Text(
                        text = uiState.email.ifBlank { "-" },
                        color = CharcoalBrown.copy(alpha = 0.6f),
                        fontSize = 12.sp
                    )
                }
            }

            Spacer(modifier = Modifier.height(20.dp))

            // ── Menu rows ──
            Column(modifier = Modifier.padding(horizontal = 24.dp)) {
                ProfileMenuRow(
                    label = "Edit Profile",
                    onClick = { navController.navigate("edit-profile") }
                )
                ProfileMenuRow(
                    label = "Ganti Password",
                    onClick = { navController.navigate("change-password") }
                )
                ProfileMenuRow(
                    label = "Riwayat Pesanan",
                    onClick = { navController.navigate("order-history") }
                )
                ProfileMenuRow(
                    label = "Keluar",
                    onClick = { profileViewModel.logout() }
                )
            }
        }

        // ── Bottom Nav Bar ──
        BottomNavBar(
            navController = navController,
            currentRoute = currentRoute
        )
    }
}

@Composable
fun ProfileMenuRow(label: String, onClick: () -> Unit) {
    Column {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .clickable(onClick = onClick)
                .padding(vertical = 16.dp),
            horizontalArrangement = Arrangement.SpaceBetween,
            verticalAlignment = Alignment.CenterVertically
        ) {
            Text(label, fontSize = 15.sp, color = CharcoalBrown)
            Text(">", fontSize = 16.sp, color = CharcoalBrown.copy(alpha = 0.4f))
        }
        HorizontalDivider(color = CharcoalBrown.copy(alpha = 0.15f))
    }
}