<?php

namespace App\Enums;

enum SessionStatus: int
{
    case ONLINE = 1;
    case OFFLINE = 2;
}
