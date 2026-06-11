<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use App\Models\MerchantVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Form tambah akun manual.
     * Admin bisa buat akun user, merchant, atau admin baru dari panel.
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Simpan akun baru yang dibuat manual oleh admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', 'in:user,merchant,admin'],
            'password' => ['required', 'string', 'min:8'],
            // Field tambahan jika role merchant
            'business_name'    => ['required_if:role,merchant', 'nullable', 'string', 'max:150'],
            'business_address' => ['required_if:role,merchant', 'nullable', 'string'],
            'latitude'         => ['required_if:role,merchant', 'nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['required_if:role,merchant', 'nullable', 'numeric', 'between:-180,180'],
        ], [
            'email.unique'              => 'Email ini sudah terdaftar.',
            'business_name.required_if' => 'Nama usaha wajib diisi untuk akun merchant.',
            'business_address.required_if' => 'Alamat usaha wajib diisi untuk akun merchant.',
            'latitude.required_if'      => 'Koordinat latitude wajib diisi untuk akun merchant.',
            'longitude.required_if'     => 'Koordinat longitude wajib diisi untuk akun merchant.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? null,
                'password_hash' => Hash::make($validated['password']),
                'role'          => $validated['role'],
            ]);

            // Jika merchant, buat profil otomatis dengan status approved
            // (karena admin yang buat, dianggap sudah terverifikasi)
            if ($validated['role'] === 'merchant') {
                MerchantProfile::create([
                    'user_id'             => $user->id,
                    'business_name'       => $validated['business_name'],
                    'business_address'    => $validated['business_address'],
                    'latitude'            => $validated['latitude'],
                    'longitude'           => $validated['longitude'],
                    'verification_status' => 'approved',
                    'verified_at'         => now(),
                ]);
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', "Akun \"{$validated['name']}\" berhasil dibuat.");
    }

    /**
     * Reset status verifikasi merchant ke pending.
     * Digunakan jika merchant perlu mengajukan ulang dokumen.
     */
    public function resetVerification(MerchantProfile $merchant)
    {
        // Hanya bisa reset jika sudah approved atau rejected
        if ($merchant->verification_status === 'pending') {
            return back()->with('error', 'Status merchant sudah pending, tidak perlu direset.');
        }

        DB::transaction(function () use ($merchant) {
            $merchant->update([
                'verification_status' => 'pending',
                'verified_at'         => null,
            ]);

            // Catat di log verifikasi sebagai tindakan admin
            MerchantVerification::create([
                'merchant_id' => $merchant->id,
                'admin_id'    => auth()->id(),
                'action'      => 'rejected', // reset = tolak + ajukan ulang
                'notes'       => 'Verifikasi direset oleh admin. Merchant dapat mengajukan ulang.',
                'actioned_at' => now(),
            ]);
        });

        return back()->with('success', "Verifikasi \"{$merchant->business_name}\" berhasil direset ke pending.");
    }

    /**
     * Toggle soft delete — nonaktifkan atau aktifkan kembali akun.
     * Endpoint tunggal: jika aktif → nonaktifkan, jika nonaktif → aktifkan.
     */
    public function toggleActive(User $user)
    {
        // Cegah admin menonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        if ($user->deleted_at) {
            // Aktifkan kembali
            $user->update(['deleted_at' => null]);
            return back()->with('success', "Akun \"{$user->name}\" berhasil diaktifkan kembali.");
        } else {
            // Nonaktifkan (soft delete)
            $user->update(['deleted_at' => now()]);
            return back()->with('success', "Akun \"{$user->name}\" berhasil dinonaktifkan.");
        }
    }
}