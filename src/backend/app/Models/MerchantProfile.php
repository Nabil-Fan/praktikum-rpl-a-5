<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MerchantProfile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_address',
        'latitude',
        'longitude',
        'business_license_url',
        'halal_cert_url',
        'verification_status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at'  => 'datetime',
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foodListings(): HasMany
    {
        return $this->hasMany(FoodListing::class, 'merchant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'merchant_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(MerchantVerification::class, 'merchant_id');
    }

    // ── Helpers ───────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->verification_status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
}