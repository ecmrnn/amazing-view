<?php

namespace App\Enums;

enum SessionStatus: int
{
    case ONLINE = 0;
    case OFFLINE = 1;
}
