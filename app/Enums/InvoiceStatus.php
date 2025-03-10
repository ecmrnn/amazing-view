<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case PARTIAL = 0;
    case PAID = 1;
    case PENDING = 2;
    case DUE = 3;
    case CANCELED = 4;
    case ISSUED = 5;
}
