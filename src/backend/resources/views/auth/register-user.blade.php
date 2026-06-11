{{--
    resources/views/auth/register-user.blade.php
    Dipanggil dari: RegisterController@showUserRegister
--}}
@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('badge-bg',     'rgba(95,111,82,0.1)')
@section('badge-color',  '#5F6F52')
@section('badge-border', 'rgba(95,111,82,0.25)')
@section('active-user',  'active')

@section('content')
<div class="card-header">
    <div class="portal-badge">
        <span>🌿</span> User Baru
    </div>
    <h2 class="card-title">Buat akun gratis</h2>
    <p class="card-subtitle">Temukan makanan surplus terbaik di Solo.</p>
</div>

<form method="POST" action="{{ route('register.post') }}">
    @csrf

    <div class="form-group">
        <label class="form-label" for="name">Nama Lengkap</label>
        <input class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               type="text" id="name" name="name"
               value="{{ old('name') }}" autocomplete="name" autofocus
               placeholder="contoh: Nabil Fannani">
        @error('name')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
               type="email" id="email" name="email"
               value="{{ old('email') }}" autocomplete="email"
               placeholder="nabil@email.com">
        @error('email')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="phone">
            No. HP <span style="font-weight:400; color:var(--text-muted)">(opsional)</span>
        </label>
        <input class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
               type="tel" id="phone" name="phone"
               value="{{ old('phone') }}"
               placeholder="08xxxxxxxxxx">
        @error('phone')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
               type="password" id="password" name="password"
               autocomplete="new-password"
               placeholder="Minimal 8 karakter">
        @error('password')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="margin-bottom:1.5rem">
        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
        <input class="form-input"
               type="password" id="password_confirmation" name="password_confirmation"
               autocomplete="new-password"
               placeholder="Ulangi password">
    </div>

    <button type="submit" class="btn-submit">Buat Akun</button>
</form>

<div class="portal-switcher" style="margin-top:1.25rem; padding-top:1.25rem">
    <p style="font-size:.82rem; color:var(--text-muted)">
        Sudah punya akun?
        <a href="{{ route('login') }}" style="color:var(--accent); font-weight:600; text-decoration:none">
            Masuk di sini
        </a>
    </p>
</div>
@endsection