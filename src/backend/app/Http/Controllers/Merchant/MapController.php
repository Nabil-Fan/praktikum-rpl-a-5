<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function index()
    {
        $profile = MerchantProfile::where('user_id', Auth::id())->first();

        abort_if(!$profile, 403, 'Profil merchant tidak ditemukan.');

        // Koordinat default Solo jika merchant belum isi koordinat
        $location = [
            'latitude'      => $profile->latitude  ? (float) $profile->latitude  : -7.5695,
            'longitude'     => $profile->longitude ? (float) $profile->longitude : 110.8270,
            'business_name' => $profile->business_name,
            'address'       => $profile->business_address,
            'has_coords'    => $profile->latitude && $profile->longitude,
        ];

        return view('merchant.map.index', compact('profile', 'location'));
    }
}