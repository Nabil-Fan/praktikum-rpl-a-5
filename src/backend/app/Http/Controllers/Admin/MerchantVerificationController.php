<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\MerchantVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MerchantVerificationController extends Controller
{
    /**
     * Daftar semua merchant, bisa difilter per status.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = MerchantProfile::with('user')->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        $merchants = $query->paginate(15)->withQueryString();

        $counts = [
            'all'      => MerchantProfile::count(),
            'pending'  => MerchantProfile::where('verification_status', 'pending')->count(),
            'approved' => MerchantProfile::where('verification_status', 'approved')->count(),
            'rejected' => MerchantProfile::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.merchant-verification.index', compact('merchants', 'status', 'counts'));
    }

    /**
     * Detail merchant — dokumen, info usaha, riwayat verifikasi.
     */
    public function show(MerchantProfile $merchant)
    {
        $merchant->load(['user', 'verifications.admin']);
        return view('admin.merchant-verification.show', compact('merchant'));
    }

    /**
     * Approve merchant.
     */
    public function approve(Request $request, MerchantProfile $merchant)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $merchant) {
            $merchant->update([
                'verification_status' => 'approved',
                'verified_at'         => now(),
            ]);

            MerchantVerification::create([
                'merchant_id' => $merchant->id,
                'admin_id'    => Auth::id(),
                'action'      => 'approved',
                'notes'       => $request->notes,
                'actioned_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', "Merchant \"{$merchant->business_name}\" berhasil disetujui.");
    }

    /**
     * Reject merchant — notes wajib diisi.
     */
    public function reject(Request $request, MerchantProfile $merchant)
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $merchant) {
            $merchant->update([
                'verification_status' => 'rejected',
                'verified_at'         => null,
            ]);

            MerchantVerification::create([
                'merchant_id' => $merchant->id,
                'admin_id'    => Auth::id(),
                'action'      => 'rejected',
                'notes'       => $request->notes,
                'actioned_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', "Merchant \"{$merchant->business_name}\" ditolak.");
    }
}