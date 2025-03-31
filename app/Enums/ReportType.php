<?php

namespace App\Enums;

enum ReportType: string
{
    case RESERVATION_SUMMARY = 'reservation summary';
    case INCOMING_RESERVATIONS = 'incoming reservations';
    case OCCUPANCY_REPORT = 'occupancy report';
    case REVENUE_PERFORMANCE = 'revenue performance';
}
