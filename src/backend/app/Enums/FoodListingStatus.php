<?php

namespace App\Enums;

enum FoodListingStatus: string
{
    case AVAILABLE   = 'available';
    case UNAVAILABLE = 'unavailable';
    case SOLD_OUT    = 'sold_out';
}
