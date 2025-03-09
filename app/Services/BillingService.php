<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Jobs\Invoice\GenerateInvoicePDF;
use App\Models\Invoice;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class BillingService
{
    public function create(Reservation $reservation, $breakdown) {
        $invoice = $reservation->invoice()->create([
            'total_amount' => Arr::get($breakdown, 'taxes.net_total', 0),
            'sub_total' => Arr::get($breakdown, 'sub_total', 0),
            'balance' => Arr::get($breakdown, 'taxes.net_total', 0),
            'status' => Invoice::STATUS_PENDING,
            'due_date' => Carbon::parse((string) $reservation->date_out)->addWeek(),
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

    public function addPayment(Invoice $invoice, $payment) {
        if ($invoice->reservation->status == ReservationStatus::AWAITING_PAYMENT->value) {
            $invoice->reservation->status = ReservationStatus::PENDING->value;
            $invoice->reservation->save();
        }

        if (!empty($payment['proof_image_path'])) {
            $payment['proof_image_path'] = $payment['proof_image_path']->store('payments', 'public');
        }  
        
        $invoice->payments()->create([
            'transaction_id' => $payment['transaction_id'],
            'amount' => $payment['amount'],
            'payment_date' => $payment['payment_date'],
            'payment_method' => $payment['payment_method'],
            'proof_image_path' => $payment['proof_image_path'],
        ]);
        
        $invoice->balance -= $payment['amount'];

        if ($invoice->balance <= 0) {
            $invoice->balance = 0;
            $invoice->status = InvoiceStatus::PAID->value;
        } else {
            $invoice->status = InvoiceStatus::PARTIAL->value;
        }

        $invoice->save();

        return $invoice;
    }

    public function issueInvoice(Invoice $invoice) {
        // Generate Invoice PDF
        GenerateInvoicePDF::dispatch($invoice);

        $invoice->issue_date = Carbon::now()->format('Y-m-d');
        $invoice->status = InvoiceStatus::ISSUED;
        $invoice->save();
    }

    public function breakdown(Reservation $reservation) {
        $sub_total = $this->subtotal($reservation);

        $taxes = $this->taxes($reservation);

        return [
            'sub_total' => $sub_total,
            'taxes' => $taxes,
        ];
    }

    public function taxes(Reservation $reservation) {
        $sub_total = $this->subtotal($reservation);
        $vatable_sales = $sub_total / 1.12;
        $vatable_exempt_sales = 0;
        $discount = 0;
        $other_charges = 0;
        
        // Compute for discounts
        if (in_array($reservation->status, [
            ReservationStatus::AWAITING_PAYMENT->value,
            ReservationStatus::PENDING->value,
            ReservationStatus::CONFIRMED->value,
            ReservationStatus::CHECKED_OUT->value,
            ReservationStatus::COMPLETED->value,
        ])) {
            if ($reservation->senior_count > 0 || $reservation->pwd_count > 0) {
                $guest_count = $reservation->children_count + $reservation->adult_count;
                $discountable_guests = $reservation->pwd_count + $reservation->senior_count;
    
                $vatable_sales = $sub_total / 1.12 * (($guest_count - $discountable_guests) / $guest_count);
                $vatable_exempt_sales = ($sub_total / 1.12) * ($discountable_guests / $guest_count);
                $discount = ($vatable_exempt_sales * .2) * $discountable_guests;
            }
        }

        // Add 'other charges'
        if(!empty($reservation->invoice->items)) {
            foreach ($reservation->invoice->items as $item) {
                $other_charges += $item->price * $item->quantity;
            }
        }
        
        $vat = $vatable_sales * .12;
        $net_total = (($vatable_sales + $vat + $vatable_exempt_sales) - $discount) + $other_charges;

        return [
            'sub_total' => $sub_total,
            'vatable_sales' => $vatable_sales,
            'vatable_exempt_sales' => $vatable_exempt_sales,
            'vat' => $vat,
            'other_charges' => $other_charges,
            'discount' => $discount,
            'net_total' => $net_total,
        ];
    }

    public function rawTaxes($invoice = null, $items) {
        $sub_total = 0;
        $other_charges = 0;
        
        // Compute the subtotal
        foreach ($items as $item) {
            if ($item['type'] != 'others') {
                $sub_total += $item['price'] * $item['quantity'];
            } else {
                $other_charges += $item['price'] * $item['quantity'];
            }
        }

        $vatable_sales = $sub_total / 1.12;
        $vatable_exempt_sales = 0;
        $discount = 0;

        if (!empty($invoice)) {
            // Compute for discounts
            if (in_array($invoice->reservation->status, [
                ReservationStatus::AWAITING_PAYMENT->value,
                ReservationStatus::PENDING->value,
                ReservationStatus::CONFIRMED->value,
                ReservationStatus::CHECKED_OUT->value,
                ReservationStatus::COMPLETED->value,
            ])) {
                if ($invoice->reservation->senior_count > 0 || $invoice->reservation->pwd_count > 0) {
                    $guest_count = $invoice->reservation->children_count + $invoice->reservation->adult_count;
                    $discountable_guests = $invoice->reservation->pwd_count + $invoice->reservation->senior_count;
        
                    $vatable_sales = $sub_total / 1.12 * (($guest_count - $discountable_guests) / $guest_count);
                    $vatable_exempt_sales = ($sub_total / 1.12) * ($discountable_guests / $guest_count);
                    $discount = ($vatable_exempt_sales * .2) * $discountable_guests; 
                }
            }
        }

        $vat = $vatable_sales * .12;
        $net_total = (($vatable_sales + $vat + $vatable_exempt_sales) - $discount) + $other_charges;

        $taxes = [
            'vatable_sales' => $vatable_sales,
            'vatable_exempt_sales' => $vatable_exempt_sales,
            'vat' => $vat,
            'discount' => $discount,
            'other_charges' => $other_charges,
            'net_total' => $net_total,
        ];

        return [
            'sub_total' => $sub_total,
            'taxes' => $taxes,
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
        
        $night_count = Carbon::parse((string) $date_in)->diffInDays($date_out);

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
        foreach ($reservation->rooms as $room) {
            foreach($room->amenitiesForReservation($reservation->id)->get() as $amenity) {
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

    public function cancel(Invoice $invoice) {
        $invoice->status = InvoiceStatus::CANCELED;
        $invoice->save();
    }

    public function downloadPdf(Invoice $invoice) {
        $filename = $invoice->iid . ' - ' . strtoupper($invoice->reservation->last_name) . '_' . strtoupper($invoice->reservation->first_name) . '.pdf';
        $path = 'public/pdf/invoice/' . $filename;

        if (Storage::exists($path)) {
            return Storage::download($path, $filename);
        }

        GenerateInvoicePDF::dispatch($invoice);
        return null;
    }
}