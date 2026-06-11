@extends('layouts.auth')

@section('title', 'Login Merchant')

@section('badge-bg', 'rgba(185,148,112,0.12)')
@section('badge-color', '#7a5c3a')
@section('badge-border', 'rgba(185,148,112,0.3)')

@section('active-merchant', 'active')

@section('content')
    <div class="card-header">
        <span class="portal-badge">
            Portal Mitra Merchant
        </span>
        <h1 class="card-title">Portal Mitra Merchant</h1>
        <p class="card-subtitle">Kelola makanan surplus dan pesanan dari pelanggan Anda.</p>
    </div>

    <form method="POST" action="{{ route('merchant.login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Bisnis</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="toko@emailbisnis.com"
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
        </div>

        <button type="submit" class="btn-submit"
            style="background:#B99470; box-shadow: 0 4px 14px rgba(185,148,112,0.4);">
            Masuk ke Portal Merchant
        </button>
    </form>

@endsection