package com.week3.ecoeats.screens.Navigations

import androidx.compose.runtime.Composable
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import com.week3.ecoeats.screens.Auth.AuthScreen
import com.week3.ecoeats.screens.Auth.SignIn
import com.week3.ecoeats.screens.Auth.SignUp
import com.week3.ecoeats.screens.Category.CategoryDetailScreen
import com.week3.ecoeats.screens.Checkout.CheckoutScreen
import com.week3.ecoeats.screens.Checkout.PaymentScreen
import com.week3.ecoeats.screens.Checkout.WaitingVerificationScreen
import com.week3.ecoeats.screens.Dashboard.DashboardScreen
import com.week3.ecoeats.screens.FoodDetail.FoodDetailScreen
import com.week3.ecoeats.screens.Maps.MapsScreen
import com.week3.ecoeats.screens.OrderHistory.OrderHistoryScreen
import com.week3.ecoeats.screens.Profile.EditProfileScreen
import com.week3.ecoeats.screens.Profile.ProfileScreen


@Composable
fun NavGraph() {
    val navController = rememberNavController()

    NavHost(
        navController = navController,
        startDestination = "auth"
    ) {
        composable("auth") {
            AuthScreen(navController = navController)
        }
        composable("signup") {
            SignUp(navController = navController)
        }
        composable("SignIn") {
            SignIn(navController = navController)
        }
        composable("dashboard") {
            DashboardScreen(navController)
        }
        composable("category/{categoryId}") { backStackEntry ->
            val categoryId = backStackEntry.arguments
                ?.getString("categoryId")
                ?.toInt() ?: 0
            CategoryDetailScreen(
                categoryId = categoryId,
                navController = navController
            )
        }
        // ── Detail menu ──
        composable("food-detail/{foodId}") { backStackEntry ->
            val foodId = backStackEntry.arguments
                ?.getString("foodId")
                ?.toInt() ?: 0
            FoodDetailScreen(
                foodId = foodId,
                navController = navController
            )
        }
        composable("maps/{foodId}") { backStackEntry ->
            val foodId = backStackEntry.arguments
                ?.getString("foodId")
                ?.toInt() ?: 0
            MapsScreen(
                foodId = foodId,
                navController = navController
            )
        }
        // ── Checkout flow: Place Order -> menunggu verifikasi -> checkout -> payment ──
        composable("waiting-verification/{orderId}") { backStackEntry ->
            val orderId = backStackEntry.arguments
                ?.getString("orderId")
                ?.toInt() ?: 0
            WaitingVerificationScreen(
                orderId = orderId,
                navController = navController
            )
        }
        composable("checkout/{orderId}") { backStackEntry ->
            val orderId = backStackEntry.arguments
                ?.getString("orderId")
                ?.toInt() ?: 0
            CheckoutScreen(
                orderId = orderId,
                navController = navController
            )
        }
        composable("payment/{orderId}") { backStackEntry ->
            val orderId = backStackEntry.arguments
                ?.getString("orderId")
                ?.toInt() ?: 0
            PaymentScreen(
                orderId = orderId,
                navController = navController
            )
        }
        // ── Riwayat Pesanan ──
        composable("order-history") {
            OrderHistoryScreen(navController = navController)
        }
        // ── Profile ──
        composable("profile") {
            ProfileScreen(navController = navController)
        }
        composable("edit-profile") {
            EditProfileScreen(navController = navController)
        }
    }
}