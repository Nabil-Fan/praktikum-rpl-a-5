<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use App\Models\Category;
use Illuminate\Http\Request;

class FoodListingController extends Controller
{
    /**
     * Daftar semua listing makanan di seluruh merchant.
     * Admin hanya bisa lihat & force-hapus (soft delete), tidak bisa edit konten.
     */
    public function index(Request $request)
    {
        $search   = $request->query('search');
        $status   = $request->query('status', 'all');
        $category = $request->query('category');

        $query = FoodListing::with(['merchant.user', 'category'])
            ->whereNull('food_listings.deleted_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('food_listings.name', 'like', "%{$search}%")
                  ->orWhereHas('merchant', fn($m) => $m->where('business_name', 'like', "%{$search}%"));
            });
        }

        $listings   = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.food-listings.index', compact('listings', 'categories', 'search', 'status', 'category'));
    }

    /**
     * Force soft-delete listing dari sisi admin (misal: konten melanggar).
     */
    public function destroy(FoodListing $foodListing)
    {
        $foodListing->update(['deleted_at' => now(), 'status' => 'unavailable']);

        return back()->with('success', "Listing \"{$foodListing->name}\" berhasil dihapus.");
    }
}