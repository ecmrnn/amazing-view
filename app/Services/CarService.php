<?php

namespace App\Services;

use App\Models\Reservation;

class CarService
{
    public function create(Reservation $reservation, $cars) {
        foreach ($cars as $car) {
            $reservation->cars()->create([
                'plate_number' => $car['plate_number'], 
                'make' => $car['make'],
                'model' => $car['model'],
                'color' => $car['color'],
            ]);
        }
    }

    public function update(Reservation $reservation, $cars) {
        foreach ($reservation->cars as $car) {
            $car->delete();
        }
        if (!empty($cars)) {
            foreach ($cars as $car) {
                $reservation->cars()->create([
                    'plate_number' => $car['plate_number'], 
                    'make' => $car['make'],
                    'model' => $car['model'],
                    'color' => $car['color'],
                ]);
            }
        }
    }

    public function add($cars, $car) {
        if (!$cars->contains('plate_number', strtoupper($car['plate_number']))) {
            $cars->push(collect([
                'plate_number' => strtoupper($car['plate_number']),
                'make' => ucwords(strtolower($car['make'])),
                'model' => ucwords(strtolower($car['model'])),
                'color' => ucwords(strtolower($car['color'])),
            ]));

            return $cars;
        }
    }

    public function remove($cars, $plate_number) {
        $cars = $cars->reject(function ($_car) use ($plate_number) {
            return $_car['plate_number'] == $plate_number;
        });

        return $cars;
    }
}