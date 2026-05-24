@extends('layouts.auth')

@section('title', 'Login Admin')

@section('badge-bg', 'rgba(56,44,35,0.07)')
@section('badge-color', '#382C23')
@section('badge-border', 'rgba(56,44,35,0.18)')

@section('active-admin', 'active')

@section('content')
    <div class="card-header">
        <span class="portal-badge">
            Portal Admin
        </span>
        <h1 class="card-title">Admin Dashboard</h1>
        <p class="card-subtitle">Akses terbatas — hanya untuk tim internal EcoEats.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Admin</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="admin@ecoeats.id"
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

        <div class="form-footer-row" style="margin-bottom: 1.5rem;">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>
        </div>

        <button type="submit" class="btn-submit"
            style="background:#382C23; box-shadow: 0 4px 14px rgba(56,44,35,0.35);">
            Masuk ke Dashboard Admin
        </button>
    </form>
@endsection