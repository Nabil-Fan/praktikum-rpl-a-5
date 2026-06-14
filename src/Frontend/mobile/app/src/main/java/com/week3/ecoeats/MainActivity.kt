package com.week3.ecoeats

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import com.week3.ecoeats.ui.theme.EcoEatsTheme
import com.week3.ecoeats.screens.Navigations.NavGraph

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            EcoEatsTheme {
               NavGraph()
                }
            }
        }
    }
