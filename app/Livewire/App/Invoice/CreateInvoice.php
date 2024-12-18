<?php

namespace App\Livewire\App\Invoice;

use App\Models\Amenity;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateInvoice extends Component
{
    use DispatchesToast;

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address;
    // Payment
    #[Validate] public $vatable_sales;
    #[Validate] public $sub_total;
    #[Validate] public $net_total;
    #[Validate] public $vat;
    // Invoice
    #[Validate] public $issue_date;
    #[Validate] public $due_date;
    public $discount_amount = 0;
    public $downpayment;

    public $date_today;
    public $selected_amenities;
    public $additional_amenity;
    public $additional_amenity_id;
    public $additional_amenity_quantities;
    public $additional_amenity_quantity = 0;
    public $additional_amenity_total = 0;
    public $additional_amenities;
    public $available_amenities;
    public $discounts;
    
    public $selected_discounts;
    public $selected_rooms;
    public $reservation;
    public $night_count = 1;
    #[Url]
    public $rid;

    public function mount() {
        $this->selected_amenities = collect();
        $this->additional_amenities = collect();
        $this->additional_amenity_quantities = collect();
        $this->selected_discounts = collect();
        $this->selected_rooms = collect();
        $this->reservation = collect();
        $this->date_today = Carbon::now()->format('Y-m-d');
        
        if (!empty($this->rid)) {
            $this->getReservation($this->rid);
        }
    }

    public function messages() 
    {
        return [
            'additional_amenity' => 'Select an :attribute first',
            'additional_amenity_quantity' => 'Quantity must be atleast 1',
        ];
    }
 
    public function validationAttributes() 
    {
        return [
            'additional_amenity' => 'amenity',
        ];
    }

    public function getReservation($reservation_id) {
        if ($reservation_id) {
            $this->reservation = Reservation::where('rid', $reservation_id)->first();

            if (!empty($this->reservation)) {
                // Initialize properties
                $this->issue_date = Carbon::now()->format('Y-m-d');
                $this->due_date = Carbon::now()->addWeek()->format('Y-m-d');
                $this->discounts = Discount::where('status', Discount::STATUS_ACTIVE)->get();
                $this->selected_rooms = Reservation::find($this->reservation->id)->rooms;
                $this->selected_amenities = Reservation::find($this->reservation->id)->amenities;

                // Get the number of nights between 'date_in' and 'date_out'
                $this->night_count = Carbon::parse($this->reservation['date_in'])->diffInDays(Carbon::parse($this->reservation['date_out']));
                // If 'date_in' == 'date_out', 'night_count' = 1
                $this->night_count != 0 ?: $this->night_count = 1;

                $this->vat = 0;
                $this->net_total = 0;
                $this->sub_total = 0;

                foreach ($this->selected_rooms as $room) {
                    $this->sub_total += ($room->rate * $this->night_count);
                }

                foreach ($this->selected_amenities as $amenity) {
                    $quantity = $amenity->pivot->quantity;
                    
                    // If quantity is 0, change it to 1
                    $quantity != 0 ?: $quantity = 1;

                    $this->sub_total += ($amenity->price * $quantity);
                }

                $this->computeBreakdown();

                $this->rid = $this->reservation->rid;
                $this->date_in = $this->reservation->date_in;
                $this->date_out = $this->reservation->date_out;
                $this->adult_count = $this->reservation->adult_count;
                $this->children_count = $this->reservation->children_count;

                $this->first_name = $this->reservation->first_name;
                $this->last_name = $this->reservation->last_name;
                $this->email = $this->reservation->email;
                $this->phone = $this->reservation->phone;
                $this->address = $this->reservation->address;

                // Get all available amenities
                $this->available_amenities = Amenity::where('quantity', '>', 0)->orderBy('name')->get();

                foreach ($this->selected_amenities as $amenity) {
                    $this->additional_amenities->push($amenity);
                    $this->additional_amenity_quantities->push([
                        'amenity_id' => $amenity->id,
                        'quantity' => $amenity->pivot->quantity
                    ]);
                }

                $this->toast('Success!', 'success', 'Reservation found!');
            } else {
                $this->reservation = collect();
                $this->toast('Failed', 'danger', 'Reservation not found!');
            }
        }
    }

    public function selectAmenity($id) {
        if (!empty($id)) {
            $this->additional_amenity_id = $id;
            $this->additional_amenity = Amenity::find($id);
            $this->getTotal();
        }
    }

    public function addAmenity() {
        $this->validate([
            'additional_amenity_quantity' => 'integer|min:1|required',
            'additional_amenity' => 'required',
        ]);

        $amenity = $this->additional_amenity;

        if ($this->additional_amenity_quantity <= $amenity->quantity) {
            $this->additional_amenities->push($amenity);
    
            $this->additional_amenity_quantities->push([
                'amenity_id' => $amenity->id,
                'quantity' => $this->additional_amenity_quantity
            ]);
            
            // Push to amenities selected on reservation
            $this->selected_amenities->push($amenity);

            // Recomputes Breakdown
            $this->sub_total += ($amenity->price * $this->additional_amenity_quantity);
            $this->computeBreakdown();

            // Reset properties
            $this->reset([
                'additional_amenity_quantity',
                'additional_amenity_total',
                'additional_amenity_id',
                'additional_amenity',
            ]);
        } else {
            $this->toast('Oof, not enough item!', 'warning', 'Item quantity is not enough');
        }
    }

    public function removeAmenity(Amenity $amenity) {
        if ($amenity) {
            // Get the quantity for this amenity
            $quantity = 1;
            foreach ($this->additional_amenity_quantities as $selected_amenity) {
                if ($selected_amenity['amenity_id'] == $amenity->id) {
                    $quantity = $selected_amenity['quantity'];
                    break;
                }
            }

            // Remove this amenity on these properties
            $this->additional_amenities = $this->additional_amenities->reject(function ($amenity_loc) use ($amenity) {
                return $amenity_loc->id == $amenity->id;
            });
            $this->selected_amenities = $this->selected_amenities->reject(function ($amenity_loc) use ($amenity) {
                return $amenity_loc->id == $amenity->id;
            });
            $this->additional_amenity_quantities = $this->additional_amenity_quantities->reject(function ($amenity_loc) use ($amenity) {
                return $amenity_loc['amenity_id'] == $amenity->id;
            });

            // Recompute breakdown
            $this->sub_total -= ($amenity->price * $quantity);
            $this->computeBreakdown();
        }
    }

    public function getTotal() {
        if ($this->additional_amenity_id && $this->additional_amenity_quantity) {
            $this->additional_amenity_total = $this->additional_amenity->price * $this->additional_amenity_quantity;
        }
    }

    public function toggleDiscount(Discount $discount) {
        $discount_amount = 0;

        if ($this->selected_discounts->contains('id', $discount->id)) {
            $this->selected_discounts = $this->selected_discounts->reject(function ($discount_loc) use ($discount) {
                return $discount_loc->id == $discount->id;
            });

            if (empty($discount->amount)) {
                $discount_amount = ($discount->percentage / 100) * $this->sub_total;
            } else {
                $discount_amount = $discount->amount;
            }

            $this->discount_amount -= $discount_amount;
        } else {
            $this->selected_discounts->push($discount);

            if (empty($discount->amount)) {
                $discount_amount = ($discount->percentage / 100) * $this->sub_total;
            } else {
                $discount_amount = $discount->amount;
            }

            $this->discount_amount += $discount_amount ;
        }
    }

    public function computeBreakdown() {
        $this->vatable_sales = $this->sub_total / 1.12;
        $this->vat = ($this->sub_total) - $this->vatable_sales;
        $this->net_total = $this->vatable_sales + $this->vat;
    }

    public function store() {
        // Validate all required properties
        $this->validate([
            'reservation' => 'required',
            'net_total' => 'required|integer',
            'issue_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:issue_date',
        ]);

        // Removes old and non existing amenity
        foreach ($this->reservation->amenities as $amenity) {
            if (!$this->selected_amenities->contains('id', $amenity->id)) {
                $this->reservation->amenities()->detach($amenity->id);
            }
        }
        foreach ($this->selected_amenities as $amenity) {
            // If the newly selected amenities exists in the already selected amenities
            // - updates the record
            $quantity = 0;
            
            foreach ($this->additional_amenity_quantities as $selected_amenity) {
                if ($selected_amenity['amenity_id'] == $amenity->id) {
                    $quantity = $selected_amenity['quantity'];
                    break;
                }
            }

            if ($this->reservation->amenities->contains('id', $amenity->id)) {
                $this->reservation->amenities()->updateExistingPivot($amenity->id, ['quantity' => $quantity]);
            } else {
                $this->reservation->amenities()->attach($amenity->id, ['quantity' => $quantity]);
            }
        }

        // Save new invoice data
        $invoice = $this->reservation->invoice;
        $invoice->issue_date = $this->issue_date;
        $invoice->due_date = $this->due_date;
        $invoice->total_amount = $this->net_total;
        $invoice->balance = $this->net_total - $invoice->downpayment;
        $invoice->save();

        // Create Discount
        foreach ($this->selected_discounts as $discount) {
            $invoice->discounts()->attach($discount->id);
        }

        $this->toast('Success!', 'success', 'Invoice created!');
        
        // Reset all properties
        $this->reset();

        $this->rid = null;
        $this->selected_amenities = collect();
        $this->additional_amenities = collect();
        $this->additional_amenity_quantities = collect();
        $this->selected_discounts = collect();
        $this->selected_rooms = collect();
        $this->reservation = collect();
    }

    public function render()
    {
        return view('livewire.app.invoice.create-invoice');
    }
}
