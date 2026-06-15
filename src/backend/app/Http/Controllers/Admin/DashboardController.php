<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\MerchantProfile;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary stats — semua query filter deleted_at IS NULL sesuai konvensi
        $stats = [
            'pending_verifications' => MerchantProfile::where('verification_status', 'pending')->count(),
            'active_merchants'      => MerchantProfile::where('verification_status', 'approved')->count(),
            'total_users'           => User::whereNull('deleted_at')->where('role', 'user')->count(),
            'active_listings'       => FoodListing::whereNull('deleted_at')
                                            ->where('status', 'available')
                                            ->count(),
        ];

        // Merchant pending terbaru untuk preview di dashboard
        $pendingMerchants = MerchantProfile::with('user')
            ->where('verification_status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Pengguna terbaru (role user)
        $recentUsers = User::whereNull('deleted_at')
            ->where('role', 'user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'pendingMerchants', 'recentUsers'));
    }
}