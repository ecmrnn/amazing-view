<?php

namespace App\Livewire\App\Services;

use App\Enums\ReservationStatus;
use App\Models\AdditionalServiceReservation;
use Livewire\Component;

class ServicesCards extends Component
{
    public $popular_service;
    public $service_sales;

    public function render()
    {
        $this->popular_service = AdditionalServiceReservation::selectRaw('additional_services.name, COUNT(additional_services_id) as count')
            ->join('additional_services', 'additional_services.id', '=', 'additional_service_reservations.additional_services_id')
            ->groupBy('additional_services.name')
            ->orderByRaw('count desc')
            ->limit(1)
            ->first();

        $finalized_services = AdditionalServiceReservation::join('reservations', 'reservations.id', '=', 'additional_service_reservations.reservation_id')
            ->whereIn('reservations.status', [ReservationStatus::CHECKED_OUT, ReservationStatus::COMPLETED])
            ->get();
        $this->service_sales = 0;
        foreach ($finalized_services as $services) {
            $this->service_sales += $services->price;
        }

        return view('livewire.app.services.services-cards');
    }
}
