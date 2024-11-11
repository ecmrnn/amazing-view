<?php

namespace App\Livewire\Guest;

use App\Models\Reservation;
use App\Models\RoomReservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FindReservation extends Component
{

    public $reservation_id;
    public $reservation = [];
    public $selected_rooms;
    public $selected_amenities = [];
    public $vat = 0;
    public $vatable_sales = 0;
    public $net_total = 0;
    public $sub_total = 0;
    public $night_count;
    public $discount_amount = 0;

    public function mount() {
        $this->reservation = new Collection;
        $this->selected_rooms = new Collection;
    }

    public function rules() {
        return [
            'reservation_id' => 'required',
        ];
    }

    public function submit() {
        $this->validate(['reservation_id' => $this->rules()['reservation_id']]);

        $this->vat = 0;
        $this->net_total = 0;
        $this->sub_total = 0;
        $this->reservation = Reservation::where('rid', $this->reservation_id)->first();
        
        if ($this->reservation != null) {
            $this->selected_rooms = Reservation::find($this->reservation['id'])->rooms;
            $this->selected_amenities = Reservation::find($this->reservation['id'])->amenities;

            // Get the number of nights between 'date_in' and 'date_out'
            $this->night_count = Carbon::parse($this->reservation['date_in'])->diffInDays(Carbon::parse($this->reservation['date_out']));
            // If 'date_in' == 'date_out', 'night_count' = 1
            $this->night_count != 0 ?: $this->night_count = 1;

            foreach ($this->selected_rooms as $room) {
                $this->sub_total += ($room->rate * $this->night_count);
            }

            foreach ($this->selected_amenities as $amenity) {
                $this->sub_total += $amenity->price;
            }

            $this->vatable_sales = $this->sub_total / 1.12;
            $this->vat = ($this->sub_total) - $this->vatable_sales;
            $this->net_total = $this->vatable_sales + $this->vat;
        }
    }

    public function render()
    {
        return view('livewire.guest.find-reservation');
    }
}
