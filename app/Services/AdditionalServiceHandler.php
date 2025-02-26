<?php

namespace App\Services;

use App\Models\AdditionalServices;
use App\Models\Reservation;

class AdditionalServiceHandler
{
    // For attaching selected services on the reservation, to be stored on additional_service_reservations pivot table
    // Accepts the following arguments:
    // - Reservation instance
    // - Services to attach
    public function attach(Reservation $reservation, $services) {
        foreach ($services as $service) {
            $reservation->services()->attach($service['id'], [
                'price' => $service['price'],
            ]);
        }
    }
    // For syncing additional services records on additonal_service_reservations pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of services to attach
    public function sync(Reservation $reservation, $services) {
        if ($reservation->services->count() > 0) {
            foreach ($reservation->services as $service) {
                $reservation->services()->detach($service->id);

                $reservation->invoice->balance -= $service->price;
                $reservation->invoice->total_amount -= $service->price;
                $reservation->save();
            }
        }
        if (!empty($services)) {
            foreach ($services as $service) {
                $reservation->services()->attach($service['id'], [
                    'price' => $service['price'],
                ]);

                $reservation->invoice->balance += $service['price'];
                $reservation->invoice->total_amount += $service['price'];
                $reservation->save();
            }
        }
    }

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