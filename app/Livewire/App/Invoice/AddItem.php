<?php

namespace App\Livewire\App\Invoice;

use App\Models\AdditionalServices;
use App\Models\Amenity;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Services\AdditionalServiceHandler;
use App\Services\AmenityService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddItem extends Component
{
    use DispatchesToast;

    protected $listeners = [
        'item-added' => '$refresh',
        'item-removed' => '$refresh',
        'item-selected' => '$refresh',
    ];

    public $items;
    public $item_type = 'others';
    public $night_count;
    public $breakdown;
    public $invoice;
    public $amenities;
    public $services;
    public $max = 999;
    #[Validate] public $amenity;
    #[Validate] public $service;
    #[Validate] public $name;
    #[Validate] public $quantity = 1;
    #[Validate] public $price = 100;

    public function mount($invoice = null) {
        $this->items = collect();
        // $this->amenities = collect();

        if (!empty($invoice)) {
            $this->invoice = Invoice::find($invoice);
            $this->setItems($this->invoice->reservation);
        }
    }

    public function rules() {
        return [
            'name' => 'required_if:item_type,others',
            'item_type' => 'required',
            'quantity' => 'required|numeric|min:1|lte:max',
            'price' => 'required|numeric|min:1',
            'amenity' => 'required_if:item_type,amenity',
            'service' => 'required_if:item_type,service',
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

        foreach ($reservation->services as $service) {
            $this->items->push([
                'id' => $service->id,
                'name' => $service->name,
                'type' => 'service',
                'quantity' => 1,
                'price' => $service->pivot->price,
            ]);
        }

        foreach ($reservation->amenities as $amenity) {
            $this->items->push([
                'id' => $amenity->id,
                'name' => $amenity->name,
                'type' => 'amenity',
                'quantity' => $amenity->pivot->quantity,
                'price' => $amenity->pivot->price,
                'max' => $amenity->quantity,
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

    public function selectItem() {
        if ($this->item_type == 'amenity') {
            $selected_amenities = $this->invoice->reservation->amenities()->pluck('amenities.id');
            $this->amenities = Amenity::whereNotIn('amenities.id', $selected_amenities)->where('amenities.quantity', '>', 0)->get();
            if ($this->amenities->count() > 0) {
                $this->name = $this->amenities->first()->name;
                $this->amenity = (int) $this->amenities->first()->id;
                $this->price = (int) $this->amenities->first()->price;
                $this->max = (int) $this->amenities->first()->quantity;
    
                if ($this->quantity > $this->max) {
                    $this->quantity = (int) $this->amenities->first()->quantity;
                }
            } else {
                $this->toast('All Amenities Added', 'info', 'Edit quantity using the table.');
            }
        } elseif ($this->item_type == 'service') {
            $selected_services = $this->invoice->reservation->services()->pluck('additional_services.id');
            $this->services = AdditionalServices::whereNotIn('additional_services.id', $selected_services)->get();
           
            if ($this->services->count() > 0) {
                $this->name = $this->services->first()->name;
                $this->service = (int) $this->services->first()->id;
                $this->price = (int) $this->services->first()->price;
                $this->max = 1;
                $this->quantity = 1;
            } else {
                $this->toast('All Services Added', 'info', 'Modify services using the table.');
            }
        } else {
            $this->reset('name', 'price', 'max', 'quantity');
        }
        $this->resetErrorBag();
    }

    public function selectedItem() {
        if ($this->item_type == 'amenity') {
            $amenity = Amenity::find($this->amenity);
            $this->name = $amenity->name;
    
            if (!empty($amenity)) {
                $this->price = (int) $amenity->price;
                $this->max = (int) $amenity->quantity;
    
                if ($this->quantity > $this->max) {
                    $this->quantity = (int) $amenity->quantity;
                }
            }
        } else {
            $service = AdditionalServices::find($this->service);
    
            if (!empty($service)) {
                $this->price = (int) $service->price;
                $this->max = 1;
                $this->quantity = 1;
            }
        }
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
            'amenity' => $this->rules()['amenity'],
            'service' => $this->rules()['service'],
        ]);

        $id = uniqid();

        if (!empty($this->invoice)) {
            if ($this->item_type == 'others') {
                $this->invoice->items()->create([
                    'name' => $this->name,
                    'item_type' => $this->item_type,
                    'quantity' => $this->quantity,
                    'price' => $this->price,
                    'total' => $this->quantity * $this->price,
                ]);
            } else {
                $service = null;

                if ($this->item_type == 'amenity' && isset($this->amenity)) {
                    $id = $this->amenity;
                    $service = new AmenityService;
                } elseif ($this->item_type == 'service' && isset($this->service)) {
                    $id = $this->service;
                    $service = new AdditionalServiceHandler;
                }

                if (!$service || !$id) {
                    throw new \Exception("Invalid item type or missing ID");
                }

                $service->attach($this->invoice->reservation, collect([
                    [
                        'id' => $id,
                        'name' => $this->name,
                        'price' => $this->price,
                        'quantity' => $this->quantity,
                    ]
                ]));
            }

            $this->items->push([
                'id' => $id,
                'name' => $this->name,
                'type' => $this->item_type,
                'quantity' => $this->quantity,
                'max' => $this->item_type == 'amenity' ?  $this->max : 999,
                'price' => $this->price,
            ]);

            // Sort the order of the items
            $order = ['room', 'service', 'amenity'];
            $this->items = $this->items->sortBy(function ($item) use ($order) {
                return array_search($item['type'], $order);
            })->values();

            // Update invoice
            $billing = new BillingService;
            $taxes = $billing->taxes($this->invoice->reservation);
            $payments = $this->invoice->payments->sum('amount');

            $this->invoice->total_amount = $taxes['net_total'];
            $this->invoice->balance = $taxes['net_total'] - $payments;
            $this->invoice->save();
        }

        $this->getTaxes();

        $this->dispatch('item-added');
        $this->reset(['item_type', 'name', 'max', 'quantity', 'price']);
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
