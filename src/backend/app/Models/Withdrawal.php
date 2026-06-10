<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    // Tabel pakai requested_at sebagai created_at
    const CREATED_AT = 'requested_at';
    const UPDATED_AT = null; // tidak ada updated_at di tabel

    protected $fillable = [
        'merchant_id',
        'amount',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'admin_id',
        'admin_notes',
        'transfer_proof_url',
        'requested_at',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ── Status constants ───────────────────────────────────────────────
    const STATUS_PENDING    = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_REJECTED   = 'rejected';

    // ── Relationships ──────────────────────────────────────────────────

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function isPending(): bool    { return $this->status === self::STATUS_PENDING; }
    public function isProcessing(): bool { return $this->status === self::STATUS_PROCESSING; }
    public function isCompleted(): bool  { return $this->status === self::STATUS_COMPLETED; }
    public function isRejected(): bool   { return $this->status === self::STATUS_REJECTED; }

    public function statusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING    => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED  => 'Selesai',
            self::STATUS_REJECTED   => 'Ditolak',
            default                 => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING    => 'b-pending',
            self::STATUS_PROCESSING => 'b-processing',
            self::STATUS_COMPLETED  => 'b-completed',
            self::STATUS_REJECTED   => 'b-rejected',
            default                 => 'b-pending',
        };
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForMerchant($query, int $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}