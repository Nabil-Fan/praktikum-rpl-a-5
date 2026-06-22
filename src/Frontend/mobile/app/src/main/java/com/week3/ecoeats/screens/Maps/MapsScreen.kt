package com.week3.ecoeats.screens.Maps

import android.preference.PreferenceManager
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.statusBarsPadding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.compose.ui.viewinterop.AndroidView
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import androidx.navigation.compose.currentBackStackEntryAsState
import org.osmdroid.config.Configuration
import org.osmdroid.tileprovider.tilesource.TileSourceFactory
import org.osmdroid.util.GeoPoint
import org.osmdroid.views.MapView
import org.osmdroid.views.overlay.Marker
import com.week3.ecoeats.data.remote.RetrofitInstance
import com.week3.ecoeats.data.remote.DashboardRepository
import com.week3.ecoeats.screens.components.BottomNavBar
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.LaurelGreen
import com.week3.ecoeats.viewmodel.FoodDetailViewModel

@Composable
fun MapsScreen(
    foodId: Int,
    navController: NavController
) {
    val context = LocalContext.current
    val repository = remember { DashboardRepository(api = RetrofitInstance.createFoodListingApi(context)) }
    val viewModel: FoodDetailViewModel = viewModel(
        factory = FoodDetailViewModel.Factory(repository, foodId)
    )
    val uiState by viewModel.uiState.collectAsState()
    val currentRoute = navController.currentBackStackEntryAsState().value?.destination?.route ?: ""

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(LaurelGreen)
    ) {
        // ── Header ──
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .background(LaurelGreen)
                .statusBarsPadding()
                .padding(horizontal = 16.dp, vertical = 12.dp),
            contentAlignment = Alignment.Center
        ) {
            Text(
                text = "MAPS",
                color = CharcoalBrown,
                fontSize = 16.sp,
                fontWeight = FontWeight.Bold
            )
        }

        when {
            uiState.isLoading -> {
                Box(modifier = Modifier.weight(1f).fillMaxWidth(), contentAlignment = Alignment.Center) {
                    CircularProgressIndicator(color = CharcoalBrown)
                }
            }
            uiState.errorMessage != null -> {
                Box(modifier = Modifier.weight(1f).fillMaxWidth(), contentAlignment = Alignment.Center) {
                    Text(uiState.errorMessage ?: "", color = Color.Red)
                }
            }
            uiState.food != null -> {
                val food = uiState.food!!
                val merchant = food.merchant
                val lat = merchant?.latitude?.toDoubleOrNull() ?: 0.0
                val lng = merchant?.longitude?.toDoubleOrNull() ?: 0.0
                val businessName = merchant?.storeName ?: "-"
                val businessAddress = merchant?.businessAddress ?: "-"

                // ── Info merchant ──
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .background(LaurelGreen)
                        .padding(horizontal = 16.dp, vertical = 8.dp)
                ) {
                    Text(
                        text = businessName.uppercase(),
                        color = CharcoalBrown,
                        fontWeight = FontWeight.Bold,
                        fontSize = 14.sp
                    )
                    Spacer(modifier = Modifier.height(2.dp))
                    Text(
                        text = businessAddress,
                        color = CharcoalBrown.copy(alpha = 0.8f),
                        fontSize = 11.sp
                    )
                }

                Spacer(modifier = Modifier.height(8.dp))

                // ── Peta OSMDroid — dibungkus Box rounded biar tetap rapi pas di-zoom/geser ──
                Box(
                    modifier = Modifier
                        .weight(1f)
                        .fillMaxWidth()
                        .padding(horizontal = 16.dp)
                        .clip(RoundedCornerShape(20.dp))
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
                                // MAPNIK = tile OpenStreetMap standar, jalan & gang otomatis kelihatan
                                setTileSource(TileSourceFactory.MAPNIK)
                                setMultiTouchControls(true) // bisa di-pinch zoom & digeser, tetap dalam box ini
                                controller.setZoom(16.0)
                                controller.setCenter(GeoPoint(lat, lng))

                                val marker = Marker(this)
                                marker.position = GeoPoint(lat, lng)
                                marker.title = businessName
                                marker.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM)
                                overlays.add(marker)
                            }
                        }
                    )
                }

                Spacer(modifier = Modifier.height(8.dp))

                // ── Info koordinat ──
                Column(
                    modifier = Modifier
                        .fillMaxWidth()
                        .background(LaurelGreen)
                        .padding(horizontal = 16.dp, vertical = 8.dp)
                ) {
                    Text("Latitude: $lat", color = CharcoalBrown, fontSize = 11.sp)
                    Text("Longitude: $lng", color = CharcoalBrown, fontSize = 11.sp)
                }
            }
        }

        // ── Bottom Nav Bar ──
        BottomNavBar(
            navController = navController,
            currentRoute = currentRoute
        )
    }
}