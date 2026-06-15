<?php

namespace App\Http\Controllers\Api;

use App\Enums\FoodListingStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\FoodListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodListingController extends Controller
{
    /**
     * Ambil semua food listing yang aktif dan tersedia.
     *
     * Kriteria listing yang ditampilkan:
     * - Status harus 'available'
     * - Stok harus lebih dari 0
     * - Belum di-soft-delete (deleted_at IS NULL)
     * - Merchant pemilik listing sudah terverifikasi (approved)
     *
     * Mendukung filter opsional via query parameter:
     * - ?category_id=1  → filter berdasarkan kategori
     * - ?search=nasi    → cari berdasarkan nama listing atau nama merchant
     *
     * FR-06 | US-06 | AC-1, AC-2
     */
    public function index(Request $request): JsonResponse
    {
        $categoryId  = $request->query('category_id');
        $searchQuery = $request->query('search');

        $foodListings = FoodListing::query()
            ->with(['merchant', 'category'])
            ->where('status', FoodListingStatus::AVAILABLE)
            ->where('stock_qty', '>', 0)
            ->whereNull('deleted_at')
            ->whereHas('merchant', function ($merchantQuery) {
                $merchantQuery->where('verification_status', VerificationStatus::APPROVED);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($searchScope) use ($searchQuery) {
                    $searchScope->where('name', 'like', '%' . $searchQuery . '%')
                                ->orWhereHas('merchant', function ($merchantSearch) use ($searchQuery) {
                                    $merchantSearch->where('business_name', 'like', '%' . $searchQuery . '%');
                                });
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($foodListings->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada menu surplus tersedia saat ini.',
                'data'    => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Daftar menu surplus berhasil dimuat.',
            'data'    => $foodListings->map(fn ($foodListing) => $this->formatFoodListing($foodListing)),
        ], 200);
    }

    /**
     * Ambil detail satu food listing berdasarkan ID.
     *
     * Menampilkan listing yang belum dihapus, terlepas dari statusnya,
     * agar user yang sudah membuka halaman detail tetap bisa melihat
     * informasi meskipun stok baru saja habis.
     *
     * FR-06 | US-06
     */
    public function show(int $foodListingId): JsonResponse
    {
        $foodListing = FoodListing::with(['merchant', 'category'])
            ->where('id', $foodListingId)
            ->whereNull('deleted_at')
            ->first();

        if ($foodListing === null) {
            return response()->json([
                'message' => 'Menu surplus tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail menu surplus berhasil dimuat.',
            'data'    => $this->formatFoodListing($foodListing),
        ], 200);
    }

    /**
     * Format data food listing menjadi struktur JSON yang konsisten
     * untuk dikembalikan ke aplikasi mobile.
     *
     * Dipisahkan ke method sendiri agar tidak duplikat antara
     * index() dan show().
     */
    private function formatFoodListing(FoodListing $foodListing): array
    {
        return [
            'id'             => $foodListing->id,
            'name'           => $foodListing->name,
            'description'    => $foodListing->description,
            'original_price' => $foodListing->original_price,
            'discount_price' => $foodListing->discount_price,
            'stock_qty'      => $foodListing->stock_qty,
            'photo_url'      => $foodListing->photo_url,
            'pickup_start'   => $foodListing->pickup_start?->toIso8601String(),
            'pickup_end'     => $foodListing->pickup_end?->toIso8601String(),
            'status'         => $foodListing->status->value,
            'category'       => [
                'id'   => $foodListing->category->id,
                'name' => $foodListing->category->name,
            ],
            'merchant'       => [
                'id'               => $foodListing->merchant->id,
                'business_name'    => $foodListing->merchant->business_name,
                'business_address' => $foodListing->merchant->business_address,
                'latitude'         => $foodListing->merchant->latitude,
                'longitude'        => $foodListing->merchant->longitude,
            ],
        ];
    }
}
