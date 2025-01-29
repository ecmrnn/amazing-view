<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Http\Controllers\AddressController;
use App\Models\Amenity;
use App\Models\Building;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\ReservationAmenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;
use Spatie\LivewireFilepond\WithFilePond;

class EditReservation extends Component
{
    use WithFilePond, DispatchesToast;

    protected $listeners = ['reservation-details-updated' => '$refresh'];

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $senior_count = 0;
    #[Validate] public $pwd_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_amenities;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address;
    // Payment
    #[Validate] public $note;
    #[Validate] public $proof_image_path;
    #[Validate] public $cash_payment = 500;
    // Address
    public $region;
    public $province;
    public $city;
    public $district;
    public $baranggay;
    public $street;
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $baranggays = [];

    // Operations
    public $modal_key; /* Unique identifier for building modal */
    public $is_map_view = true; /* Must be set to true */
    public $selected_type; 
    public $selected_building;
    public $additional_amenity;
    public $available_amenities;
    public $additional_amenities;
    public $additional_amenity_total;
    public $additional_amenity_quantity = 1;
    public $additional_amenity_quantities;
    public $additional_amenity_id;
    public $available_room_types;
    public $available_rooms;
    public $reserved_rooms;
    public $capacity;
    public $min_date;
    public Building $building;
    public $buildings;
    public $floor_number = 1;
    public $floor_count = 1;
    public $column_count = 1;
    public $night_count = 1;
    public $addons;
    public $rooms;
    public $sub_total = 0;
    public $net_total = 0;
    public $vat = 0;
    public $vatable_sales = 0;
    public $payment_method = 'online';
    public $reservation;

    public function mount(Reservation $reservation = null)
    {
        $this->reservation = $reservation;
        $this->min_date = Carbon::now()->format('Y-m-d');
        
        $this->selected_rooms = collect();
        $this->selected_amenities = $reservation->amenities;
        $this->additional_amenities = collect();
        $this->additional_amenity_quantities = collect();
        $this->available_rooms = collect();

        $this->setProperties();

        $this->buildings = Building::with('rooms')->withCount('rooms')->get();
        $this->rooms = RoomType::with('rooms')->get();
    }

    public function rules()
    {
        return Reservation::rules();
    }

    public function messages() 
    {
        return Reservation::messages();
    }
 
    public function validationAttributes()
    {
        return Reservation::validationAttributes();
    }

    public function setProperties() {
        // Reservation Details
        $this->date_in = $this->reservation->date_in;
        $this->date_out = $this->reservation->date_out;
        $this->adult_count = $this->reservation->adult_count;
        $this->children_count = $this->reservation->children_count;
        $this->senior_count = $this->reservation->senior_count;
        $this->pwd_count = $this->reservation->pwd_count;
        // Guest Details
        $this->first_name = $this->reservation->first_name;
        $this->last_name = $this->reservation->last_name;
        $this->phone = $this->reservation->phone;
        $this->email = $this->reservation->email;
        $this->address = $this->reservation->address;
        // Payment
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

    #[On('select-building')]
    public function selectBuilding($data)
    {
        $this->modal_key = uniqid();
        $this->selected_rooms = collect();
        
        if ($data['selected_rooms']) {
            foreach ($data['selected_rooms'] as $room) {
                $room = Room::find($room);
    
                if ($room) {
                    $this->toggleRoom($room);
                }
            }
        } 

        $this->date_in = $data['date_in'];
        $this->date_out = $data['date_out'];
        $this->floor_number = 1;
        $this->selected_building = Building::where('id', $data['building'])->first();
        $this->floor_count = $this->selected_building->floor_count;
        $this->column_count = $this->selected_building->room_col_count;

        $this->reserved_rooms = Room::whereHas('reservations', function ($query) {
            return $query->where('reservations.id', '!=', $this->reservation->id)
                ->where('date_in', '<=', $this->date_out)
                ->where('date_out', '>=', $this->date_in)
                ->whereIn('status', [ReservationStatus::AWAITING_PAYMENT->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value]);
        })->pluck('id')->toArray();

        // Get the rooms in the building
        $this->available_rooms = Room::where('building_id', $this->selected_building->id)
            ->where('floor_number', $this->floor_number)
            ->get();

        $this->dispatch('open-modal', 'show-building-rooms');
        // $this->dispatch('$refresh');
    }

    public function computeBreakdown()
    {
        $this->vatable_sales = $this->sub_total / 1.12;
        $this->vat = ($this->sub_total) - $this->vatable_sales;
        $this->net_total = $this->vatable_sales + $this->vat;
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
            $this->toast('Oof, not enough item', 'warning', 'Item quantity is not enough');
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

    public function upFloor()
    {
        if ($this->floor_number < $this->selected_building->floor_count) {
            $this->floor_number++;

            // Get the rooms in the building
            $this->available_rooms = Room::where('building_id', $this->selected_building->id)
                ->where('floor_number', $this->floor_number)
                ->get();
        }
    }

    public function downFloor()
    {
        if ($this->floor_number > 1) {
            $this->floor_number--;

            // Get the rooms in the building
            $this->available_rooms = Room::where('building_id', $this->selected_building->id)
                ->where('floor_number', $this->floor_number)
                ->get();
        }
    }

    public function toggleAmenity(Amenity $amenity_clicked)
    {
        // If: the amenity is already selected, remove it from the 'selected_amenities'
        // Else: push it to the 'selected_amenities'
        if ($this->selected_amenities->contains('id', $amenity_clicked->id)) {
            $this->selected_amenities = $this->selected_amenities->reject(function ($amenity) use ($amenity_clicked) {
                return $amenity->id == $amenity_clicked->id;
            });
            $this->sub_total -= $amenity_clicked->price;
        } else {
            $this->selected_amenities->push($amenity_clicked);
            $this->sub_total += $amenity_clicked->price;
        }

        $this->computeBreakdown();
    }

    #[On('add-room')]
    public function toggleRoom(Room $room)
    {
        if ($room && !$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);

            $this->capacity += $room->max_capacity;
            $this->sub_total += ($room->rate * $this->night_count);
        } else {
            $this->capacity -= $room->max_capacity;

            $this->sub_total -= ($room->rate * $this->night_count);

            $this->selected_rooms = $this->selected_rooms->reject(function ($room_loc) use ($room) {
                return $room_loc->id == $room->id;
            });
        }

        $this->computeBreakdown();
    }

    #[On('view-rooms')]
    public function viewRooms($data) {
        $this->selected_type = RoomType::find($data['room_type']);
        $this->date_in = $data['date_in'];
        $this->date_out = $data['date_out'];
        $this->selected_rooms = collect();

        foreach ($data['selected_rooms'] as $room) {
            $room = Room::find($room);
            
            if ($room) {
                $this->toggleRoom($room);
            }
        }
        
        $this->reserved_rooms = Room::whereHas('reservations', function ($query) {
            return $query->where('reservations.id', '!=', $this->reservation->id)
                ->where('date_in', '<=', $this->date_out)
                ->where('date_out', '>=', $this->date_in)
                ->whereIn('status', [ReservationStatus::AWAITING_PAYMENT->value, ReservationStatus::PENDING->value, ReservationStatus::CONFIRMED->value]);
        })->pluck('id')->toArray();
        
        $room_by_capacity = Room::whereNotIn('id', $this->reserved_rooms)
            ->where('room_type_id', $this->selected_type->id)
            ->orderBy('max_capacity')
            ->get()
            ->toBase();

        $this->available_room_types = $room_by_capacity->groupBy('max_capacity');
        $this->dispatch('open-modal', 'show-typed-rooms');
    }

    public function addRoom($room_ids) {
        // Loops through all the room ids
        foreach ($room_ids as $room_id) {
            // Check if the room is not yet selected
            if (!$this->selected_rooms->contains('id', $room_id)) {
                $room = Room::find($room_id);
                $this->toggleRoom($room);
                break;
            }
        }
        $this->dispatch('add-rooms', $room_ids);
    }

    #[On('update-guests')]
    public function updateGuests($data) {
        $this->adult_count = $data['adult_count'];
        $this->children_count = $data['children_count'];

        $this->dispatch('open-modal', 'show-discounts-modal');
    }

    public function applyDiscount() {
        $this->validate([
            'senior_count' => 'nullable|lte:adult_count|integer',
            'pwd_count' => 'nullable|integer',
        ]);

        if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
            $this->addError('pwd_count', 'Total Seniors and PWDs cannot exceed total guests');
            return;
        }

        $this->dispatch('apply-discount', [
            'senior_count' => $this->senior_count,
            'pwd_count' => $this->pwd_count,
        ]);

        $this->toast('Success!', description: 'ok!');
    }

    public function update() {
        $validated = $this->validate([
            'date_in' => 'required|date|after_or_equal:today',
            'date_out' => Reservation::rules()['date_out'],
            'adult_count' => Reservation::rules()['adult_count'],
            'children_count' => Reservation::rules()['children_count'],
            'selected_rooms' => Reservation::rules()['selected_rooms'],
            'first_name' => Reservation::rules()['first_name'],
            'last_name' => Reservation::rules()['last_name'],
            'email' => Reservation::rules()['email'],
            'phone' => Reservation::rules()['phone'],
            'address' => Reservation::rules()['address'],
            'note' => Reservation::rules()['note'],
        ]);

        $validated['selected_rooms'] = $this->selected_rooms;
        $validated['selected_amenities'] = $this->additional_amenities;
        dd($this->additional_amenities);
        
        $service = new ReservationService();
        $service->update($this->reservation, $validated);

        $this->toast('Success!', 'success', 'Yay, reservation updated!');
    }

    public function render()
    {
        $this->available_amenities = Amenity::where('quantity', '>', 0)->orderBy('name')->get();
        $this->addons = Amenity::where('is_addons', 1)->get();

        return view('livewire.app.reservation.edit-reservation', [
            'buildings' => $this->buildings,
            'addons' => $this->addons,
            'rooms' => $this->rooms,
            'selected_rooms' => $this->selected_rooms
        ]);
    }
}
