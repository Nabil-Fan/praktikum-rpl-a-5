<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Ambil semua kategori yang tersedia.
     *
     * Digunakan di halaman Dashboard untuk menampilkan
     * filter kategori (Semua, Nasi, Roti, Mie, dst).
     *
     * FR-06 | US-06
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('name', 'asc')->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada kategori tersedia.',
                'data'    => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Daftar kategori berhasil dimuat.',
            'data'    => $categories->map(function ($category) {
                return $this->formatCategory($category);
            }),
        ], 200);
    }

    /**
     * Format data kategori menjadi struktur JSON
     * yang konsisten untuk dikembalikan ke aplikasi mobile.
     */
    private function formatCategory(Category $category): array
    {
        return [
            'id'   => $category->id,
            'name' => $category->name,
        ];
    }
}
