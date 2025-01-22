<?php

namespace App\Enums;

enum RoomStatus: int
{
    case AVAILABLE = 0;
    case UNAVAILABLE = 1;
    case OCCUPIED = 2;
    case RESERVED = 3;
}
