@extends('layouts.auth')

@section('title', 'Login Merchant')
@section('active-merchant', 'active')

@section('content')
  <span class="role-pill">Portal Mitra Merchant</span>
  <h1>Portal Merchant</h1>
  <br>

  <form class="auth-form" method="POST" action="{{ route('merchant.login.post') }}">
    @csrf

    <label for="email">Email</label>
    <input
      type="email"
      id="email"
      name="email"
      class="@error('email') is-invalid @enderror"
      value="{{ old('email') }}"
      placeholder="username@gmail.com"
      required
      autofocus
      autocomplete="email"
    >
    @error('email')
      <p class="field-error">{{ $message }}</p>
    @enderror

    <label for="password">Password</label>
    <input
      type="password"
      id="password"
      name="password"
      class="@error('password') is-invalid @enderror"
      placeholder="••••••••"
      required
      autocomplete="current-password"
    >
    @error('password')
      <p class="field-error">{{ $message }}</p>
    @enderror

    <div class="check-wrap">
      <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
      <label for="remember" style="text-transform:none; letter-spacing:normal; font-size:13px; margin-bottom:0;">
        Ingat saya
      </label>
    </div>

    <button type="submit" class="auth-btn">Masuk</button>
  </form>

  <div class="info-box">
    <strong>Belum terdaftar sebagai mitra?</strong>
    <br>
    {{-- Ganti '#' dengan route('merchant.register') kalau route-nya sudah dibuat --}}
    <a href="#" style="color:inherit; font-weight:700; text-decoration:underline;">
      → Daftar sebagai Merchant
    </a>
  </div>
@endsection