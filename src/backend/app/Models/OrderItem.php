<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // Schema DB punya created_at dan updated_at (nullable)
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'food_listing_id',
        'listing_name',   // snapshot nama saat transaksi — JANGAN ambil dari relasi listing
        'quantity',
        'unit_price',     // snapshot harga saat transaksi — JANGAN ambil dari relasi listing
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'quantity'   => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke listing asli — HANYA untuk referensi (cek stok, dll).
     * Untuk tampilkan nama/harga di histori, pakai kolom snapshot
     * listing_name dan unit_price, BUKAN dari relasi ini.
     * Relasi ini nullable karena listing bisa di-soft-delete.
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(FoodListing::class, 'food_listing_id')
                    ->withTrashed(); // listing bisa sudah dihapus, tapi item tetap harus terbaca
    }
}