<?php

namespace App\Enums;

enum ReservationStatus: int
{
    case CONFIRMED = 1;
    case PENDING = 2;
    case EXPIRED = 3;
    case CHECKED_IN = 4;
    case CHECKED_OUT = 5;
    case COMPLETED = 6;
    case CANCELED = 7;
    case RESERVED = 8;
    case AWAITING_PAYMENT = 9;
    case NO_SHOW = 10;
    case RESCHEDULED = 11;
}
