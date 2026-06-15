<?php

namespace App\Enums;

enum UserRole: string
{
    case USER     = 'user';
    case MERCHANT = 'merchant';
    case ADMIN    = 'admin';
}
