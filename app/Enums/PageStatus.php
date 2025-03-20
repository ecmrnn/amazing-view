<?php

namespace App\Enums;

enum PageStatus: int
{
    case ACTIVE = 1;
    case DISABLED = 2;
    case MAINTENANCE = 3;
}
