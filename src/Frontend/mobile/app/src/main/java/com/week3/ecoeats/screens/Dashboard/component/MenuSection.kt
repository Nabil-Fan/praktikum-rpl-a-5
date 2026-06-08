package com.week3.ecoeats.screens.Dashboard.component


import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import coil.compose.AsyncImage
import com.week3.ecoeats.data.FoodListing
import com.week3.ecoeats.ui.theme.CharcoalBrown
import com.week3.ecoeats.ui.theme.DarkOliveGreen

@Composable
fun MenuSection(
    menuList: List<FoodListing>
) {
    Text(
        text = "MENU TERSEDIA",
        fontWeight = FontWeight.Bold,
        color = CharcoalBrown
    )
    Spacer(modifier = Modifier.height(8.dp))
    Column(
        verticalArrangement = Arrangement.spacedBy(12.dp)
    ) {
        menuList.forEach { food ->
            MenuCard(food = food)
        }
    }
}

@Composable
fun MenuCard(food : FoodListing) {
    Row(
        modifier = Modifier.fillMaxWidth(),
        verticalAlignment = Alignment.CenterVertically
    ) {
        AsyncImage(
            model = food.photoUrl,
            contentDescription = food.name,
            contentScale = ContentScale.Crop,
            modifier = Modifier
                .size(80.dp)
                .clip(RoundedCornerShape(12.dp))
        )
        Spacer(modifier = Modifier.width(12.dp))
        Column {
            Text(
                text = food.name,
                fontWeight = FontWeight.Bold,
                fontSize = 14.sp,
                color = CharcoalBrown
            )
            Text(
                text = food.restoran,
                fontSize = 12.sp,
                color = CharcoalBrown.copy(alpha = 0.6f)
            )
            Text(
                text = "Rp.${food.harga}",
                fontSize = 12.sp,
                color = DarkOliveGreen,
                fontWeight = FontWeight.SemiBold
            )
        }
    }
}