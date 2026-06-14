package com.week3.ecoeats.screens.Dashboard

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.unit.dp
import androidx.navigation.NavController
import androidx.navigation.compose.currentBackStackEntryAsState
import com.week3.ecoeats.R
import com.week3.ecoeats.data.Category
import com.week3.ecoeats.data.FoodListing
import com.week3.ecoeats.screens.Dashboard.component.CategorySection
import com.week3.ecoeats.screens.Dashboard.component.GreetingCard
import com.week3.ecoeats.screens.Dashboard.component.MenuSection
import com.week3.ecoeats.screens.components.BottomNavBar
import com.week3.ecoeats.screens.components.MainImage
import com.week3.ecoeats.ui.theme.Cornsilk

@Composable
fun DashboardScreen(
    navController: NavController,
    username: String = "user"
) {
    var query by remember { mutableStateOf("") }

    val categories = listOf(
        Category("Semua", R.drawable.ic_semua),
        Category("Nasi", R.drawable.ic_nasi),
        Category("Roti", R.drawable.ic_roti),
        Category("Mie", R.drawable.ic_mie)
    )

    val menuList = emptyList<FoodListing>()

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        // Layer 1 — gambar atas
        MainImage()

        // Layer 2 — konten scrollable
        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
        ) {
            // spacer supaya konten mulai di bawah gambar
            Spacer(modifier = Modifier.height(250.dp))

            // GreetingCard overlap di perbatasan gambar & cornsilk
            Box(modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 24.dp)) {
                GreetingCard(
                    username = username,
                    query = query,
                    onQueryChange = { query = it },
                    modifier = Modifier
                        .fillMaxWidth()
                        //.align(Alignment.Center)
                        .clip(RoundedCornerShape(16.dp))
                )
            }

            Spacer(modifier = Modifier.height(16.dp))

            // Konten bawah
            Column(
                modifier = Modifier
                    .fillMaxWidth()
                    .background(Cornsilk)
                    .padding(horizontal = 16.dp)
            ) {
                Spacer(modifier = Modifier.height(8.dp))
                CategorySection(categories = categories, navController = navController)
                Spacer(modifier = Modifier.height(16.dp))
                MenuSection(menuList = menuList)
                Spacer(modifier = Modifier.height(80.dp))
            }
        }
        val currentRoute = navController.currentBackStackEntryAsState().value?.destination?.route ?: "dashboard"
        BottomNavBar(
            navController = navController,
            currentRoute = currentRoute,
            modifier = Modifier.align(Alignment.BottomCenter)
        )
    }
}