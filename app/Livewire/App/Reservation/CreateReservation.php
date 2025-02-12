<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Http\Controllers\AddressController;
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
use Spatie\LivewireFilepond\WithFilePond;

class CreateReservation extends Component
{
    use WithFilePond, DispatchesToast;

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $senior_count = 0;
    #[Validate] public $pwd_count = 0;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_amenities;
    #[Validate] public $reservation_type = 'walk-in-reservation';
    #[Validate] public $amenity;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address = [];
    #[Validate] public $cars;
    // Payment
    #[Validate] public $note;
    #[Validate] public $proof_image_path;
    // Invoice
    #[Validate] public $transaction_id;
    #[Validate] public $downpayment = 0;
    public $payment_method = 'cash';
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
    public $modal_key;
    public $is_map_view = true; /* Must be set to true */
    public $can_select_room = false; /* Must be set to false */
    public $guest_found = false;
    public $selected_type;
    public $max_senior_count;
    public $selected_building;
    public $quantity = 0;
    public $max_quantity = 0;
    public $available_amenities;
    public $available_room_types;
    public $available_rooms;
    public $reserved_rooms;
    public $capacity;
    public $min_date;
    public $buildings;
    public $floor_number = 1;
    public $floor_count = 1;
    public $column_count = 1;
    public $night_count = 1;
    public $rooms;
    public $net_total = 0;
    public $vat = 0;
    public $vatable_sales = 0;
    public $payment_online = false;
    public $services;
    public $selected_services;
    public $plate_number; 
    public $make; 
    public $model; 
    public $color; 

    public function mount()
    {
        $this->min_date = Carbon::now()->format('Y-m-d');
        $this->selected_rooms = collect();
        $this->selected_amenities = collect();
        $this->selected_services = collect();
        $this->cars = collect();

        $this->buildings = Building::all();
        $this->rooms = RoomType::all();
        $this->services = AdditionalServices::all();

        if (empty($this->regions) || empty($this->districts)) {
            try {
                $this->regions = AddressController::getRegions();
                $this->districts = AddressController::getDistricts();
            } catch (\Throwable $th) {
                logger($th);
                $this->regions = null;
                $this->districts = null;
            }
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

    public function toggleService(AdditionalServices $service)
    {
        $handler = new AdditionalServiceHandler;
        $this->selected_services = $handler->add($this->selected_services, $service);
    }

    // Address Get Methods
    public function getProvinces($region)
    {
        $this->provinces = AddressController::getProvinces($region);
    }

    public function getCities($province)
    {
        $this->cities = AddressController::getCities($province);
    }

    public function getBaranggays($city)
    {
        $this->baranggays = AddressController::getBaranggays($city);
    }

    public function getDistrictBaranggays($district)
    {
        $this->baranggays = AddressController::getDistrictBaranggays($district);
    }

    public function addAmenity() {
        $this->validate([
            'amenity' => 'required',
            'quantity' => 'required|lte:max_quantity|gt:0',
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

        if (!$this->cars->contains('plate_number', strtoupper($this->plate_number))) {
            $car_service = new CarService;
            $this->cars = $car_service->add($this->cars, $validated);
    
            if ($this->cars) {
                $this->reset('plate_number', 'make', 'model', 'color');
                $this->toast('Success!', description: 'Car added successfully!');
                $this->dispatch('car-added');
            }
        } else {
            $this->toast('Car Exists!', 'warning', 'Plate number ' . strtoupper($this->plate_number) . ' already exists.');
        }
    }

    public function removeCar($plate_number) {
        $car_service = new CarService;
        $this->cars = $car_service->remove($this->cars, $plate_number);

        $this->toast('Success!', description: 'Car removed successfully!');
        $this->dispatch('car-removed');
    }

    public function selectBuilding(Building $id)
    {
        logger($this->selected_rooms);
        $this->modal_key = uniqid();
        $this->floor_number = 1;
        $this->selected_building = $id;
        $this->floor_count = $this->selected_building->floor_count;
        $this->column_count = $this->selected_building->room_col_count;

        $this->reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id')->toArray();

        // Get the rooms in the building
        $this->available_rooms = Room::where('building_id', $this->selected_building->id)
            ->where('floor_number', $this->floor_number)
            ->get();

        $this->dispatch('open-modal', 'show-building-rooms');
    }

    public function selectedRoom()
    {
        $this->validate([
            'selected_rooms' => $this->rules()['selected_rooms'],
        ]);

        $this->can_select_room = false;
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
        } else {
            $this->selected_amenities->push($amenity_clicked);
        }
    }

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

        $this->dispatch('discount-applied');

        $this->toast('Success!', description: 'Senior and PWDs updated successfully!');
    }

    public function selectRoom()
    {
        $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
        ]);

        if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
            $this->addError('adult_count', 'Total Seniors and PWDs cannot exceed total guests');
            return;
        }
        if ($this->senior_count > $this->adult_count) {
            $this->addError('adult_count', 'Total seniors cannot exceed total adults');
            return;
        }

        $this->can_select_room = true;

        if ($this->buildings->count() > 0 || $this->rooms->count() > 0) {
            $this->buildings = Building::all();
            $this->rooms = RoomType::all();
        }
    }

    public function viewRooms(RoomType $roomType) {
        $this->selected_type = $roomType;
        
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');
        
        $room_by_capacity = Room::whereNotIn('id', $reserved_rooms)
            ->where('room_type_id', $roomType->id)
            ->orderBy('max_capacity')
            ->get()
            ->toBase();

        $this->available_room_types = $room_by_capacity->groupBy('max_capacity');
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
    }

    #[On('guest-found')]
    public function findGuest($guest_details) {
        $this->first_name = $guest_details['first_name'];
        $this->last_name = $guest_details['last_name'];
        $this->email = $guest_details['email'];
        $this->phone = $guest_details['phone'];
        $this->address = $guest_details['address'];
        $this->guest_found = true;
    }

    public function removeRoom(Room $room) {
        $this->toggleRoom($room);
    }

    public function resetReservation() {
        $this->toast('Success!', description: 'Reservation fields resets successfully!');
        $this->reset();
        $this->resetErrorBag();

        $this->min_date = Carbon::now()->format('Y-m-d');
        $this->selected_rooms = collect();
        $this->selected_amenities = collect();
        $this->selected_services = collect();
        $this->cars = collect();
        $this->buildings = Building::all();
        $this->rooms = RoomType::all();
        $this->services = AdditionalServices::all();
        
        if (empty($this->regions) || empty($this->districts)) {
            $this->regions = AddressController::getRegions();
            $this->districts = AddressController::getDistricts();
        }
    }

    public function submit()
    {
        if (is_array($this->address)) {
            $this->address = [
                'street' => $this->street,
                'baranggay' => $this->baranggay,
                'district' => $this->district,
                'city' => $this->city,
                'province' => $this->province,
            ];
            $this->address = array_filter($this->address);
        }

        $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'senior_count' => $this->rules()['senior_count'],
            'pwd_count' => $this->rules()['pwd_count'],
            'selected_rooms' => 'required',
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
            'proof_image_path' => 'nullable|mimes:jpg,jpeg,png|file|max:1000',
            'note' => $this->rules()['note'],
        ]);

        if ($this->downpayment > 0 && $this->downpayment < 500) {
            $this->addError('downpayment', 'The minimum amount for downpayment is 500.00');
            return;
        }

        if ($this->adult_count + $this->children_count > $this->capacity) {
            $this->addError('selected_rooms', 'The room capacity cannot accomodate the total number of guests');
            return;
        }

        // Open success modal
        $this->dispatch('open-modal', 'show-reservation-confirmation');
    }

    public function store() {
        $validated = $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'senior_count' => $this->rules()['senior_count'],
            'pwd_count' => $this->rules()['pwd_count'],
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
            'proof_image_path' => 'nullable|mimes:jpg,jpeg,png|file|max:1000',
            'note' => $this->rules()['note'],
        ]);

        $validated['downpayment'] = $this->downpayment;
        $validated['address'] = is_array($validated['address']) ? trim(implode(', ', $validated['address']), ',') : $validated['address'];
        $validated['selected_rooms'] = $this->selected_rooms;
        $validated['selected_services'] = $this->selected_services;
        $validated['selected_amenities'] = $this->selected_amenities;
        $validated['cars'] = $this->cars;

        $service = new ReservationService;
        $reservation = $service->create($validated);

        $this->resetReservation();

        $this->toast('Success!', description: 'Reservation created successfully!');
        $this->dispatch('reservation-created');
    }

    public function render()
    {
        $this->available_amenities = Amenity::where('quantity', '>', 0)->orderBy('name')->get();

        return view('livewire.app.reservation.create-reservation', [
            'buildings' => $this->buildings,
            'rooms' => $this->rooms,
        ]);
    }
}
