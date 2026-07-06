package com.week3.ecoeats.screens.Category

import com.week3.ecoeats.R
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
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import coil.compose.AsyncImage
import com.week3.ecoeats.data.FoodListing
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.viewmodel.CategoryDetailViewModel

@Composable
fun CategoryDetailScreen(
    categoryId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { DashboardRepository(
        api = RetrofitInstance.createFoodListingApi(context)) }
    val viewModel: CategoryDetailViewModel = viewModel(
        factory = CategoryDetailViewModel.Factory(repository, categoryId)
    )
    val uiState by viewModel.uiState.collectAsState()

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(DarkOliveGreen)
    ) {
        TopBar(
            categoryName = uiState.categoryName.ifBlank { "Kategori" },
            onBack = { navController.popBackStack() }
        )

        when {
            uiState.isLoading -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = Cornsilk)
                }
            }
            uiState.errorMessage != null -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    Text(text = uiState.errorMessage ?: "", color = Color.Red)
                }
            }
            uiState.listings.isEmpty() -> {
                Box(modifier = Modifier.fillMaxSize(), contentAlignment = Alignment.Center) {
                    Text("Belum ada menu di kategori ini.", color = Cornsilk)
                }
            }
            else -> {
                LazyColumn {
                    items(uiState.listings) { food ->
                        CategoryMenuCard(food = food, navController = navController)
                    }
                }
            }
        }
    }
}

@Composable
fun TopBar(categoryName: String, onBack: () -> Unit) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .background(DarkOliveGreen)
            .statusBarsPadding()
            .padding(horizontal = 16.dp, vertical = 12.dp),
        verticalAlignment = Alignment.CenterVertically,
        horizontalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        Button(
            onClick = { onBack() },
            colors = ButtonDefaults.buttonColors(containerColor = DarkOliveGreen),
            modifier = Modifier.size(36.dp),
            contentPadding = PaddingValues(0.dp),
            shape = RoundedCornerShape(8.dp)
        ) {
            Text("<", color = CharcoalBrown, fontSize = 16.sp, fontWeight = FontWeight.Bold)
        }
        Text(
            text = categoryName.uppercase(),
            color = Cornsilk,
            fontSize = 16.sp,
            fontWeight = FontWeight.Bold
        )
    }
}

@Composable
fun CategoryMenuCard(food: FoodListing, navController: NavController) {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp, vertical = 14.dp)
            // Klik di area kartu (selain tombol Detail/Maps) -> ke Detail Menu juga
            .clickable { navController.navigate("food-detail/${food.id}") },
        horizontalArrangement = Arrangement.spacedBy(8.dp)
    ) {
        Card(
            modifier = Modifier.size(110.dp),
            shape = RoundedCornerShape(12.dp)
        ) {
            AsyncImage(
                model = if (food.photoUrl.isBlank()) {
                    R.drawable.gambar2
                } else {
                    val url = com.week3.ecoeats.data.util.resolvePhotoUrl(food.photoUrl)
                    android.util.Log.d("PHOTO_URL", "photoUrl raw: '${food.photoUrl}' -> resolved: '$url'")
                    url
                },
                contentDescription = food.name,
                contentScale = ContentScale.Crop,
                modifier = Modifier.fillMaxSize()
            )
        }

        Card(
            modifier = Modifier
                .weight(1f)
                .height(110.dp),
            shape = RoundedCornerShape(12.dp),
            colors = CardDefaults.cardColors(containerColor = Cornsilk)
        ) {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .padding(horizontal = 6.dp, vertical = 8.dp),
                verticalArrangement = Arrangement.Top
            ) {
                Column(verticalArrangement = Arrangement.spacedBy(3.dp)) {
                    Text(
                        food.name.uppercase(),
                        fontWeight = FontWeight.Bold,
                        fontSize = 11.sp,
                        lineHeight = 13.sp,
                        color = CharcoalBrown,
                        maxLines = 2,
                        overflow = TextOverflow.Ellipsis
                    )
                    Spacer(modifier = Modifier.height(3.dp))
                    Text(
                        food.restoran,
                        fontSize = 9.sp,
                        lineHeight = 11.sp,
                        color = CharcoalBrown.copy(alpha = 0.6f),
                        maxLines = 1,
                        overflow = TextOverflow.Ellipsis
                    )
                    Spacer(modifier = Modifier.height(3.dp))
                    Text(
                        "Rp. ${food.harga}",
                        fontSize = 10.sp,
                        lineHeight = 12.sp,
                        color = CharcoalBrown,
                        fontWeight = FontWeight.Bold
                    )
                    Spacer(modifier = Modifier.height(7.dp))
                }
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.End
                ) {
                    Button(
                        onClick = { navController.navigate("food-detail/${food.id}") },
                        colors = ButtonDefaults.buttonColors(containerColor = LaurelGreen),
                        modifier = Modifier.height(28.dp).width(70.dp),
                        contentPadding = PaddingValues(0.dp)
                    ) {
                        Text("Detail", fontSize = 10.sp, color = CharcoalBrown)
                    }
                    Spacer(modifier = Modifier.width(6.dp))
                    Button(
                        onClick = { navController.navigate("maps/${food.id}") },
                        colors = ButtonDefaults.buttonColors(containerColor = LaurelGreen),
                        modifier = Modifier.height(28.dp).width(70.dp),
                        contentPadding = PaddingValues(0.dp)
                    ) {
                        Text("Maps", fontSize = 10.sp, color = CharcoalBrown)
                    }
                }
            }
        }
    }
}