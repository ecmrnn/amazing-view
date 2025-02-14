<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;

class BillingService
{
    public function create(Reservation $reservation, $breakdown) {
        $invoice = $reservation->invoice()->create([
            'total_amount' => Arr::get($breakdown, 'sub_total', 0),
            'balance' => Arr::get($breakdown, 'sub_total', 0),
            'status' => Invoice::STATUS_PENDING,
        ]);    

        return $invoice;
    }

    public function update(Invoice $invoice, $data) {
        $invoice->update([
            'total_amount' => $data['total_amount'],
            'balance' => $data['balance'],
            'status' => $data['status'],
        ]);

        return $invoice;
    }

    public function addPayment(Invoice $invoice) {
        // $invoice->payments()->create();
    }

    public function breakdown(Reservation $reservation) {
        $sub_total = $this->subtotal($reservation);
        $breakdown = [];

        $taxes = $this->taxes($reservation);

        return [
            'sub_total' => $sub_total,
            'taxes' => $taxes,
            'breakdown' => $breakdown,
        ];
        
    }

    public function breakdownRaw($reservation) {
        // dd($reservation);
    }

    public function taxes(Reservation $reservation) {
        $sub_total = $this->subtotal($reservation);
        $vatable_sales = $sub_total / 1.12;
        $vatable_exempt_sales = 0;
        $discount = 0;
        
        // Compute for discounts
        if (in_array($reservation->status, [
            ReservationStatus::AWAITING_PAYMENT->value,
            ReservationStatus::PENDING->value,
            ReservationStatus::CONFIRMED->value,
        ])) {
            if ($reservation->senior_count > 0 || $reservation->pwd_count > 0) {
                $guest_count = $reservation->children_count + $reservation->adult_count;
                $discountable_guests = $reservation->pwd_count + $reservation->senior_count;
    
                $vatable_sales = $sub_total / 1.12 * (($guest_count - $discountable_guests) / $guest_count);
                $vatable_exempt_sales = ($sub_total / 1.12) * ($discountable_guests / $guest_count);
                $discount = ($vatable_exempt_sales * .2) * $discountable_guests; 
            }
        }
        
        $vat = $vatable_sales * .12;
        $net_total = ($vatable_sales + $vat + $vatable_exempt_sales) - $discount;

        return [
            'vatable_sales' => $vatable_sales,
            'vatable_exempt_sales' => $vatable_exempt_sales,
            'vat' => $vat,
            'discount' => $discount,
            'net_total' => $net_total,
        ];
    }

    public function discounts(Reservation $reservation) {
        $discountable_guests = $reservation->pwd_count + $reservation->senior_count;
        $vatable_exempt_sales = $this->taxes($reservation)['vatable_exempt_sales'];

        // Calculate discount
        $discount = ($vatable_exempt_sales * .2) * $discountable_guests; 

        return $discount;
    }

    public function subtotal(Reservation $reservation) {
        $sub_total = 0;
        $date_in = $reservation->date_in;
        $date_out = $reservation->date_out;
        
        if (!empty($reservation->resched_date_in)) {
            $date_in = $reservation->resched_date_in;
        }
        if (!empty($reservation->resched_date_out)) {
            $date_out = $reservation->resched_date_out;
        }

        $night_count = Carbon::parse($date_in)->diffInDays($date_out);

        if ($night_count == 0) {
            $night_count = 1;
        }

        // Compute room rates
        if (!empty($reservation->rooms)) {
            foreach ($reservation->rooms as $room) {
                if ($room->pivot) {
                    $sub_total += $room->pivot->rate * $night_count;
                    $breakdown[] = [
                        'name' => $room->name,
                        'quantity' => $night_count,
                        'rate' => $room->pivot->rate * $night_count,
                    ];
                }
            }
        }

        // Compute amenity rates
        if (!empty($reservation->amenities)) {
            foreach ($reservation->amenities as $amenity) {
                $sub_total += $amenity->pivot->price * $amenity->pivot->quantity;
                $breakdown[] = [
                    'name' => $amenity->name,
                    'quantity' => $amenity->pivot->quantity,
                    'price' => $amenity->pivot->price,
                ];
            }
        }
        
        // Compute service prices
        if (!empty($reservation->services)) {
            foreach ($reservation->services as $service) {
                $sub_total += $service->pivot->price;
                $breakdown[] = [
                    'name' => $service->name,
                    'quantity' => 1,
                    'price' => $service->pivot->price,
                ];
            }
        }

        return $sub_total;
    }
}