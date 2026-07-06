package com.week3.ecoeats.screens.Dashboard.component

import androidx.compose.foundation.Image
import com.week3.ecoeats.R
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.lazy.LazyRow
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.unit.dp
import com.week3.ecoeats.ui.theme.LaurelGreen
import androidx.compose.foundation.lazy.items
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.navigation.NavController
import com.week3.ecoeats.data.model.Category

/**
 * Cocokkan nama kategori dari API ke icon lokal.
 * Kalau semua kategori tampil dengan icon yang sama, kemungkinan besar
 * nama dari backend tidak match string di bawah (beda casing, ada spasi,
 * atau nama kategori belum di-mapping ke sini sama sekali).
 * Tambahkan branch baru sesuai nama kategori asli dari backend kamu.
 */
private fun getCategoryIcon(categoryName: String): Int {
    val normalized = categoryName.trim().lowercase()
    return when {
        normalized.contains("nasi") || normalized.contains("mie") -> R.drawable.ic_nasi
        normalized.contains("lauk") || normalized.contains("snack") -> R.drawable.ic_lauk_snack
        normalized.contains("minuman") -> R.drawable.ic_minuman
        normalized.contains("pastry") || normalized.contains("roti") -> R.drawable.ic_pastry
        else -> R.drawable.ic_semua
    }
}

@Composable
fun CategorySection(
    categories: List<Category>,
    navController: NavController
) {
    Column {
        Text(
            text = "KATEGORI",
            fontWeight = FontWeight.Bold,
            modifier = Modifier.padding(horizontal = 24.dp)
        )
        Spacer(modifier = Modifier.height(16.dp))

        LazyRow(
            modifier = Modifier.fillMaxWidth(),
            horizontalArrangement = Arrangement.spacedBy(16.dp),
            contentPadding = PaddingValues(horizontal = 24.dp)
        ) {
            items(categories, key = { it.id }) { category ->
                Column(
                    horizontalAlignment = Alignment.CenterHorizontally,
                    modifier = Modifier.clickable {
                        navController.navigate("category/${category.id}")
                    }
                ) {
                    Box(
                        modifier = Modifier
                            .size(64.dp)
                            .clip(CircleShape)
                            .background(LaurelGreen)
                    ) {
                        Image(
                            painter = painterResource(
                                getCategoryIcon(category.name)
                            ),
                            contentDescription = category.name,
                            contentScale = ContentScale.Crop,
                            modifier = Modifier.size(64.dp)
                        )
                    }
                    Spacer(modifier = Modifier.height(8.dp))
                    Text(text = category.name)
                }
            }
        }

        Spacer(modifier = Modifier.height(4.dp))
    }
}