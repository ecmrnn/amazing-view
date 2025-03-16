<?php

namespace App\Enums;

enum PageStatus: int
{
    case ACTIVE = 0;
    case DISABLED = 1;
    case MAINTENANCE = 2;
}
