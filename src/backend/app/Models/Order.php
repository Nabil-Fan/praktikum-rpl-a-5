<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    // Tabel tidak punya created_at standar — pakai ordered_at
    // updated_at ada di DB, tapi created_at tidak → matikan auto-timestamp
    const CREATED_AT = 'ordered_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'merchant_id',
        'pickup_code',
        'status',
        'total_amount',
        'payment_method',
        'ordered_at',
        'expires_at',
        'confirmed_at',
        'completed_at',
        'rejected_at',
        'expired_at',
    ];

    protected $casts = [
        'total_amount'  => 'decimal:2',
        'ordered_at'    => 'datetime',
        'expires_at'    => 'datetime',
        'confirmed_at'  => 'datetime',
        'completed_at'  => 'datetime',
        'rejected_at'   => 'datetime',
        'expired_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ── Status constants ───────────────────────────────────────────────
    // Pakai konstanta supaya tidak ada typo di controller/view
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_READY     = 'ready';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_EXPIRED   = 'expired';

    
    // ── Auto-generate pickup_code saat order dibuat ────────────────────
    protected static function boot(): void
    {
        parent::boot();
 
        static::creating(function (Order $order) {
            if (empty($order->pickup_code)) {
                $order->pickup_code = static::generatePickupCode();
            }
        });
    }
 
    /**
     * Generate kode pickup unik 8 karakter (huruf besar + angka).
     * Loop sampai dapat kode yang belum ada di DB.
     */
    public static function generatePickupCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa 0/O/1/I agar tidak membingungkan
 
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('pickup_code', $code)->exists());
 
        return $code;
    }
 

    // ── Relationships ──────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items(): HasMany
    {
        return $this->orderItems();
    }

    public function payment(): HasOne
    {
        // Ambil payment terbaru (retry terakhir)
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Apakah pesanan masih bisa dikonfirmasi atau ditolak oleh merchant.
     */
    public function isActionable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Apakah merchant bisa update status ke ready.
     */
    public function canMarkReady(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Apakah pesanan bisa diselesaikan (user sudah pickup).
     */
    public function canComplete(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    /**
     * Label status dalam Bahasa Indonesia untuk tampilan.
     */
    public function statusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING   => 'Menunggu Konfirmasi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_READY     => 'Siap Diambil',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED  => 'Ditolak',
            self::STATUS_EXPIRED   => 'Kedaluwarsa',
            default                => ucfirst($this->status),
        };
    }

    /**
     * Warna badge CSS untuk tampilan status.
     * Return class string yang bisa dipakai di Blade.
     */
    public function statusColor(): string
    {
        return match($this->status) {
            self::STATUS_PENDING   => 'camel',
            self::STATUS_CONFIRMED => 'olive',
            self::STATUS_READY     => 'laurel',
            self::STATUS_COMPLETED => 'olive',
            self::STATUS_REJECTED  => 'red',
            self::STATUS_EXPIRED   => 'gray',
            default                => 'gray',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        // Pesanan yang masih "hidup" — belum selesai/ditolak/expired
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_READY,
        ]);
    }

    public function scopeForMerchant($query, int $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }
}