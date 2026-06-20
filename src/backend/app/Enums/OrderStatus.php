<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case CONFIRMED = 'confirmed';
    case READY     = 'ready';
    case COMPLETED = 'completed';
    case REJECTED  = 'rejected';
    case EXPIRED   = 'expired';
}
