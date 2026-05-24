package com.week3.ecoeats.ui.theme

import android.os.Build
import androidx.compose.foundation.isSystemInDarkTheme
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.MaterialTheme.colorScheme
import androidx.compose.material3.Shapes
import androidx.compose.material3.MaterialTheme.shapes
import androidx.compose.material3.VerticalDragHandleDefaults.shapes
import androidx.compose.material3.darkColorScheme
import androidx.compose.material3.dynamicDarkColorScheme
import androidx.compose.material3.dynamicLightColorScheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.Font
import androidx.compose.ui.text.font.FontFamily
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp


private val LightColorScheme = lightColorScheme(
    primary = DarkOliveGreen,
    secondary = LaurelGreen,
    background = Cornsilk,
    surface = Cornsilk,
    onPrimary = Cornsilk,
    onBackground = CharcoalBrown,
    onSurface = CharcoalBrown,
    surfaceContainer = white

    /* Other default colors to override
    background = Color(0xFFFFFBFE),
    surface = Color(0xFFFFFBFE),
    onPrimary = Color.White,
    onSecondary = Color.White,
    onTertiary = Color.White,
    onBackground = Color(0xFF1C1B1F),
    onSurface = Color(0xFF1C1B1F),
    */
)
private val EcoEatsShapes = Shapes(
    small = RoundedCornerShape(size = 30.dp),
    medium = RoundedCornerShape(size = 40.dp),
    large = RoundedCornerShape(size = 90.dp),
)

//private val poppinsFont = GoogleFont("Poppins")
//val PoppinsFamily = FontFamily(
//    Font(
//        googleFont = poppinsFont
//        fontProvider = googleFontProvider,
//        weight = FontWeight.Normal
//    ),
//    Font(
//        googleFont = poppinsFont
//        fontProvider = googleFontProvider,
//        weight = FontWeight.Bold
//    )
//)

//private val dmSansFont = GoogleFont("DM Sans")
//val DMSansFamily = FontFamily(
//    Font(
//        googleFont = dmSansFont,
//        fontProvider = googleFontProvider,
//        weight = FontWeight.Normal),
//    Font(
//        googleFont = dmSansFont,
//        fontProvider = googleFontProvider,
//        weight = FontWeight.Medium),
//)
//
//private val EcoEatsTypography = Typography(
//    headlineLarge = TextStyle(
//        fontFamily = PoppinsFamily,
//        fontWeight = FontWeight.Bold,
//        fontSize = 24.sp,
//        lineHeight = 32.sp,
//    ),
//    bodyMedium = TextStyle(
//        fontFamily = DMSansFamily,
//        fontWeight = FontWeight.Normal,
//        fontSize = 16.sp,
//        lineHeight = 22.sp,
//    ),
//)

@Composable
fun EcoEatsTheme(
    darkTheme: Boolean = isSystemInDarkTheme(),
    content: @Composable () -> Unit
) {
//    val colorScheme = if (darkTheme) DarkColorScheme else LightColorScheme

    MaterialTheme(
        colorScheme = colorScheme,
        //typography = EcoEatsTypography,
        shapes = EcoEatsShapes,
        content = content
    )
}