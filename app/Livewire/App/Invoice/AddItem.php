<?php

namespace App\Livewire\App\Invoice;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Services\AmenityService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

use function PHPUnit\Framework\isNull;

class AddItem extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'item-added' => '$refresh',
        'item-removed' => '$refresh',
    ];

    public $items;
    public $item_type;
    public $night_count;
    public $breakdown;
    public $invoice;
    #[Validate] public $name;
    #[Validate] public $quantity = 1;
    #[Validate] public $price = 0;

    public function mount($invoice = null) {
        $this->items = collect();

        if (!empty($invoice)) {
            $this->invoice = Invoice::find($invoice);
            $this->setItems($this->invoice->reservation);
        }
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
                'id' => $room->id,
                'name' => $room->building->prefix . ' ' .$room->room_number,
                'type' => 'room',
                'quantity' => $this->night_count,
                'price' => $room->pivot->rate,
            ]);
        }
        foreach ($reservation->amenities as $amenity) {
            $this->items->push([
                'id' => $amenity->id,
                'name' => $amenity->name,
                'type' => 'amenity',
                'quantity' => $amenity->pivot->quantity,
                'price' => $amenity->pivot->price,
            ]);
        }
        foreach ($reservation->services as $service) {
            $this->items->push([
                'id' => $service->id,
                'name' => $service->name,
                'type' => 'service',
                'quantity' => 1,
                'price' => $service->pivot->price,
            ]);
        }

        
        foreach ($reservation->invoice->items as $item) {
            // $other_charges += $item->quantity * $item->price;
            $this->items->push([
                'id' => $item->id,
                'name' => $item->name,
                'type' => 'others',
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }
        
        $this->getTaxes();
    }

    public function getTaxes() {
        $billing_service = new BillingService;
        $this->breakdown = $billing_service->rawTaxes($this->invoice, $this->items);
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

        if (!empty($this->invoice)) {
            // Add items to invoice when type is others
            if ($this->item_type == 'others') {
                $this->invoice->items()->create([
                    'name' => $this->name,
                    'item_type' => $this->item_type,
                    'quantity' => $this->quantity,
                    'price' => $this->price,
                    'total' => $this->quantity * $this->price,
                ]);
            }

            // Update invoice balance
            $this->invoice->balance += $this->quantity * $this->price;
            $this->invoice->save();
        }

        $this->getTaxes();

        $this->dispatch('item-added');
        $this->reset(['item_type', 'name', 'quantity', 'price']);
    }

    public function removeItem($item) {
        $this->items = $this->items->reject(function ($_item) use ($item) {
            return $_item == $item;
        });

        if (!empty($this->invoice) && $item['type'] == 'others') {
            $item_to_delete = $this->invoice->items()->find($item['id']);

            if (!empty($item_to_delete)) {
                $item_to_delete->delete();
                $this->invoice->balance -= $item['price'] * $item['quantity'];
                $this->invoice->save();
            }
        }

        $this->getTaxes();
        $this->toast('Success!', description: 'Item removed: ' . ucwords($item['name']));
        $this->dispatch('item-removed');
    }

    public function updateAmenity($id, $quantity) {
        $this->items = $this->items->map(function ($item) use ($id, $quantity) {
            if ($item['type'] == 'amenity' && $item['id'] == $id) {
                $item['quantity'] = $quantity;
            }
            return $item;
        });

        $item = $this->items->first(function ($item) use ($id) {
            if ($item['type'] == 'amenity' && $item['id'] == $id) {
                return $item;
            }
        });

        // Update the database
        if (!empty($this->invoice)) {
            $service = new AmenityService;
            $amenities = $this->items->filter(function ($item) {
                return $item['type'] == 'amenity';
            });
            $service->sync($this->invoice->reservation, $amenities);
        }

        $this->getTaxes();
        $this->toast('Success!', description: 'Updated quantity of ' . ucwords($item['name'] . '!'));
    }

    public function render()
    {
        return view('livewire.app.invoice.add-item');
    }
}
