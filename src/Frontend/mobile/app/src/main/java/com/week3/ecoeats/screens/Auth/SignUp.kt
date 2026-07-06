package com.week3.ecoeats.screens.Auth

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import androidx.navigation.NavController
import com.week3.ecoeats.screens.components.MainImage
import com.week3.ecoeats.ui.theme.Cornsilk
import com.week3.ecoeats.ui.theme.DarkOliveGreen
import com.week3.ecoeats.ui.theme.white
import com.week3.ecoeats.viewmodel.AuthUiState
import com.week3.ecoeats.viewmodel.AuthViewModel
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll

@Composable
fun SignUp(
    navController: NavController,
    modifier: Modifier = Modifier,
    authViewModel: AuthViewModel = viewModel()
) {
    var name by remember { mutableStateOf("") }
    var username by remember { mutableStateOf("") }
    var email by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var passwordConfirmation by remember { mutableStateOf("") }
    val uiState by authViewModel.uiState.collectAsState()

    LaunchedEffect(uiState) {
        if (uiState is AuthUiState.Success) {
            navController.navigate("SignIn") {
                popUpTo("signup") { inclusive = true }
            }
            authViewModel.resetState()
        }
    }

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(Cornsilk)
    ) {
        MainImage()
        BottomLayer(
            name = name,
            onNameChange = { name = it },
            username = username,
            onUsernameChange = { username = it },
            email = email,
            onEmailChange = { email = it },
            password = password,
            onPasswordChange = { password = it },
            passwordConfirmation = passwordConfirmation,
            onPasswordConfirmationChange = { passwordConfirmation = it },
            uiState = uiState,
            onSubmit = {
                authViewModel.register(
                    name = name,
                    email = email,
                    username = username,
                    phone = null,
                    password = password,
                    passwordConfirmation = passwordConfirmation
                )
            },
            navController = navController,
            modifier = Modifier
                .fillMaxHeight(0.62f)
                .align(Alignment.BottomCenter)
        )
    }
}

@Composable
fun headline() {
    Text(
        text = "DAFTAR",
        color = DarkOliveGreen,
        fontSize = 22.sp,
        fontWeight = FontWeight.Bold,
        textAlign = TextAlign.Center,
        lineHeight = 32.sp
    )
}

@Composable
fun FieldName(value: String, onValueChange: (String) -> Unit) {
    OutlinedTextField(
        value = value,
        onValueChange = onValueChange,
        placeholder = { Text("Nama lengkap") },
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(50),
        singleLine = true
    )
}

@Composable
fun FieldUsername(value: String, onValueChange: (String) -> Unit) {
    OutlinedTextField(
        value = value,
        onValueChange = onValueChange,
        placeholder = { Text("Username") },
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(50),
        singleLine = true
    )
}

@Composable
fun FieldEmail(value: String, onValueChange: (String) -> Unit) {
    OutlinedTextField(
        value = value,
        onValueChange = onValueChange,
        placeholder = { Text("Email") },
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(50),
        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Email),
        singleLine = true
    )
}

@Composable
fun FieldPassword(value: String, onValueChange: (String) -> Unit) {
    OutlinedTextField(
        value = value,
        onValueChange = onValueChange,
        placeholder = { Text("Password") },
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(50),
        visualTransformation = PasswordVisualTransformation(),
        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Password),
        singleLine = true
    )
}

@Composable
fun FieldPasswordConfirmation(value: String, onValueChange: (String) -> Unit) {
    OutlinedTextField(
        value = value,
        onValueChange = onValueChange,
        placeholder = { Text("Konfirmasi password") },
        modifier = Modifier.fillMaxWidth(),
        shape = RoundedCornerShape(50),
        visualTransformation = PasswordVisualTransformation(),
        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Password),
        singleLine = true
    )
}

@Composable
fun BottomLayer(
    name: String,
    onNameChange: (String) -> Unit,
    username: String,
    onUsernameChange: (String) -> Unit,
    email: String,
    onEmailChange: (String) -> Unit,
    password: String,
    onPasswordChange: (String) -> Unit,
    passwordConfirmation: String,
    onPasswordConfirmationChange: (String) -> Unit,
    uiState: AuthUiState,
    onSubmit: () -> Unit,
    navController: NavController,
    modifier: Modifier = Modifier
) {
    Column(
        modifier = modifier
            .fillMaxWidth()
            .verticalScroll(rememberScrollState())
            .clip(RoundedCornerShape(topStart = 32.dp, topEnd = 32.dp))
            .background(Cornsilk)
            .padding(horizontal = 32.dp, vertical = 16.dp),
        verticalArrangement = Arrangement.spacedBy(20.dp),
        horizontalAlignment = Alignment.CenterHorizontally
    ) {
        Spacer(modifier = Modifier.height(8.dp))
        headline()

        Column(modifier = Modifier.fillMaxWidth()) {
            FieldName(value = name, onValueChange = onNameChange)
            Spacer(modifier = Modifier.height(16.dp))
            FieldUsername(value = username, onValueChange = onUsernameChange)
            Spacer(modifier = Modifier.height(16.dp))
            FieldEmail(value = email, onValueChange = onEmailChange)
            Spacer(modifier = Modifier.height(16.dp))
            FieldPassword(value = password, onValueChange = onPasswordChange)
            Spacer(modifier = Modifier.height(16.dp))
            FieldPasswordConfirmation(
                value = passwordConfirmation,
                onValueChange = onPasswordConfirmationChange
            )

            if (uiState is AuthUiState.Failure) {
                Spacer(modifier = Modifier.height(12.dp))
                Text(text = uiState.message, color = Color.Red, fontSize = 13.sp)
            }

            Spacer(modifier = Modifier.height(28.dp))

            Button(
                onClick = onSubmit,
                enabled = uiState !is AuthUiState.Loading,
                modifier = Modifier
                    .fillMaxWidth()
                    .height(56.dp),
                shape = MaterialTheme.shapes.large,
                colors = ButtonDefaults.buttonColors(
                    containerColor = DarkOliveGreen,
                    contentColor = white
                )
            ) {
                if (uiState is AuthUiState.Loading) {
                    CircularProgressIndicator(modifier = Modifier.height(20.dp), color = white)
                } else {
                    Text(text = "Daftar")
                }
            }
        }
    }
}