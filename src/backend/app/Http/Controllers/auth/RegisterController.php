<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // ── User biasa ────────────────────────────────────────────

    public function showUserRegister()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.register-user');
    }

    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'email.unique'    => 'Email ini sudah terdaftar.',
            'password.min'    => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'password_hash' => Hash::make($validated['password']),
            'role'          => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard');
    }

    // ── Merchant ──────────────────────────────────────────────

    public function showMerchantRegister()
    {
        if (Auth::check()) {
            return redirect()->route('merchant.dashboard');
        }
        return view('auth.register-merchant');
    }

    public function registerMerchant(Request $request)
    {
        $validated = $request->validate([
            // Akun
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            // Profil usaha
            'business_name'         => ['required', 'string', 'max:150'],
            'business_address'      => ['required', 'string'],
            'latitude'              => ['required', 'numeric', 'between:-90,90'],
            'longitude'             => ['required', 'numeric', 'between:-180,180'],
            // Dokumen
            'business_license'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'halal_cert'            => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'email.unique'           => 'Email ini sudah terdaftar.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'latitude.between'       => 'Koordinat latitude tidak valid (-90 hingga 90).',
            'longitude.between'      => 'Koordinat longitude tidak valid (-180 hingga 180).',
        ]);

        // Upload dokumen jika ada
        $licenseUrl = null;
        $halalUrl   = null;

        if ($request->hasFile('business_license')) {
            $licenseUrl = $request->file('business_license')
                ->store('merchant-docs/licenses', 'public');
        }
        if ($request->hasFile('halal_cert')) {
            $halalUrl = $request->file('halal_cert')
                ->store('merchant-docs/halal', 'public');
        }

        // Buat user dengan role merchant
        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'password_hash' => Hash::make($validated['password']),
            'role'          => 'merchant',
        ]);

        // Buat profil merchant — status langsung pending, menunggu review admin
        MerchantProfile::create([
            'user_id'              => $user->id,
            'business_name'        => $validated['business_name'],
            'business_address'     => $validated['business_address'],
            'latitude'             => $validated['latitude'],
            'longitude'            => $validated['longitude'],
            'business_license_url' => $licenseUrl,
            'halal_cert_url'       => $halalUrl,
            'verification_status'  => 'pending',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('merchant.dashboard')
            ->with('success', 'Akun berhasil dibuat. Dokumen Anda sedang ditinjau oleh admin EcoEats.');
    }
}