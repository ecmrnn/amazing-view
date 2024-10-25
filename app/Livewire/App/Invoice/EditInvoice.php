<?php

namespace App\Livewire\App\Invoice;

use App\Models\Amenity;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Component;

class EditInvoice extends Component
{
    use DispatchesToast;

    protected $listeners = ['payment-added' => '$refresh'];
    
    // Reservation Details
    public $rid;
    public $date_in;
    public $date_out;
    public $adult_count;
    public $children_count;
    public $night_count;
    public $reservation;
    public $selected_rooms = null;
    public $selected_amenities = null;
    // Amenity
    public $additional_amenity;
    public $additional_amenity_id;
    public $additional_amenity_quantities;
    public $additional_amenity_quantity = 0;
    public $additional_amenity_total = 0;
    public $additional_amenities;
    public $available_amenities;
    public $discounts;

    public $selected_discounts;
    // Guest Details
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    // Payment
    public $vatable_sales;
    public $vat;
    public $net_total;
    public $sub_total;
    public $discount_amount;
    // Invoice
    public $invoice;
    public $issue_date;
    public $due_date;

    public function mount(Invoice $invoice) {
        $this->invoice = $invoice;
        $this->issue_date = $invoice->issue_date;
        $this->due_date = $invoice->due_date;

        $reservation = Reservation::find($invoice->reservation_id);

        $this->additional_amenities = collect();
        $this->additional_amenity_quantities = collect();

        $this->setProperties($reservation);
    }

    public function setProperties(Reservation $reservation) {
        $this->reservation = $reservation;
        $this->rid = $reservation->rid;
        $this->date_in = $reservation->date_in;
        $this->date_out = $reservation->date_out;
        $this->adult_count = $reservation->adult_count;
        $this->children_count = $reservation->children_count;

        $this->first_name = $reservation->first_name;
        $this->last_name = $reservation->last_name;
        $this->email = $reservation->email;
        $this->phone = $reservation->phone;
        $this->address = $reservation->address;

        $this->selected_amenities = $reservation->amenities;
        $this->available_amenities = Amenity::where('is_addons', 0)->get();
        $this->selected_rooms = $reservation->rooms;

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
        // Attach selected amenities to additional_amenities
        foreach ($this->selected_amenities as $amenity) {
            $this->additional_amenities->push($amenity);
            $this->additional_amenity_quantities->push([
                'amenity_id' => $amenity->id,
                'quantity' => $amenity->pivot->quantity
            ]);
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

    public function updateAmenity() {
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
    }

    public function computeBreakdown() {
        $this->vatable_sales = $this->sub_total / 1.12;
        $this->vat = ($this->sub_total) - $this->vatable_sales;
        $this->net_total = $this->vatable_sales + $this->vat;
    }

    public function render()
    {
        return view('livewire.app.invoice.edit-invoice');
    }
}
