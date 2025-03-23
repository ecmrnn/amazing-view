<?php

namespace App\Enums;

enum RoomStatus: int
{
    case AVAILABLE = 1;
    case UNAVAILABLE = 2;
    case OCCUPIED = 3;
    case RESERVED = 4;
    case DISABLED = 5;
}
