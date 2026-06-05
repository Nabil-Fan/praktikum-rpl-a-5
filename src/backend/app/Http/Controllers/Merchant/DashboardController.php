<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\MerchantProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
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

            $stats['pending_orders'] = Order::forMerchant($profile->id)
                ->where('status', Order::STATUS_PENDING)
                ->count();

            $stats['completed_today'] = Order::forMerchant($profile->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereDate('completed_at', today())
                ->count();
        }

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