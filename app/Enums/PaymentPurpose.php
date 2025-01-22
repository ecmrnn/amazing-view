<?php

namespace App\Enums;

enum PaymentPurpose: int
{
    case DOWNPAYMENT = 1;
    case PARTIAL = 2;
}
