@extends('layouts.auth')

@section('title', 'Login User')

@section('badge-bg', 'rgba(95,111,82,0.1)')
@section('badge-color', '#5F6F52')
@section('badge-border', 'rgba(95,111,82,0.25)')

@section('active-user', 'active')

@section('content')
    <div class="card-header">
        <span class="portal-badge">
            👤 Portal User
        </span>
        <h1 class="card-title">Selamat datang kembali</h1>
        <p class="card-subtitle">Masuk untuk menemukan makanan surplus di sekitarmu.</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="kamu@email.com"
                required
                autofocus
                autocomplete="email"
            >
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input @error('password') is-invalid @enderror"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            >
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-footer-row">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>
            {{-- <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a> --}}
        </div>

        <button type="submit" class="btn-submit">
            Masuk
        </button>
    </form>
@endsection