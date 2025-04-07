<?php

namespace App\Enums;

enum PromoStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case EXPIRED = 3;
    case PENDING = 4;
}
