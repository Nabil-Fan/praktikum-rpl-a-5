<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage di routes: middleware('role:admin') atau middleware('role:merchant')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            // Redirect ke halaman login yang sesuai berdasarkan prefix URL
            if ($request->is('merchant/*') || $request->is('merchant')) {
                return redirect()->route('merchant.login');
            }
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;

        // Cek juga soft delete
        if ($user->deleted_at !== null) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
        }

        if (! in_array($userRole, $roles, true)) {
            // User sudah login tapi role salah — logout dan redirect ke portal yang benar
            return redirect()->route('login')->withErrors([
                'email' => 'Anda tidak memiliki akses ke halaman ini.',
            ]);
        }

        return $next($request);
    }
}