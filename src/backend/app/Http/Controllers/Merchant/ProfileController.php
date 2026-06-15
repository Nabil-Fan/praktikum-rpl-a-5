<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Ambil profil merchant milik user yang login.
     */
    private function getProfile(): MerchantProfile
    {
        $profile = MerchantProfile::where('user_id', Auth::id())->first();
        abort_if(! $profile, 404, 'Profil merchant tidak ditemukan.');
        return $profile;
    }

    /**
     * Halaman edit profil usaha.
     */
    public function edit()
    {
        $profile = $this->getProfile();
        return view('merchant.profile.edit', compact('profile'));
    }

    /**
     * Simpan perubahan profil usaha.
     * Jika dokumen diubah dan status sebelumnya approved/rejected,
     * status otomatis kembali ke pending untuk review ulang.
     */
    public function update(Request $request)
    {
        $profile = $this->getProfile();

        $validated = $request->validate([
            'business_name'    => ['required', 'string', 'max:150'],
            'business_address' => ['required', 'string'],
            'latitude'         => ['required', 'numeric', 'between:-90,90'],
            'longitude'        => ['required', 'numeric', 'between:-180,180'],
            'business_license' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'halal_cert'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'latitude.between'  => 'Koordinat latitude tidak valid (-90 hingga 90).',
            'longitude.between' => 'Koordinat longitude tidak valid (-180 hingga 180).',
        ]);

        $docChanged = false;

        // Upload ulang business license jika ada file baru
        $licenseUrl = $profile->business_license_url;
        if ($request->hasFile('business_license')) {
            if ($licenseUrl) {
                Storage::disk('public')->delete($licenseUrl);
            }
            $licenseUrl = $request->file('business_license')
                ->store('merchant-docs/licenses', 'public');
            $docChanged = true;
        }

        // Upload ulang halal cert jika ada file baru
        $halalUrl = $profile->halal_cert_url;
        if ($request->hasFile('halal_cert')) {
            if ($halalUrl) {
                Storage::disk('public')->delete($halalUrl);
            }
            $halalUrl = $request->file('halal_cert')
                ->store('merchant-docs/halal', 'public');
            $docChanged = true;
        }

        // Jika dokumen berubah, status kembali ke pending untuk review ulang
        $newStatus  = $profile->verification_status;
        $verifiedAt = $profile->verified_at;
        if ($docChanged && $profile->verification_status !== 'pending') {
            $newStatus  = 'pending';
            $verifiedAt = null;
        }

        $profile->update([
            'business_name'        => $validated['business_name'],
            'business_address'     => $validated['business_address'],
            'latitude'             => $validated['latitude'],
            'longitude'            => $validated['longitude'],
            'business_license_url' => $licenseUrl,
            'halal_cert_url'       => $halalUrl,
            'verification_status'  => $newStatus,
            'verified_at'          => $verifiedAt,
        ]);

        $msg = 'Profil usaha berhasil diperbarui.';
        if ($docChanged && $newStatus === 'pending') {
            $msg .= ' Dokumen baru Anda akan ditinjau ulang oleh admin.';
        }

        return redirect()->route('merchant.profile.edit')
            ->with('success', $msg);
    }
}