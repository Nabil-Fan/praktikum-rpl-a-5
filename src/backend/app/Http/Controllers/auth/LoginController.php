<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login user biasa.
     */
    public function showUserLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login-user');
    }

    /**
     * Tampilkan halaman login merchant.
     */
    public function showMerchantLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login-merchant');
    }

    /**
     * Tampilkan halaman login admin.
     */
    public function showAdminLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login-admin');
    }

    /**
     * Proses login user biasa.
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        return $this->attemptLogin($request, $credentials, 'user', route('user.dashboard'));
    }

    /**
     * Proses login merchant.
     */
    public function loginMerchant(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        return $this->attemptLogin($request, $credentials, 'merchant', route('merchant.dashboard'));
    }

    /**
     * Proses login admin.
     */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        return $this->attemptLogin($request, $credentials, 'admin', route('admin.dashboard'));

    }

    /**
     * Shared logic: attempt login, validasi role, redirect.
     */
    private function attemptLogin(Request $request, array $credentials, string $expectedRole, string $intendedRoute)
    {
        // Cek apakah email ada dan belum di-soft-delete
        $user = \App\Models\User::where('email', $credentials['email'])
            ->whereNull('deleted_at')
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Validasi role sesuai portal
        if ($user->role->value !== $expectedRole) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak terdaftar sebagai ' . $this->roleLabel($expectedRole) . '.',
            ]);
        }

        // Attempt login dengan kredensial
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($intendedRoute);
    }

    /**
     * Logout semua role.
     */
    public function logout(Request $request)
    {
        $role = Auth::user()?->role ?? 'user';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return match ($role) {
            'merchant' => redirect()->route('merchant.login'),
            'admin'    => redirect()->route('admin.login'),  // = 'admin.' + 'login'
            default    => redirect()->route('login'),
        };
    }

    /**
     * Redirect berdasarkan role setelah login.
     */
    private function redirectByRole(string $role): \Illuminate\Http\RedirectResponse
    {
        return match ($role) {
            'merchant' => redirect()->route('merchant.dashboard'),
            'admin'    => redirect()->route('admin.dashboard'),
            default    => redirect()->route('user.dashboard'),
        };
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            'merchant' => 'Mitra Merchant',
            'admin'    => 'Admin',
            default    => 'User',
        };
    }

    
}