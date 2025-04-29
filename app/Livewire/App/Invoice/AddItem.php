<?php

namespace App\Livewire\App\Invoice;

use App\Enums\AmenityStatus;
use App\Enums\ReservationStatus;
use App\Enums\ServiceStatus;
use App\Models\AdditionalServices;
use App\Models\Amenity;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomAmenity;
use App\Services\AdditionalServiceHandler;
use App\Services\AmenityService;
use App\Services\BillingService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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
    public $item_type;
    public $night_count;
    public $breakdown;
    public $invoice;
    public $amenities;
    public $room_number;
    public $services;
    public $max = 99999;
    public $discount;
    #[Validate] public $amenity;
    #[Validate] public $service;
    #[Validate] public $name;
    #[Validate] public $quantity = 1;
    #[Validate] public $price = 100;

    public function mount($invoice = null) {
        $this->items = collect();
        if (!empty($invoice)) {
            $this->invoice = Invoice::find($invoice);
            $this->discount = $this->invoice->reservation->discounts()->first()->description;
            $checked_in_rooms = $this->invoice->reservation->rooms()->where('room_reservations.status', ReservationStatus::CHECKED_IN->value)->get();
            
            if ($checked_in_rooms->count() > 0) {
                $this->room_number = $checked_in_rooms->first()->room_number;
            } else {
                $this->room_number = $this->invoice->reservation->rooms()->first()->room_number;
            }

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

        $this->night_count = Carbon::parse((string) $date_in)->diffInDays($date_out);

        if ($this->night_count == 0) {
            $this->night_count = 1;
        }

        foreach ($reservation->rooms as $room) {
            $this->items->push([
                'id' => $room->id,
                'name' => $room->room_number,
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
                'max' => 99999
            ]);
        }

        foreach ($reservation->rooms as $room) {
            foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity) {
                $this->items->push([
                    'id' => $amenity->id,
                    'room_number' => $room->room_number,
                    'name' => $amenity->name,
                    'type' => 'amenity',
                    'quantity' => $amenity->pivot->quantity,
                    'price' => $amenity->pivot->price,
                    'max' => $amenity->quantity + $amenity->pivot->quantity,
                    'status' => $room->pivot->status,
                ]);
            }

            foreach ($room->items as $item) {
                if ($item->invoice_id == $reservation->invoice->id) {
                    $this->items->push([
                        'id' => $item->id,
                        'room_number' => $room->room_number,
                        'name' => $item->name,
                        'type' => 'others',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'status' => $room->pivot->status,
                    ]);
                }
            }
        }
        
        $this->getTaxes();
    }

    public function selectItem($item) {
        $this->item_type = $item;
        if ($this->item_type == 'amenity') {
            $this->amenities = Amenity::where('quantity', '>', 0)->get();

            if ($this->amenities->count() > 0) {
                $this->name = $this->amenities->first()->name;
                // $this->amenity = (int) $this->amenities->first()->id;
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
            $this->services = AdditionalServices::whereNotIn('additional_services.id', $selected_services)
                ->whereStatus(ServiceStatus::ACTIVE)
                ->get();
           
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
            
            if (!empty($amenity)) {
                $this->name = $amenity->name;
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
        $this->resetErrorBag();
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
        $collect = null;
        
        if (!empty($this->invoice)) {
            if ($this->item_type == 'others') {
                foreach ($this->invoice->reservation->rooms as $room) {
                    if ($this->room_number == $room->room_number) {
                        $item = $this->invoice->items()->create([
                            'name' => $this->name,
                            'room_id' => $room->id,
                            'quantity' => $this->quantity,
                            'price' => $this->price,
                            'total' => $this->quantity * $this->price,
                        ]);
                        $collect = array(
                            'id' => $item->id,
                            'room_number' => $this->room_number,
                            'name' => $this->name,
                            'type' => $this->item_type,
                            'quantity' => $this->quantity,
                            'price' => $this->price,
                            'max' => 99999
                        );
                    }
                }
            } else {
                $service = null;

                if ($this->item_type == 'amenity' && isset($this->amenity)) {
                    $id = $this->amenity;
                    $service = new AmenityService;
                    $collect = array(
                        'id' => $id,
                        'room_number' => $this->room_number,
                        'name' => $this->name,
                        'type' => $this->item_type,
                        'quantity' => $this->quantity,
                        'price' => $this->price,
                        'max' => $this->max,
                    );
                } elseif ($this->item_type == 'service' && isset($this->service)) {
                    $id = $this->service;
                    $service = new AdditionalServiceHandler;

                    $collect = array(
                        'id' => $id,
                        'name' => $this->name,
                        'price' => $this->price,
                        'type' => $this->item_type,
                        'quantity' => $this->quantity,
                    );
                }

                if (!$service || !$id) {
                    throw new \Exception("Invalid item type or missing ID");
                }

                $service->attach($this->invoice->reservation, collect([$collect]));
            }

            $this->items->push($collect);

            // Sort the order of the items
            $order = ['room', 'service', 'amenity'];
            $this->items = $this->items->sortBy(function ($item) use ($order) {
                return array_search($item['type'], $order);
            })->values();

            // Update invoice
            $billing = new BillingService;
            $taxes = $billing->taxes($this->invoice->reservation->fresh());
            $payments = $this->invoice->payments->sum('amount');

            $this->invoice->sub_total = $taxes['sub_total'];
            $this->invoice->total_amount = $taxes['net_total'];
            $this->invoice->balance = $taxes['net_total'] - $payments;
            $this->invoice->save();
        }

        $this->getTaxes();

        $this->dispatch('item-added');
        $this->reset(['item_type', 'name', 'max', 'amenity', 'quantity', 'price']);
    }

    public function removeItem($item) {
        if (!empty($this->invoice)) {
            if ($item['type'] == 'others') {
                $item_to_delete = $this->invoice->items()->find($item['id']);
    
                if (!empty($item_to_delete)) {
                    $item_to_delete->delete();
                    $this->invoice->balance -= $item['price'] * $item['quantity'];
                    $this->invoice->save();
                }
            } else {
                $service = null;
                $items = $this->items->filter(function ($_item) use ($item) {
                    if ($item['type'] == $_item['type']) {
                        return $_item != $item;
                    }
                });

                if ($item['type'] == 'amenity') {
                    $service = new AmenityService;
                } else {
                    $service = new AdditionalServiceHandler;
                }

                $service->sync($this->invoice->reservation, $items);
            }
        }

        // Remove the item from the collection
        $this->items = $this->items->reject(function ($_item) use ($item) {
            return $_item == $item;
        });

        // Update invoice
        $billing = new BillingService;
        $taxes = $billing->taxes($this->invoice->reservation->fresh());
        $payments = $this->invoice->payments->sum('amount');

        $this->invoice->sub_total = $taxes['sub_total'];
        $this->invoice->total_amount = $taxes['net_total'];
        $this->invoice->balance = $taxes['net_total'] - $payments;
        $this->invoice->save();

        $this->getTaxes();
        $this->toast('Success!', description: 'Item removed: ' . ucwords($item['name']));
        $this->dispatch('item-removed');
    }

    public function updateQuantity($id, $quantity, $item_type, $room_number) {
        $item = $this->items->first(function ($item) use ($id, $item_type, $room_number) {
            if ($item['type'] == $item_type && $item['id'] == $id && Arr::get($item, 'room_number', null) == $room_number) {
                return $item;
            }
        });

        if ($quantity > Arr::get($item, 'max', 0) && $item['type'] == 'amenity') {
            $this->toast('Update Failed', 'warning', 'Remaining stock of ' . ucwords($item['name']) . ' is ' . $item['max'] . '.');
            return;
        }

        $this->items = $this->items->map(function ($item) use ($id, $quantity, $item_type, $room_number) {
            if ($item['type'] == $item_type && $item['id'] == $id && Arr::get($item, 'room_number', null) == $room_number) {
                $item['quantity'] = $quantity;
            }
            return $item;
        });
        $service = null;
        
        switch ($item_type) {
            case 'amenity':
                $service = new AmenityService;
                break;
            case 'others':
                $service = new AdditionalServiceHandler;
                break;
        }
        // Update the database
        if (!empty($this->invoice)) {
            $items = $this->items->filter(function ($item) use ($id, $item_type){
                if ($item['type'] == $item_type && $item['id']) {
                    return $item;
                }
            });

            if ($item_type == 'amenity') {
                $_amenity = Amenity::find($item['id']);

                if ($_amenity->status == AmenityStatus::INACTIVE->value) {
                    $this->toast('Update Amenity Failed!', 'warning', 'The amenity you are trying to update is disabled!');
                    return;
                }

                $service->sync($this->invoice->reservation, $items);
            } else {
                foreach ($items as $item) {
                    $this->invoice->items()->updateOrCreate([
                        'id' => $item['id']
                    ],[
                        'name' => $item['name'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }

        // Update invoice
        $billing = new BillingService;
        $taxes = $billing->taxes($this->invoice->reservation->fresh());
        $payments = $this->invoice->payments->sum('amount');

        $this->invoice->sub_total = $taxes['sub_total'];
        $this->invoice->total_amount = $taxes['net_total'];
        $this->invoice->balance = $taxes['net_total'] - $payments;
        $this->invoice->save();

        $this->getTaxes();
        $this->reset('quantity');
        $this->toast('Success!', description: 'Updated quantity of ' . ucwords($item['name'] . '!'));
    }

    public function selectRoom() {
        $amenity = $this->amenity;
        $room_number = $this->room_number;

        if ($this->items->contains(function ($item) use ($room_number, $amenity) {
            return Arr::get($item, 'room_number', null) == $room_number && $item['id'] == $amenity;
        })) {
            $this->reset('amenity', 'max', 'quantity', 'price');
        }        
    }

    public function render()
    {
        return view('livewire.app.invoice.add-item');
    }
}
