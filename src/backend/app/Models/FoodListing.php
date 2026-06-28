<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodListing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'category_id',
        'name',
        'description',
        'original_price',
        'discount_price',
        'stock_qty',
        'photo_url',
        'pickup_start',
        'pickup_end',
        'status',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'pickup_start'   => 'datetime',
        'pickup_end'     => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'deleted_at'     => 'datetime',
        'status'         => \App\Enums\FoodListingStatus::class,
    ];

    // ── Relationships ──────────────────────────────────────────

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Persentase diskon yang dihitung dari original vs discount price.
     */
    public function discountPercent(): int
    {
        if ($this->original_price <= 0) return 0;
        return (int) round((1 - $this->discount_price / $this->original_price) * 100);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock_qty > 0;
    }

    // ── Scopes ────────────────────────────────────────────────

    /**
     * Hanya listing yang aktif (tidak soft-deleted, status available, stok > 0).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at')
                     ->where('status', 'available')
                     ->where('stock_qty', '>', 0);
    }
}