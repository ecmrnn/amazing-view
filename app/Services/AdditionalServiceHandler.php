<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ServiceStatus;
use App\Models\AdditionalServices;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class AdditionalServiceHandler
{
    public function create($data) {
        return DB::transaction(function () use ($data) {
            return AdditionalServices::create($data);
        });    
    }

    public function delete(AdditionalServices $service) {
        return DB::transaction(function () use ($service) {
            return $service->delete();
        });
    }

    public function toggleStatus(AdditionalServices $service) {
        return DB::transaction(function () use ($service) {
            if ($service->status == ServiceStatus::ACTIVE->value) {
                return $service->update([
                    'status' => ServiceStatus::INACTIVE
                ]);
            }  

            return $service->update([
                'status' => ServiceStatus::ACTIVE
            ]);
        });
    }
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
        DB::transaction(function () use ($reservation, $services) {
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
    
            // Update the invoice
            $billing = new BillingService;
            $taxes = $billing->taxes($reservation->fresh());
            $payments = $reservation->invoice->payments->sum('amount');
            $waive = $reservation->invoice->waive_amount;
    
            $reservation->invoice->sub_total = $taxes['net_total'];
            $reservation->invoice->total_amount = $taxes['net_total'];
            $reservation->invoice->balance = $taxes['net_total'] - $payments;
            
            // Apply waived amount
            if ((int) $waive > 0 && $reservation->invoice->balance >= $waive) {
                $reservation->invoice->balance -=  $waive;
            } 

            if ($reservation->invoice->balance > 0) {
                $reservation->invoice->status = InvoiceStatus::PARTIAL->value;
            } else {
                $reservation->invoice->status = InvoiceStatus::PAID->value;
            }
    
            $reservation->invoice->save();
        });
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