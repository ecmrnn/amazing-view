<?php

namespace App\Enums;

enum ReservationStatus: int
{
    case CONFIRMED = 0;
    case PENDING = 1;
    case EXPIRED = 2;
    case CHECKED_IN = 3;
    case CHECKED_OUT = 4;
    case COMPLETED = 5;
    case CANCELED = 6;
    case RESERVED = 7;
    case AWAITING_PAYMENT = 8;
    case NO_SHOW = 9;
}
