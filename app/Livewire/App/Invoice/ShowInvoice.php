<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ShowInvoice extends Component
{
    protected $listeners = ['payment-added' => '$refresh'];
    
    // Reservation Details
    public $rid;
    public $date_in;
    public $date_out;
    public $adult_count;
    public $children_count;
    public $night_count;
    public $reservation;
    public $selected_amenities = null;
    public $selected_rooms = null;
    // 
    public $additional_amenity;
    public $additional_amenity_id;
    public $additional_amenity_quantities;
    public $additional_amenity_quantity = 0;
    public $additional_amenity_total = 0;
    public $additional_amenities;
    public $available_amenities;
    public $discounts;
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

        $breakdown = Reservation::computeBreakdown($reservation);
        $this->vatable_sales = $breakdown['vatable_sales'];
        $this->vat = $breakdown['vat'];
        $this->net_total = $breakdown['net_total'];
        $this->additional_amenity_quantities = collect();
        $this->additional_amenities = collect();

        $this->setReservationDetails($reservation);
    }

    public function setReservationDetails(Reservation $reservation) {
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
        $this->selected_rooms = $reservation->rooms;

        // Get the number of nights between 'date_in' and 'date_out'
        $this->night_count = Carbon::parse($this->reservation['date_in'])->diffInDays(Carbon::parse($this->reservation['date_out']));
        // If 'date_in' == 'date_out', 'night_count' = 1
        $this->night_count != 0 ?: $this->night_count = 1;

        foreach ($this->selected_amenities as $amenity) {
            $this->additional_amenities->push($amenity);
            $this->additional_amenity_quantities->push([
                'amenity_id' => $amenity->id,
                'quantity' => $amenity->pivot->quantity
            ]);
        }
    }

    public function render()
    {
        return view('livewire.app.invoice.show-invoice');
    }
}
