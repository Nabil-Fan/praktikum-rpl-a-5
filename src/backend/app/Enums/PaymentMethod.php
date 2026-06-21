<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case TRANSFER = 'transfer';
    case EWALLET  = 'ewallet';
    case CASH     = 'cash';
    case QRIS     = 'qris';
}
