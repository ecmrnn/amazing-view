<?php

namespace App\Enums;

enum PageStatus: int
{
    case ACTIVE = 0;
    case MAINTENANCE = 1;
    case RESTRICTED = 2;
    case DISABLED = 3;
    case HIDDEN = 4;
}
