<?php

namespace App\Services;

use App\Models\Reservation;

class AdditionalServiceHandler
{
    public function add(Reservation $reservation, array $services)
    {
        dd($reservation, $services);
    }
}