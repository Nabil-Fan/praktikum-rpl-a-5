package com.week3.ecoeats.screens.Auth

import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.ui.layout.ContentScale
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Button
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.shape.RoundedCornerShape
import com.week3.ecoeats.R
import androidx.compose.material3.ButtonDefaults
import androidx.compose.ui.Alignment
import androidx.compose.ui.draw.clip
import androidx.navigation.NavController
import androidx.navigation.compose.rememberNavController
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.screens.components.MainImage

@Composable
fun AuthScreen(navController: NavController) {
    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        MainImage()
        BottomSheet(
            navController = navController,
            modifier = Modifier
                .fillMaxHeight(0.62f)
                .align(Alignment.BottomCenter)
        )
    }
}

@Composable
fun WelcomeText() {
    Text(
        text = "SELAMAT DATANG DI ECOEATS!",
        color = DarkOliveGreen,
        fontSize = 22.sp,
        fontWeight = FontWeight.Bold,
        textAlign = TextAlign.Center,
        lineHeight = 32.sp
    )
}

@Composable
fun ButtonDaftar(navController: NavController) {
    Button(
        onClick = { navController.navigate("signup") },
        modifier = Modifier
            .fillMaxWidth()
            .height(56.dp),
        shape = MaterialTheme.shapes.large,
        colors = ButtonDefaults.buttonColors(
            containerColor = DarkOliveGreen,
            contentColor = white
        )
    ) {
        Text(
            text = "DAFTAR"
        )
    }
}

@Composable
fun ButtonMasuk(navController: NavController) {
    Button(
        onClick = { navController.navigate("SignIn") },
        modifier = Modifier
            .fillMaxWidth()
            .height(54.dp),
        shape = MaterialTheme.shapes.large,
        colors = ButtonDefaults.buttonColors(
            containerColor = DarkOliveGreen,
            contentColor = white
        )
    ) {
        Text(
            text = "MASUK"
        )
    }
}

@Composable
fun BottomSheet(navController: NavController, modifier: Modifier = Modifier) {
    Column(
        modifier = modifier
            .fillMaxWidth()
            .clip(RoundedCornerShape(topStart = 32.dp, topEnd = 32.dp))
            .background(Cornsilk)
            .padding(horizontal = 32.dp, vertical = 40.dp),
        verticalArrangement = Arrangement.SpaceBetween,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Spacer(modifier = Modifier.height(42.dp))
        WelcomeText()

        Column(
            modifier = Modifier.fillMaxWidth()
        ) {
            ButtonDaftar(navController = navController)
            Spacer(modifier = Modifier.height(36.dp))
            ButtonMasuk(navController = navController)
            Spacer(modifier = Modifier.height(48.dp))
        }
    }
}