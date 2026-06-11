@extends('layouts.auth')

@section('title', 'Login Admin')
@section('active-admin', 'active')

@section('content')
  <span class="role-pill admin">Portal Admin</span>
  <h1>Portal Admin</h1>
  <p class="subtitle">Akses terbatas — hanya untuk tim internal EcoEats.</p>

  <form class="auth-form" method="POST" action="{{ route('admin.login.post') }}">
    @csrf

    <label for="email">Email Admin</label>
    <input
      type="email"
      id="email"
      name="email"
      class="@error('email') is-invalid @enderror"
      value="{{ old('email') }}"
      placeholder="admin@ecoeats.id"
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

    <button type="submit" class="auth-btn admin">Masuk ke Dashboard Admin</button>
  </form>

  <div class="info-box admin-info">
    <strong>Akses terbatas</strong>
    Hanya akun administrator resmi yang dapat mengakses halaman ini.
  </div>
@endsection