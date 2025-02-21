<?php

namespace App\Livewire\App\Invoice;

use App\Models\Reservation;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddItem extends Component
{
    use DispatchesToast;

    public $items;
    public $item_type;
    public $night_count;
    public $breakdown;
    #[Validate] public $name;
    #[Validate] public $quantity = 1;
    #[Validate] public $price = 0;

    public function mount() {
        $this->items = collect();
    }

    public function rules() {
        return [
            'name' => 'required',
            'item_type' => 'required',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
        ];
    }

    #[On('reset-invoice')]
    public function resetItems() {
        $this->reset();

        $this->items = collect();
    }

    #[On('reservation-found')]
    public function setItems(Reservation $reservation) {
        $date_in = $reservation->date_in;
        $date_out = $reservation->date_out;

        if (!empty($reservation->resched_date_in)) {
            $date_in = $reservation->resched_date_in;
        }
        if (!empty($reservation->resched_date_out)) {
            $date_out = $reservation->resched_date_out;
        }

        $this->night_count = Carbon::parse((string) $date_in)->diffInDays($date_out);

        if ($this->night_count == 0) {
            $this->night_count = 1;
        }

        foreach ($reservation->rooms as $room) {
            $this->items->push([
                'id' => uniqid(),
                'name' => $room->building->prefix . ' ' .$room->room_number,
                'type' => 'room',
                'quantity' => $this->night_count,
                'price' => $room->pivot->rate,
            ]);
        }
        foreach ($reservation->amenities as $amenity) {
            $this->items->push([
                'id' => uniqid(),
                'name' => $amenity->name,
                'type' => 'amenity',
                'quantity' => $amenity->pivot->quantity,
                'price' => $amenity->pivot->price,
            ]);
        }
        foreach ($reservation->services as $service) {
            $this->items->push([
                'id' => uniqid(),
                'name' => $service->name,
                'type' => 'service',
                'quantity' => 1,
                'price' => $service->pivot->price,
            ]);
        }

        $this->getTaxes();
    }

    public function getTaxes() {
        $billing_service = new BillingService;
        $this->breakdown = $billing_service->rawTaxes($this->items);
    }

    public function addItem() {
        $this->validate([
            'name' => $this->rules()['name'],
            'quantity' => $this->rules()['quantity'],
            'price' => $this->rules()['price'],
        ]);

        $this->items->push([
            'id' => uniqid(),
            'name' => $this->name,
            'type' => $this->item_type,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ]);

        $this->getTaxes();

        $this->dispatch('item-added');
        $this->reset(['item_type', 'name', 'quantity', 'price']);
    }

    public function removeItem($item) {
        $this->items = $this->items->reject(function ($_item) use ($item) {
            return $_item == $item;
        });

        $this->getTaxes();
        $this->toast('Success!', description: 'Item removed: ' . ucwords($item['name']));
        $this->dispatch('item-removed');
    }

    public function render()
    {
        return view('livewire.app.invoice.add-item');
    }
}
