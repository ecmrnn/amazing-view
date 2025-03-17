<?php

namespace App\Enums;

enum UserRole: int
{
    case GUEST = 1;
    case RECEPTIONIST = 2;
    case ADMIN = 3;
    case ALL = 4;
}
