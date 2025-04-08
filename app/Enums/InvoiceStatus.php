<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case PARTIAL = 1;
    case PAID = 2;
    case PENDING = 3;
    case DUE = 4;
    case CANCELED = 5;
    case ISSUED = 6;
    case RESCHEDULED = 7;
    case WAIVED = 8;
}
