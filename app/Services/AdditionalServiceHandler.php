<?php

namespace App\Services;

use App\Models\AdditionalServices;
use App\Models\Reservation;

class AdditionalServiceHandler
{
    public function add($services, AdditionalServices $service)
    {
        if ($services->contains('id', $service->id)) {
            // If: the amenity is already selected, remove it from the 'services'
            $services = $services->reject(function ($s) use ($service) {
                return $s->id == $service->id;
            });
        } else {
            // Else: push it to the 'services'
            $services->push($service);
        } 
        
        return $services;
    }
}