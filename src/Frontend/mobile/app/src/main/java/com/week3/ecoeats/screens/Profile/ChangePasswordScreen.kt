package com.week3.ecoeats.screens.Profile

import androidx.compose.foundation.background
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
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.viewmodel.ProfileViewModel

@Composable
fun ChangePasswordScreen(
    navController: NavController,
    profileViewModel: ProfileViewModel = viewModel()
) {
    val uiState by profileViewModel.uiState.collectAsState()

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
                modifier = Modifier
                    .size(36.dp)
                    .align(Alignment.CenterStart),
                contentPadding = PaddingValues(0.dp),
                shape = RoundedCornerShape(8.dp)
            ) {
                Text("<", color = DarkOliveGreen, fontSize = 16.sp, fontWeight = FontWeight.Bold)
            }
            Text(
                text = "Ganti Password",
                color = DarkOliveGreen,
                fontSize = 18.sp,
                fontWeight = FontWeight.Bold,
                textAlign = TextAlign.Center,
                modifier = Modifier.align(Alignment.Center)
            )
        }

        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
                .padding(horizontal = 24.dp)
        ) {
            Spacer(modifier = Modifier.height(20.dp))

            EditField(
                label = "Password Saat Ini",
                value = uiState.currentPassword,
                onValueChange = { profileViewModel.onCurrentPasswordChange(it) },
                isPassword = true
            )
            Spacer(modifier = Modifier.height(18.dp))

            EditField(
                label = "Password Baru",
                value = uiState.newPassword,
                onValueChange = { profileViewModel.onNewPasswordChange(it) },
                isPassword = true
            )
            Spacer(modifier = Modifier.height(18.dp))

            EditField(
                label = "Konfirmasi Password Baru",
                value = uiState.newPasswordConfirmation,
                onValueChange = { profileViewModel.onNewPasswordConfirmationChange(it) },
                isPassword = true
            )

            if (uiState.passwordError != null) {
                Spacer(modifier = Modifier.height(16.dp))
                Text(
                    text = uiState.passwordError ?: "",
                    color = Color(0xFFB3261E),
                    fontSize = 13.sp
                )
            }

            Spacer(modifier = Modifier.height(32.dp))

            Button(
                onClick = { profileViewModel.changePassword() },
                enabled = !uiState.isChangingPassword,
                modifier = Modifier
                    .fillMaxWidth()
                    .height(52.dp),
                shape = RoundedCornerShape(12.dp),
                colors = ButtonDefaults.buttonColors(
                    containerColor = DarkOliveGreen,
                    contentColor = Cornsilk
                )
            ) {
                Text(
                    if (uiState.isChangingPassword) "Menyimpan..." else "Ganti Password",
                    fontWeight = FontWeight.Bold
                )
            }

            Spacer(modifier = Modifier.height(32.dp))
        }
    }
}