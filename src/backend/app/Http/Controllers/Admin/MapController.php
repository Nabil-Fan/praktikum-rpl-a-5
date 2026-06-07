<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;

class MapController extends Controller
{
    public function index()
    {
        // Hanya merchant approved yang punya koordinat valid
        $merchants = MerchantProfile::with(['user', 'foodListings' => function ($q) {
                $q->whereNull('deleted_at')
                  ->where('status', 'available')
                  ->where('stock_qty', '>', 0);
            }])
            ->where('verification_status', 'approved')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn($m) => [
                'id'             => $m->id,
                'business_name'  => $m->business_name,
                'business_address'=> $m->business_address,
                'latitude'       => (float) $m->latitude,
                'longitude'      => (float) $m->longitude,
                'owner'          => $m->user?->name ?? '—',
                'active_listings'=> $m->foodListings->count(),
            ]);

        return view('admin.map.index', compact('merchants'));
    }
}