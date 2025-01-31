<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\AdditionalServices;
use App\Models\Amenity;
use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\AdditionalServiceHandler;
use App\Services\AmenityService;
use App\Services\CarService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use Spatie\LivewireFilepond\WithFilePond;

class EditReservation extends Component
{
    use WithFilePond, DispatchesToast;

    protected $listeners = [
        'reservation-details-updated' => '$refresh',
        'reservation-canceled' => '$refresh',
    ];

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $senior_count = 0;
    #[Validate] public $pwd_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_services;
    #[Validate] public $selected_amenities;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address;
    #[Validate] public $cars;
    public $plate_number; 
    public $make; 
    public $model; 
    public $color; 
    // Payment
    #[Validate] public $note;
    #[Validate] public $proof_image_path;
    #[Validate] public $cash_payment = 500;
    // Canceled Reservation
    public $canceled_reservation;
    // Operations
    public $modal_key; /* Unique identifier for building modal */
    public $is_map_view = true; /* Must be set to true */
    public $selected_type; 
    public $selected_building;
    public $available_amenities;
    public $amenity;
    public $quantity = 0;
    public $max_quantity = 0;
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
    public $services;
    public $rooms;
    public $reservation;

    public function mount(Reservation $reservation = null)
    {
        $this->reservation = $reservation;
        $this->min_date = Carbon::now()->format('Y-m-d');
        
        $this->selected_rooms = collect();
        $this->selected_services = $reservation->services;
        $this->selected_amenities = collect();
        $this->available_rooms = collect();

        $this->setProperties();

        $this->buildings = Building::with('rooms')->withCount('rooms')->get();
        $this->rooms = RoomType::with('rooms')->get();

        if (!empty($reservation->canceled_at)) {
            $this->canceled_reservation = $reservation->cancelled;
        }
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
        $this->cars = collect();

        if (!empty($this->reservation->cars)) {
            foreach ($this->reservation->cars as $car) {
                $this->cars->push([
                    'plate_number' => $car->plate_number,
                    'make' => $car->make,
                    'model' => $car->model,
                    'color' => $car->color,
                ]);
            }   
        }
        
        if (!empty($this->reservation->amenities)) {
            foreach ($this->reservation->amenities as $amenity) {
                $this->selected_amenities->push([
                    'id' => $amenity->id,
                    'name' => $amenity->name,
                    'quantity' => $amenity->pivot->quantity,
                    'price' => $amenity->pivot->price,
                ]);
            }
        }
    }
    
    #[On('reservation-canceled')]
    public function reservationCanceled() {
        $this->canceled_reservation = $this->reservation->cancelled;
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

    public function selectAmenity() {
        $amenity = Amenity::find($this->amenity);

        if ($amenity) {
            $this->max_quantity = $amenity->quantity;
        } else {
            $this->max_quantity = 0;
            $this->quantity = 0;
        }
    }

    public function addCar() {
        $validated = $this->validate([
            'plate_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
        ]);

        $car_service = new CarService;
        $this->cars = $car_service->add($this->cars, $validated);

        if ($this->cars) {
            $this->reset('plate_number', 'make', 'model', 'color');
            $this->toast('Success!', description: 'Car added successfully!');
            $this->dispatch('car-added');
        } else {
            $this->toast('Car Exists!', 'warning', 'Plate number ' . $this->plate_number . ' already exists.');
        }
    }

    public function removeCar($plate_number) {
        $car_service = new CarService;
        $this->cars = $car_service->remove($this->cars, $plate_number);

        $this->toast('Success!', description: 'Car removed successfully!');
        $this->dispatch('car-removed');
    }

    public function addAmenity() {
        $this->validate([
            'amenity' => 'required',
            'quantity' => 'required|lte:max_quantity',
        ]);

        $amenity = Amenity::find($this->amenity);

        $service = new AmenityService;
        $service->add($this->selected_amenities, $amenity, $this->quantity);

        $this->reset('amenity', 'quantity', 'max_quantity');
        $this->dispatch('amenity-added');
        $this->toast('Success!', description: 'Amenity added successfully!');
    }

    public function removeAmenity(Amenity $amenity) {
        $service = new AmenityService;
        $this->selected_amenities = $service->remove($this->selected_amenities, $amenity);

        $this->dispatch('amenity-removed');
        $this->toast('Amenity Removed', 'info', ucwords($amenity->name) . ' is removed successfully!');
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

    public function toggleService(AdditionalServices $service)
    {
        $handler = new AdditionalServiceHandler;
        $this->selected_services = $handler->add($this->selected_services, $service);
    }

    #[On('add-room')]
    public function toggleRoom(Room $room)
    {
        if ($room && !$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);

            $this->capacity += $room->max_capacity;
        } else {
            $this->capacity -= $room->max_capacity;

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

        $this->toast('Success', description: 'Senior and PWDs updated successfully!');
    }

    public function update() {
        $validated = $this->validate([
            'date_in' => Reservation::rules()['date_in'],
            'date_out' => Reservation::rules()['date_out'],
            'adult_count' => Reservation::rules()['adult_count'],
            'children_count' => Reservation::rules()['children_count'],
            'first_name' => Reservation::rules()['first_name'],
            'last_name' => Reservation::rules()['last_name'],
            'email' => Reservation::rules()['email'],
            'phone' => Reservation::rules()['phone'],
            'address' => Reservation::rules()['address'],
            'note' => Reservation::rules()['note'],
        ]);

        $validated['selected_services'] = $this->selected_services;
        $validated['selected_amenities'] = $this->selected_amenities;
        $validated['cars'] = $this->cars;
        
        $service = new ReservationService();
        $service->update($this->reservation, $validated);

        $this->toast('Success!', 'success', 'Yay, reservation updated!');
    }

    public function render()
    {
        $this->available_amenities = Amenity::where('quantity', '>', 0)->orderBy('name')->get();
        $this->services = AdditionalServices::where('is_active', true)->get();

        return view('livewire.app.reservation.edit-reservation', [
            'buildings' => $this->buildings,
            'services' => $this->services,
            'rooms' => $this->rooms,
            'selected_rooms' => $this->selected_rooms
        ]);
    }
}
