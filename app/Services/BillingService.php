<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Jobs\Invoice\GenerateInvoicePDF;
use App\Mail\Invoice\DiscardPayment;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BillingService
{
    public function create(Reservation $reservation, $breakdown) {
        $invoice = $reservation->invoice()->create([
            'total_amount' => Arr::get($breakdown, 'taxes.net_total', 0),
            'sub_total' => Arr::get($breakdown, 'sub_total', 0),
            'balance' => Arr::get($breakdown, 'taxes.net_total', 0),
            'status' => InvoiceStatus::PENDING,
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
        return DB::transaction(function () use ($invoice, $payment) {
            if ($invoice->reservation->status == ReservationStatus::AWAITING_PAYMENT->value) {
                $invoice->reservation->status = ReservationStatus::PENDING->value;
                $invoice->reservation->expires_at = null;
                $invoice->reservation->save();
            }
    
            if (!empty($payment['proof_image_path'])) {
                $payment['proof_image_path'] = $payment['proof_image_path']->store('payments', 'public');
            }  

            $purpose = '';

            if ($invoice->balance - $payment['amount'] <= 0) {
                $purpose = 'full payment';
            } else {
                $purpose = 'partial';
            }

            $invoice->payments()->create([
                'transaction_id' => Arr::get($payment, 'payment_method', 'gcash') == 'cash' ? null : Arr::get($payment, 'transaction_id', null),
                'amount' => Arr::get($payment, 'amount', 0),
                'purpose' => $purpose,
                'payment_date' => Arr::get($payment, 'payment_date', now()),
                'payment_method' => Arr::get($payment, 'payment_method', 'gcash'),
                'proof_image_path' => Arr::get($payment, 'proof_image_path', null),
            ]);
            
            if (!in_array($invoice->reservation->fresh()->status, [
                ReservationStatus::AWAITING_PAYMENT->value,
                ReservationStatus::PENDING->value,
            ])) {
                $invoice->balance -= $payment['amount'];
                
                if ($invoice->balance <= 0) {
                    $invoice->balance = 0;
                    $invoice->due_date = null;
                    $invoice->status = InvoiceStatus::PAID->value;
                } else {
                    $invoice->status = InvoiceStatus::PARTIAL->value;
                }
            }
    
            $invoice->save();

            return $invoice->fresh();
        });
    }

    public function issueInvoice(Invoice $invoice) {
        DB::transaction(function () use ($invoice) {
            // Generate Invoice PDF
            GenerateInvoicePDF::dispatch($invoice);
    
            $invoice->issue_date = Carbon::now()->format('Y-m-d');
            $invoice->status = InvoiceStatus::ISSUED;
            $invoice->save();
        });
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
        $vatable_sales = 0;
        $vatable_exempt_sales = 0;
        $vat = 0;
        $discount = 0;
        $promo_discount = 0;
        $other_charges = 0;
        $guest_assigned = false;

        // Check if the guest in this reservation is already assigned to rooms
        foreach ($reservation->rooms as $room) {
            $guest_count = $room->pivot->regular_guest_count;
            $discountable_guests_count = $room->pivot->discountable_guest_count; 

            if ($guest_count > 0 || $discountable_guests_count > 0) {
                $guest_assigned = true;
                break;
            }
        }

        if (in_array($reservation->status, [
            ReservationStatus::AWAITING_PAYMENT->value,
            ReservationStatus::PENDING->value,
            ReservationStatus::CONFIRMED->value,
        ]) && !$guest_assigned) {
            $vatable_sales = $sub_total / 1.12;
        } else {
            foreach ($reservation->rooms as $room) {
                $night_count = Carbon::parse((string) $reservation->date_in)->diffInDays($reservation->date_out);
        
                if ($night_count == 0) {
                    $night_count = 1;
                }

                $guest_count = $room->pivot->regular_guest_count;
                $discountable_guests_count = $room->pivot->discountable_guest_count; 
                $total_guests = $guest_count + $discountable_guests_count;
                $room_rate = $room->pivot->rate * $night_count;

                if ($total_guests == 0) {
                    $vatable_sales += $room_rate / 1.12;
                } else {
                    $vatable_sales += $room_rate / 1.12 * ($guest_count / $total_guests);
                }
                
                // Compute for promos & discounts
                if (in_array($reservation->status, [
                        ReservationStatus::CHECKED_IN->value,
                        ReservationStatus::CHECKED_OUT->value,
                    ]) || $guest_assigned) {
                    if ($discountable_guests_count > 0) {
                        if ($total_guests == $discountable_guests_count) { 
                            $vatable_exempt_sales += ($room_rate / 1.12);
                        } else {
                            $vatable_exempt_sales += ($room_rate / 1.12) * ($discountable_guests_count / $total_guests);
                        }
                    }
    
                    if ($reservation->promo) {
                        $promo_discount = $reservation->promo->amount;
                    }
                }

                // Add 'amenities'
                foreach($room->amenitiesForReservation($reservation->id)->get() as $amenity) {
                    $vatable_sales += ($amenity->pivot->price * $amenity->pivot->quantity) / 1.12;
                }
            }

            // Add 'additional services'
            if ($reservation->services->count() > 0) {
                foreach ($reservation->services as $service) {
                    $vatable_sales += $service->pivot->price / 1.12;
                }
            }
        }
        
        // Add 'other charges'
        if(!empty($reservation->invoice->items)) {
            foreach ($reservation->invoice->items as $item) {
                $other_charges += $item->price * $item->quantity;
            }
        }

        $discount = $vatable_exempt_sales * .2;
        $vat = $vatable_sales * .12;
        $net_total = (($vatable_sales + $vat + $vatable_exempt_sales + $other_charges) - $discount - $promo_discount);
        
        return [
            'sub_total' => $sub_total,
            'vatable_sales' => $vatable_sales,
            'vatable_exempt_sales' => $vatable_exempt_sales,
            'vat' => $vat,
            'other_charges' => $other_charges,
            'discount' => $discount,
            'promo_discount' => $promo_discount,
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

        $vatable_sales = 0;
        $vatable_exempt_sales = 0;
        $discount = 0;
        $promo_discount = 0;

        if (!empty($invoice)) {
            // Compute for discounts
            foreach ($invoice->reservation->rooms as $room) {
                $night_count = Carbon::parse((string) $invoice->reservation->date_in)->diffInDays($invoice->reservation->date_out);
        
                if ($night_count == 0) {
                    $night_count = 1;
                }
                
                $guest_count = $room->pivot->regular_guest_count;
                $discountable_guests_count = $room->pivot->discountable_guest_count; 
                $total_guests = $guest_count + $discountable_guests_count;
                $room_rate = $room->pivot->rate * $night_count;

                if ($total_guests == 0) {
                    $vatable_sales += $room_rate / 1.12;
                } else {
                    $vatable_sales += $room_rate / 1.12 * ($guest_count / $total_guests);
                }
                
                // Compute for promos & discounts
                if (!in_array($invoice->reservation->status, [
                        ReservationStatus::AWAITING_PAYMENT->value,
                        ReservationStatus::PENDING->value,
                        ReservationStatus::CONFIRMED->value,
                    ])) {
                    if ($discountable_guests_count > 0) {
                        if ($total_guests == $discountable_guests_count) { 
                            $vatable_exempt_sales += ($room_rate / 1.12);
                        } else {
                            $vatable_exempt_sales += ($room_rate / 1.12) * ($discountable_guests_count / $total_guests);
                        }
                    }
    
                    if ($invoice->reservation->promo) {
                        $promo_discount = $invoice->reservation->promo->amount;
                    }
                }

                // Add 'amenities'
                foreach($room->amenitiesForReservation($invoice->reservation->id)->get() as $amenity) {
                    $vatable_sales += ($amenity->pivot->price * $amenity->pivot->quantity) / 1.12;
                }
            }

            // Add 'additional services'
            if ($invoice->reservation->services->count() > 0) {
                foreach ($invoice->reservation->services as $service) {
                    $vatable_sales += $service->pivot->price / 1.12;
                }
            }

            // Add 'other charges'
            if(!empty($invoice->items)) {
                foreach ($invoice->items as $item) {
                    $other_charges += $item->price * $item->quantity;
                }
            }
        } else {
            $vatable_sales = $sub_total / 1.12;
        }

        $discount = $vatable_exempt_sales * .2;
        $vat = $vatable_sales * .12;
        $net_total = (($vatable_sales + $vat + $vatable_exempt_sales) - $discount - $promo_discount) + $other_charges;

        $taxes = [
            'vatable_sales' => $vatable_sales,
            'vatable_exempt_sales' => $vatable_exempt_sales,
            'vat' => $vat,
            'discount' => $discount,
            'promo_discount' => $promo_discount,
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
        $filename = $invoice->iid . ' - ' . strtoupper($invoice->reservation->user->last_name) . '_' . strtoupper($invoice->reservation->user->first_name) . '.pdf';
        $path = 'public/pdf/invoice/' . $filename;

        if (Storage::exists($path)) {
            return Storage::download($path, $filename);
        }

        GenerateInvoicePDF::dispatch($invoice);
        return null;
    }

    public function discardPayment(InvoicePayment $payment) {
        DB::transaction(function () use ($payment) {
            if ($payment->proof_image_path) {
                if (Storage::exists($payment->proof_image_path)) {
                    Storage::disk('public')->delete($payment->proof_image_path);
                }
            }

            $payment->invoice->reservation->status = ReservationStatus::AWAITING_PAYMENT->value;
            $payment->invoice->reservation->expires_at = Carbon::now()->addHour();
            $payment->invoice->reservation->save();

            $payment->invoice->status = InvoiceStatus::PENDING;
            $payment->invoice->save();

            $payment->delete();

            // Send mail
            Mail::to($payment->invoice->reservation->user->email)->queue(new DiscardPayment($payment->invoice));
        });
    }

    public function generatePdf(Invoice $invoice) {
        GenerateInvoicePDF::dispatch($invoice);
    }

    public function waive(Invoice $invoice, $data) {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'balance' => $invoice->balance - $data['amount'],
                'waive_amount' => $invoice->waive_amount + $data['amount'],
                'waive_reason' => $data['reason'],
                'waived_by' => Auth::user()->id,
                'status' => InvoiceStatus::WAIVED,
            ]);

            if (in_array($invoice->reservation->status, [
                ReservationStatus::AWAITING_PAYMENT->value,
                ReservationStatus::PENDING->value,
            ])) {
                $invoice->reservation->status = ReservationStatus::CONFIRMED->value;
                $invoice->reservation->expires_at = null;
                $invoice->reservation->save();
            }

            return $invoice;
        });
    }

    public function retractWaive(Invoice $invoice, $amount) {
        return DB::transaction(function () use ($invoice, $amount) {
            $invoice->waive_amount -= $amount;
            $invoice->reservation->save();
            $invoice->save();

            $taxes = $this->taxes($invoice->reservation);
            $payments = $invoice->payments->sum('amount');
            $waive = $invoice->waive_amount;

            $invoice->sub_total = $taxes['net_total'];
            $invoice->total_amount = $taxes['net_total'];
            $invoice->balance = $taxes['net_total'] - $payments;

            // Apply waived amount
            if ($invoice->balance >= $waive) {
                $invoice->balance -=  $waive;
            } else {
                $invoice->balance = 0;
            }

            if ((int) $invoice->waive_amount == 0) {
                $invoice->waive_reason = null;
                $invoice->waived_by = null;

                if ($invoice->balance > 0 && in_array($invoice->reservation->status, [
                    ReservationStatus::AWAITING_PAYMENT->value,
                    ReservationStatus::PENDING->value,
                    ReservationStatus::CONFIRMED->value,
                ])) {
                    $invoice->status = InvoiceStatus::PENDING;
                } else {
                    $invoice->status = InvoiceStatus::PARTIAL;
                }

                if ($invoice->payments()->sum('amount') == 0 && $invoice->reservation->status == ReservationStatus::CONFIRMED->value) {
                    $invoice->reservation->status = ReservationStatus::AWAITING_PAYMENT;
                    $invoice->reservation->expires_at = Carbon::now()->addHour();
                }
            }

            $invoice->reservation->save();
            $invoice->save();
            return $invoice;
        });
    }
}