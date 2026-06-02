<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\MerchantProfile;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil profil merchant milik user ini
        $profile = MerchantProfile::where('user_id', $user->id)->first();

        $stats = [
            'active_listings' => 0,
            'pending_orders'  => 0,
            'completed_today' => 0,
        ];

        if ($profile) {
            $stats['active_listings'] = FoodListing::where('merchant_id', $profile->id)
                ->whereNull('deleted_at')
                ->where('status', 'available')
                ->count();

            // Pesanan akan ditambahkan nanti setelah Order model dibuat
            // $stats['pending_orders']  = Order::where('merchant_id', $profile->id)->where('status', 'pending')->count();
            // $stats['completed_today'] = Order::where('merchant_id', $profile->id)->where('status', 'completed')->whereDate('completed_at', today())->count();
        }

        // Listing terbaru milik merchant ini
        $recentListings = $profile
            ? FoodListing::where('merchant_id', $profile->id)
                ->whereNull('deleted_at')
                ->with('category')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
            : collect();

        return view('dashboard.merchant', compact('profile', 'stats', 'recentListings'));
    }
}