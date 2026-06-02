<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function foodListings(): HasMany
    {
        return $this->hasMany(FoodListing::class);
    }
}