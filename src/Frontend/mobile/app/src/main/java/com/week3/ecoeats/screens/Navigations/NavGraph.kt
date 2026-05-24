package com.week3.ecoeats.screens.Navigations

import androidx.compose.runtime.Composable
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import com.week3.ecoeats.screens.Auth.AuthScreen
import com.week3.ecoeats.screens.Auth.SignIn
import com.week3.ecoeats.screens.Auth.SignUp

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
        composable("SignIn"){
            SignIn(navController = navController)
        }
    }
}