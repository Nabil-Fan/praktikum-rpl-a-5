package com.week3.ecoeats.screens.Dashboard

import android.util.Log
import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.LazyRow
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Search
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.navigation.NavController
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.compose.currentBackStackEntryAsState
import coil.compose.AsyncImage
import com.week3.ecoeats.R
import com.week3.ecoeats.data.model.Category
import com.week3.ecoeats.data.model.FoodListing
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.data.util.resolvePhotoUrl
import com.week3.ecoeats.screens.Dashboard.component.CategorySection
import com.week3.ecoeats.viewmodel.DashboardViewModel
import java.text.NumberFormat
import java.util.Locale
import com.week3.ecoeats.screens.Dashboard.component.GreetingCard
import com.week3.ecoeats.screens.components.MainImage
import com.week3.ecoeats.screens.components.BottomNavBar


// ── Color Tokens (sesuai mockup: olive-green + cream) ───────────────────────
private val GreenPrimary  = Color(0xFF4A5C3F)
private val GreenLight    = Color(0xFF6B7F5E)
private val Cream         = Color(0xFFF5F0E8)
private val CardBg        = Color(0xFFFFFFFF)
private val TextPrimary   = Color(0xFF1A1A1A)
private val TextSecondary = Color(0xFF6B6B6B)

// ── Screen ───────────────────────────────────────────────────────────────────

@Composable
fun DashboardScreen(
    navController: NavController,
    // Default-nya langsung navigate via navController.
    // Klik kartu ATAU tombol "Detail" -> ke halaman Detail Menu.
    // Klik tombol "Maps" -> ke halaman Maps.
    onDetailClick: (Int) -> Unit = { id -> navController.navigate("food-detail/$id") },
    onMapsClick: (Int) -> Unit = { id -> navController.navigate("maps/$id") }
) {
    val context = LocalContext.current
    val repository = remember {
        DashboardRepository(RetrofitInstance.createFoodListingApi(context))
    }
    val viewModel: DashboardViewModel = viewModel(
        factory = DashboardViewModel.Factory(repository)
    )
    val state by viewModel.uiState.collectAsStateWithLifecycle()

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(Cream)
    ) {
        MainImage()

        // Column utama: header & kategori FIXED (tidak ikut scroll vertikal),
        // hanya "Menu Tersedia" yang scrollable lewat LazyColumn ber-weight.
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(top = 250.dp, bottom = 100.dp)
        ) {

            // ── FIXED: Greeting + Search ──────────────────────────────────
            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 24.dp)
            ) {
                GreetingCard(
                    username = "User",
                    query = state.searchQuery,
                    onQueryChange = viewModel::onSearchQueryChange,
                    modifier = Modifier
                        .fillMaxWidth()
                        .clip(RoundedCornerShape(16.dp))
                )
            }

            Spacer(modifier = Modifier.height(16.dp))

            // ── FIXED: Kategori (scroll horizontal saja, bukan vertikal) ──
            CategorySection(
                categories = state.categories,
                navController = navController
            )

            Text(
                text = "MENU TERSEDIA",
                fontSize = 13.sp,
                fontWeight = FontWeight.Bold,
                color = TextSecondary,
                modifier = Modifier.padding(
                    horizontal = 24.dp,
                    vertical = 4.dp
                )
            )

            // ── SCROLLABLE: hanya bagian ini yang scroll vertikal ─────────
            // weight(1f) membatasi tinggi area ini supaya tidak menutupi
            // header/kategori di atas maupun BottomNavBar di bawah.
            LazyColumn(
                modifier = Modifier
                    .fillMaxWidth()
                    .weight(1f),
                contentPadding = PaddingValues(bottom = 16.dp)
            ) {
                when {
                    state.isLoading -> item {
                        Box(
                            Modifier
                                .fillMaxWidth()
                                .padding(32.dp),
                            Alignment.Center
                        ) {
                            CircularProgressIndicator()
                        }
                    }

                    state.errorMessage != null -> item {
                        ErrorCard(
                            message = state.errorMessage!!,
                            onRetry = viewModel::refresh
                        )
                    }

                    state.listings.isEmpty() -> item {
                        EmptyState()
                    }

                    else -> items(
                        state.listings,
                        key = { it.id }
                    ) { listing ->
                        FoodListingCard(
                            listing = listing,
                            onDetailClick = {
                                onDetailClick(listing.id)
                            },
                            onMapsClick = {
                                onMapsClick(listing.id)
                            }
                        )
                    }
                }
            }
        }

        val currentRoute =
            navController.currentBackStackEntryAsState()
                .value?.destination?.route ?: "dashboard"

        BottomNavBar(
            navController = navController,
            currentRoute = currentRoute,
            modifier = Modifier.align(Alignment.BottomCenter)
        )
    }
}


// ── Header ───────────────────────────────────────────────────────────────────

@Composable
private fun DashboardHeader() {
    Box(
        modifier = Modifier
            .fillMaxWidth()
            .height(160.dp)
            .background(GreenPrimary)
            .padding(16.dp),
        contentAlignment = Alignment.BottomStart
    ) {
        Text(
            text = "Hai, user!",
            color = Color.White,
            fontSize = 20.sp,
            fontWeight = FontWeight.SemiBold
        )
    }
}

// ── Search ───────────────────────────────────────────────────────────────────

@Composable
private fun SearchBar(
    query: String,
    onQueryChange: (String) -> Unit,
    modifier: Modifier = Modifier
) {
    OutlinedTextField(
        value = query,
        onValueChange = onQueryChange,
        placeholder = { Text("PENCARIAN", color = TextSecondary, fontSize = 13.sp) },
        leadingIcon = { Icon(Icons.Default.Search, contentDescription = null, tint = GreenPrimary) },
        singleLine = true,
        shape = RoundedCornerShape(24.dp),
        colors = OutlinedTextFieldDefaults.colors(
            focusedBorderColor = GreenPrimary,
            unfocusedBorderColor = Color(0xFFCCCCCC),
            focusedContainerColor = CardBg,
            unfocusedContainerColor = CardBg
        ),
        modifier = modifier.fillMaxWidth()
    )
}

// ── Categories ────────────────────────────────────────────────────────────────

@Composable
private fun CategoryRow(
    categories: List<Category>,
    selectedId: Int?,
    onSelect: (Int?) -> Unit
) {
    Column(modifier = Modifier.padding(horizontal = 16.dp)) {
        Text(
            text = "KATEGORI",
            fontSize = 13.sp,
            fontWeight = FontWeight.Bold,
            color = TextSecondary,
            letterSpacing = 1.sp
        )
        Spacer(Modifier.height(8.dp))
        LazyRow(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
            // "SEMUA" chip
            item {
                CategoryChip(
                    label = "SEMUA",
                    selected = selectedId == null,
                    onClick = { onSelect(null) }
                )
            }
            items(categories, key = { it.id }) { cat ->
                CategoryChip(
                    label = cat.name.uppercase(),
                    selected = selectedId == cat.id,
                    onClick = { onSelect(cat.id) }
                )
            }
        }
    }
}

@Composable
private fun CategoryChip(label: String, selected: Boolean, onClick: () -> Unit) {
    Box(
        modifier = Modifier
            .clip(CircleShape)
            .background(if (selected) GreenPrimary else CardBg)
            .border(1.dp, if (selected) GreenPrimary else Color(0xFFCCCCCC), CircleShape)
            .clickable(onClick = onClick)
            .padding(horizontal = 16.dp, vertical = 8.dp),
        contentAlignment = Alignment.Center
    ) {
        Text(
            text = label,
            color = if (selected) Color.White else TextPrimary,
            fontSize = 12.sp,
            fontWeight = FontWeight.Medium
        )
    }
}

// ── Food Listing Card ─────────────────────────────────────────────────────────

@Composable
private fun FoodListingCard(
    listing: FoodListing,
    onDetailClick: () -> Unit,
    onMapsClick: () -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp, vertical = 6.dp)
            // Klik di area kosong kartu (selain tombol Detail/Maps) -> ke Detail Menu juga
            .clickable(onClick = onDetailClick),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = CardBg),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Row(
            modifier = Modifier.padding(12.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Thumbnail
            Box(
                modifier = Modifier
                    .size(72.dp)
                    .clip(RoundedCornerShape(8.dp))
                    .background(Color(0xFFE0E0E0))
            ) {
                AsyncImage(
                    model = if (listing.imageUrl.isNullOrBlank()) R.drawable.gambar2 else resolvePhotoUrl(listing.imageUrl),
                    contentDescription = listing.name,
                    contentScale = ContentScale.Crop,
                    modifier = Modifier.fillMaxSize()
                )
            }

            Spacer(Modifier.width(12.dp))

            // Info
            Column(modifier = Modifier.weight(1f)) {
                Text(
                    text = listing.name.uppercase(),
                    fontWeight = FontWeight.Bold,
                    fontSize = 13.sp,
                    color = TextPrimary,
                    maxLines = 1,
                    overflow = TextOverflow.Ellipsis
                )
                Text(
                    text = listing.merchant?.storeName ?: "-",
                    fontSize = 11.sp,
                    color = TextSecondary,
                    maxLines = 1
                )
                Spacer(Modifier.height(4.dp))
                Text(
                    text = formatRupiah(listing.discountedPrice),
                    fontWeight = FontWeight.SemiBold,
                    fontSize = 13.sp,
                    color = GreenPrimary
                )
                Spacer(Modifier.height(6.dp))
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    OutlinedButton(
                        onClick = onDetailClick,
                        shape = RoundedCornerShape(8.dp),
                        contentPadding = PaddingValues(horizontal = 12.dp, vertical = 4.dp),
                        border = androidx.compose.foundation.BorderStroke(1.dp, GreenPrimary),
                        modifier = Modifier.height(30.dp)
                    ) {
                        Text("Detail", fontSize = 11.sp, color = GreenPrimary)
                    }
                    Button(
                        onClick = onMapsClick,
                        shape = RoundedCornerShape(8.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = GreenLight),
                        contentPadding = PaddingValues(horizontal = 12.dp, vertical = 4.dp),
                        modifier = Modifier.height(30.dp)
                    ) {
                        Text("Maps", fontSize = 11.sp, color = Color.White)
                    }
                }
            }
        }
    }
}

// ── Empty / Error states ──────────────────────────────────────────────────────

@Composable
private fun EmptyState() {
    Box(
        modifier = Modifier.fillMaxWidth().padding(48.dp),
        contentAlignment = Alignment.Center
    ) {
        Text("Tidak ada menu tersedia", color = TextSecondary, fontSize = 14.sp)
    }
}

@Composable
private fun ErrorCard(message: String, onRetry: () -> Unit) {
    Column(
        modifier = Modifier.fillMaxWidth().padding(24.dp),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Text(message, color = MaterialTheme.colorScheme.error, fontSize = 14.sp)
        Spacer(Modifier.height(8.dp))
        Button(
            onClick = onRetry,
            colors = ButtonDefaults.buttonColors(containerColor = GreenPrimary)
        ) {
            Text("Coba lagi")
        }
    }
}

// ── Helpers ───────────────────────────────────────────────────────────────────

private fun formatRupiah(amount: Double): String {
    val format = NumberFormat.getCurrencyInstance(Locale("id", "ID"))
    return format.format(amount).replace(",00", "")
}