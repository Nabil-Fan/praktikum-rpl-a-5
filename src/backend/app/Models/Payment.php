<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'amount',
        'status',
        'payment_proof_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING  = 'pending';
    const STATUS_PAID     = 'paid';
    const STATUS_FAILED   = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_EXPIRED  = 'expired';

    // ── Relationships ──────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING  => 'Menunggu Pembayaran',
            self::STATUS_PAID     => 'Lunas',
            self::STATUS_FAILED   => 'Gagal',
            self::STATUS_REFUNDED => 'Dikembalikan',
            self::STATUS_EXPIRED  => 'Kedaluwarsa',
            default               => ucfirst($this->status),
        };
    }
}