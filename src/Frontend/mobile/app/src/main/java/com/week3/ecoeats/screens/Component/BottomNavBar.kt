package com.week3.ecoeats.screens.components

import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.ShoppingCart
import androidx.compose.material3.Icon
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.NavigationBarItemDefaults
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.unit.dp
import androidx.navigation.NavController
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen

@Composable
fun BottomNavBar(
    navController: NavController,
    currentRoute: String,
    modifier: Modifier = Modifier
) {
    NavigationBar(
        modifier = modifier
            .clip(RoundedCornerShape(topStart = 24.dp, topEnd = 24.dp)),
        containerColor = DarkOliveGreen
    ) {
        NavigationBarItem(
            selected = currentRoute == "dashboard",
            onClick = { navController.navigate("dashboard") },
            icon = {
                Icon(
                    imageVector = Icons.Default.Home,
                    contentDescription = "Home"
                )
            },
            label = { Text("Home") },
            colors = NavigationBarItemDefaults.colors(
                selectedIconColor = Cornsilk,
                unselectedIconColor = Cornsilk.copy(alpha = 0.5f),
                selectedTextColor = Cornsilk,
                unselectedTextColor = Cornsilk.copy(alpha = 0.5f),
                indicatorColor = DarkOliveGreen
            )
        )
        NavigationBarItem(
            selected = currentRoute == "order-history",
            onClick = { navController.navigate("order-history") },
            icon = {
                Icon(
                    imageVector = Icons.Default.ShoppingCart,
                    contentDescription = "Pesanan"
                )
            },
            label = { Text("Pesanan") },
            colors = NavigationBarItemDefaults.colors(
                selectedIconColor = Cornsilk,
                unselectedIconColor = Cornsilk.copy(alpha = 0.5f),
                selectedTextColor = Cornsilk,
                unselectedTextColor = Cornsilk.copy(alpha = 0.5f),
                indicatorColor = DarkOliveGreen
            )
        )
        NavigationBarItem(
            selected = currentRoute == "profile",
            onClick = { navController.navigate("profile") },
            icon = {
                Icon(
                    imageVector = Icons.Default.Person,
                    contentDescription = "Profile"
                )
            },
            label = { Text("Profile") },
            colors = NavigationBarItemDefaults.colors(
                selectedIconColor = Cornsilk,
                unselectedIconColor = Cornsilk.copy(alpha = 0.5f),
                selectedTextColor = Cornsilk,
                unselectedTextColor = Cornsilk.copy(alpha = 0.5f),
                indicatorColor = DarkOliveGreen
            )
        )
    }
}