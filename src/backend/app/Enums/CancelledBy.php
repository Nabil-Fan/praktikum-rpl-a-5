<?php

namespace App\Enums;

enum CancelledBy: string
{
    case USER     = 'user';
    case MERCHANT = 'merchant';
    case SYSTEM   = 'system';
}
