<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoodListing;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoodListingController extends Controller
{
    /**
     * Pastikan merchant sudah approved sebelum CRUD listing.
     * Dipanggil di konstruktor lewat middleware closure.
     */
    public function __construct()
    {
        // Middleware approve-check dilakukan di route level via closure
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Ambil profil merchant milik user yang sedang login.
     * Abort 403 jika tidak ditemukan.
     */
    private function getMerchantProfile(): MerchantProfile
    {
        $profile = MerchantProfile::where('user_id', Auth::id())->first();

        abort_if(! $profile, 403, 'Profil merchant tidak ditemukan.');

        return $profile;
    }

    /**
     * Pastikan listing milik merchant yang login.
     */
    private function authorizeOwnership(FoodListing $listing, MerchantProfile $profile): void
    {
        abort_if($listing->merchant_id !== $profile->id, 403, 'Akses ditolak.');
    }

    // ── CRUD ──────────────────────────────────────────────────

    public function index()
    {
        $profile  = $this->getMerchantProfile();
        $listings = FoodListing::where('merchant_id', $profile->id)
            ->whereNull('deleted_at')
            ->with('category')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('merchant.food-listings.index', compact('listings', 'profile'));
    }

    public function create()
    {
        $profile = $this->getMerchantProfile();

        // Merchant harus approved untuk publish listing
        if (! $profile->isApproved()) {
            return redirect()->route('merchant.dashboard')
                ->with('error', 'Akun Anda belum diverifikasi. Tunggu persetujuan admin terlebih dahulu.');
        }

        $categories = Category::orderBy('name')->get();
        return view('merchant.food-listings.create', compact('categories', 'profile'));
    }

    public function store(Request $request)
    {
        $profile = $this->getMerchantProfile();

        if (! $profile->isApproved()) {
            abort(403, 'Akun belum diverifikasi.');
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['nullable', 'string', 'max:1000'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['required', 'numeric', 'min:0', 'lt:original_price'],
            'stock_qty'      => ['required', 'integer', 'min:1'],
            'pickup_start'   => ['required', 'date', 'after_or_equal:now'],
            'pickup_end'     => ['required', 'date', 'after:pickup_start'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'         => ['required', 'in:available,unavailable'],
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga asli.',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photoUrl = $request->file('photo')->store('food-listings', 'public');
        }

        FoodListing::create([
            'merchant_id'    => $profile->id,
            'category_id'    => $validated['category_id'],
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'original_price' => $validated['original_price'],
            'discount_price' => $validated['discount_price'],
            'stock_qty'      => $validated['stock_qty'],
            'pickup_start'   => $validated['pickup_start'],
            'pickup_end'     => $validated['pickup_end'],
            'photo_url'      => $photoUrl,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('merchant.listings.index')
            ->with('success', 'Menu surplus berhasil ditambahkan.');
    }

    public function edit(FoodListing $listing)
    {
        $profile = $this->getMerchantProfile();
        $this->authorizeOwnership($listing, $profile);

        $categories = Category::orderBy('name')->get();
        return view('merchant.food-listings.edit', compact('listing', 'categories', 'profile'));
    }

    public function update(Request $request, FoodListing $listing)
    {
        $profile = $this->getMerchantProfile();
        $this->authorizeOwnership($listing, $profile);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'category_id'    => ['required', 'exists:categories,id'],
            'description'    => ['nullable', 'string', 'max:1000'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['required', 'numeric', 'min:0', 'lt:original_price'],
            'stock_qty'      => ['required', 'integer', 'min:0'],
            'pickup_start'   => ['required', 'date'],
            'pickup_end'     => ['required', 'date', 'after:pickup_start'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'         => ['required', 'in:available,unavailable,sold_out'],
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga asli.',
        ]);

        $photoUrl = $listing->photo_url;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($photoUrl) {
                Storage::disk('public')->delete($photoUrl);
            }
            $photoUrl = $request->file('photo')->store('food-listings', 'public');
        }

        $listing->update([
            'category_id'    => $validated['category_id'],
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'original_price' => $validated['original_price'],
            'discount_price' => $validated['discount_price'],
            'stock_qty'      => $validated['stock_qty'],
            'pickup_start'   => $validated['pickup_start'],
            'pickup_end'     => $validated['pickup_end'],
            'photo_url'      => $photoUrl,
            'status'         => $validated['status'],
        ]);

        return redirect()->route('merchant.listings.index')
            ->with('success', 'Menu surplus berhasil diperbarui.');
    }

    /**
     * Soft delete listing.
     */
    public function destroy(FoodListing $listing)
    {
        $profile = $this->getMerchantProfile();
        $this->authorizeOwnership($listing, $profile);

        $listing->update([
            'deleted_at' => now(),
            'status'     => 'unavailable',
        ]);

        return redirect()->route('merchant.listings.index')
            ->with('success', "Menu \"{$listing->name}\" berhasil dihapus.");
    }
}