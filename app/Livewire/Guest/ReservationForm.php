<?php

namespace App\Livewire\Guest;

use App\Enums\RoomStatus;
use App\Enums\ServiceStatus;
use App\Http\Controllers\AddressController;
use App\Models\AdditionalServices;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Services\AdditionalServiceHandler;
use App\Services\BillingService;
use App\Services\ReservationService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use phpDocumentor\Reflection\Types\This;
use Spatie\LivewireFilepond\WithFilePond;

class ReservationForm extends Component
{
    use WithFilePond, WithPagination, DispatchesToast;

    public $step = 1;
    public $capacity = 0;

    // Reservation Details
    public $min_date_in;
    public $min_date_out;
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $senior_count = 0;
    #[Validate] public $pwd_count = 0;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_services;
    public $suggested_rooms;
    public $additional_services = [];
    public $room_type_name;
    public $room_type_id;
    public $max_senior_count = 0;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address = []; /* Complete concatenated Address property */
    public $region;
    public $province;
    public $city;
    public $baranggay;
    public $street;
    public $district; 
    // Car Properties
    public $cars;
    public $plate_number; 
    public $make; 
    public $model; 
    public $color; 
    // Populated Address Arrays
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $baranggays = [];
    public $districts = [];
    // Payment
    #[Validate] public $proof_image_path;
    #[Validate] public $transaction_id;

    // Operational Variables
    public $reservation_type = null; /* Can be 'day tour' or 'overnight' */
    public $can_select_a_room = false;
    public $can_select_address = false;
    public $show_available_rooms = false;
    public $available_room_types;
    public $guest_found = false;
    public $room_types;
    public $selected_type;
    public $reservation_rid;
    public $night_count;
    public $breakdown;

    public function mount() {
        $this->selected_rooms = collect();
        $this->selected_services = collect();
        $this->available_room_types = collect();
        $this->cars = collect();
        $this->min_date_in = Carbon::now()->addDay()->format('Y-m-d');
        
        $this->room_types = RoomType::all();
        $this->additional_services = AdditionalServices::where('status', ServiceStatus::ACTIVE)->get();
    }

    public function setMinDateOut($date_in) {
        $this->min_date_out = Carbon::parse($date_in)->addDay()->format('Y-m-d');
    }

    public function resetReservation() {
        $this->reset();

        $this->selected_rooms = collect();
        $this->selected_services = collect();
        $this->available_room_types = collect();
        $this->cars = collect();
        $this->min_date_in = Carbon::now()->addDay()->format('Y-m-d');
        
        $this->room_types = RoomType::all();
        $this->additional_services = AdditionalServices::where('status', ServiceStatus::ACTIVE)->get();
        $this->dispatch('reservation-reset');
        sleep(2);
    }

    // Custome Validation Messages
    public function messages() 
    {
        return Reservation::messages(['downpayment', 'note']);
    }

    // Validation Methods
    public function rules()
    {
        return[
            'date_in' => 'required|date|after_or_equal:today',
            'date_out' => 'required_if:reservation_type,overnight|date|after_or_equal:date_in',
            'senior_count' => 'required|integer',
            'pwd_count' => 'required|integer',
            'adult_count' => 'required|integer|min:1',
            'children_count' => 'integer|min:0',
            'selected_rooms' => 'required',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|digits:11|starts_with:09',
            'address' => 'required',
            'proof_image_path' => 'nullable|mimes:jpg,jpeg,png|file|max:1000',
        ];
    }

    public function validationAttributes()
    {
        return Reservation::validationAttributes(['downpayment', 'note']);
    }

    public function goToStep($step) {
        $this->step = $step;
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

    public function viewRooms(RoomType $roomType) {
        $this->selected_type = $roomType;
        
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');
        
        $room_by_capacity = Room::whereNotIn('id', $reserved_rooms)
            ->where('room_type_id', $roomType->id)
            ->orderBy('max_capacity')
            ->get()
            ->toBase();

        $this->available_room_types = $room_by_capacity->groupBy('max_capacity');
        $this->dispatch('open-modal', 'show-typed-rooms');
    }

    public function addVehicle() {
        $this->validate([
            'plate_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required',
        ]);

        if (!$this->cars->contains('plate_number', strtoupper($this->plate_number))) {
            $this->cars->push(collect([
                'plate_number' => strtoupper($this->plate_number),
                'make' => ucwords(strtolower($this->make)),
                'model' => ucwords(strtolower($this->model)),
                'color' => ucwords(strtolower($this->color)),
            ]));

            $this->toast('Success!', 'success', 'Car added!');
            $this->reset('plate_number', 'make', 'model', 'color');
            $this->dispatch('car-added');
        } else {
            $this->toast('Oops!', 'warning', 'Car already added!');
        }
    }

    public function addRoom($room_ids) {
        // Loops through all the room ids
        foreach ($room_ids as $room_id) {
            // Check if the room is not yet selected
            if (!$this->selected_rooms->contains('id', $room_id)) {
                $room = Room::find($room_id);
                $this->toggleRoom($room);

                $this->toast('Success!', 'success', 'Room added!');
                break;
            }
        }
    }

    public function removeVehicle($plate_number) {
        $this->cars = $this->cars->reject(function ($car) use ($plate_number) {
            return $car['plate_number'] == $plate_number;
        });

        $this->toast('For Your Info.', 'info', 'Room removed');
    }

    public function removeRoom(Room $room_to_delete) {
        $this->capacity -= $room_to_delete->max_capacity;
        
        $this->selected_rooms = $this->selected_rooms->reject(function ($room) use ($room_to_delete) {
            return $room->id == $room_to_delete->id;
        });

        $this->toast('For Your Info.', 'info', 'Room removed');
    }

    // Will be called when customer finished filling out the following properties
    // - Date in and out
    // - Guest count
    public function selectRoom() {
        // Validate the following variables
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

        // Get the number of nights between 'date_in' and 'date_out'
        $this->night_count = Carbon::parse($this->date_in)->diffInDays(Carbon::parse($this->date_out));

        // If 'date_in' == 'date_out', 'night_count' = 1
        $this->night_count != 0 ?: $this->night_count = 1;

        // Turn can_select_a_room to 'true'
        $this->can_select_a_room = true;

        // Reset the suggested_rooms property
        $this->suggested_rooms = [];
    }

    public function additionalDetails() {
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

        // Validate the following variables
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
        ]);

        if ($this->guest_found) {
            $this->can_select_address = true;
            return;
        }
        
        // Check if guest already have an account
        $guest = User::where('email', $this->email)->first();

        if ($guest) {
            $this->dispatch('open-modal', 'show-guest-confirmation');
            return;
        }

        $this->can_select_address = true;
    }

    public function guestFound() {
        if (!$this->guest_found) {
            $guest = User::where('email', $this->email)->first();
    
            $this->first_name = $guest->first_name;
            $this->last_name = $guest->last_name;
            $this->email = $guest->email;
            $this->phone = $guest->phone;
            $this->address = $guest->address;
    
            $this->guest_found = true;
            $this->can_select_address = true;
            $this->dispatch('guest-found');
        }
    }

    public function viewRoom(Room $room) {
        $this->dispatch('open-modal', 'view-room-' . $room->id);
    }
    
    // Populate 'suggested_rooms' property
    public function suggestRooms() {
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');

        $this->suggested_rooms = Room::whereNotIn('id', $reserved_rooms)
                                    ->where('max_capacity', '>=', $this->adult_count + $this->children_count)
                                    ->orderBy('max_capacity')
                                    ->limit(3)
                                    ->get();
    }

    // Populate 'available_rooms' property
    public function getAvailableRooms(RoomType $room_type) {
        // Negate the value of the show_available_rooms
        $this->show_available_rooms = true;

        // Set the id for the selected room type
        $this->room_type_id = $room_type->id;

        // Set the name for the selected room type
        $this->room_type_name = $room_type->name;
    }

    // Selects and Deselect Amenity
    public function toggleService(AdditionalServices $service) {
        $handler = new AdditionalServiceHandler;
        $this->selected_services = $handler->add($this->selected_services, $service);
    }

    // Address Get Methods
    public function getProvinces($region) {
        $this->provinces = AddressController::getProvinces($region);
    }   

    public function getCities($province) {
        $this->cities = AddressController::getCities($province);
    }

    public function getBaranggays($city) {
        $this->baranggays = AddressController::getBaranggays($city);
    }

    public function getDistrictBaranggays($district) {
        $this->baranggays = AddressController::getDistrictBaranggays($district);
    }

    public function submit($previous = false)
    {
        // dd('hello');
        if ($previous) {
            $this->step -= 1;
        } else {
            // Validate input for each step/cases
            // 1: Reservation Details
            // 2: Guest Details
            // 3: Payment
            switch ($this->step) {
                case 1:
                    $this->validate([
                        'date_in' => $this->rules()['date_in'],
                        'date_out' => $this->rules()['date_out'],
                        'adult_count' => $this->rules()['adult_count'],
                        'children_count' => $this->rules()['children_count'],
                        'selected_rooms' => $this->rules()['selected_rooms'],
                    ]);

                    if ($this->capacity < $this->adult_count + $this->children_count) {
                        $this->toast('Oops!', 'warning', 'Selected rooms cannot accommodate the number of guests.');
                        $this->addError('selected_rooms', 'Selected rooms cannot accommodate the number of guests.');
                        return;
                    }

                    // Initialize guest if authorized (logged in)
                    if (Auth::check()) {
                        $this->first_name = Auth::user()->first_name;
                        $this->last_name = Auth::user()->last_name;
                        $this->email = Auth::user()->email;
                        $this->phone = Auth::user()->phone;
                        $this->address = Auth::user()->address;
                        $this->guest_found = true;
                    }

                    // Fetch regions and districts from from https://psgc.cloud
                    if (empty($this->regions)) {
                        try {
                            $this->regions = AddressController::getRegions();
                            $this->districts = AddressController::getDistricts();
                        } catch (\Throwable $th) {
                            $this->toast('Oh no', 'warning', 'Failed getting data from server');
                        }
                    }

                    $this->step++;
                    $this->toast('Success!', 'success', 'Next, Guest Details');
                    break;
                case 2:
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
                        'first_name' => $this->rules()['first_name'],
                        'last_name' => $this->rules()['last_name'],
                        'email' => $this->rules()['email'],
                        'phone' => $this->rules()['phone'],
                        'address' => $this->rules()['address'],
                    ]);

                    $items = collect();

                    foreach ($this->selected_rooms as $room) {
                        $items->push([
                            'price' => $room->rate,
                            'quantity' => $this->night_count,
                            'type' => 'room',
                        ]);
                    }

                    foreach ($this->selected_services as $service) {
                        $items->push([
                            'price' => $service->price,
                            'quantity' => 1,
                            'type' => 'service',
                        ]);
                    }

                    $billing = new BillingService;
                    $this->breakdown =  $billing->rawTaxes(null, $items);

                    $this->step++;
                    $this->toast('Success!', 'success', 'Next, Payment');
                    break;
                case 3:
                    $this->validate([
                        'proof_image_path' => $this->rules()['proof_image_path']
                    ]);
    
                    $this->dispatch('open-modal', 'show-reservation-confirmation');
                    break;
                default:
                    break;
            }
        }
        
        $this->resetErrorBag();
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
            'proof_image_path' => $this->rules()['proof_image_path'],
        ]);

        $validated['selected_rooms'] = $this->selected_rooms;
        $validated['selected_services'] = $this->selected_services;
        $validated['cars'] = $this->cars;
        $validated['note'] = null;
        $validated['address'] = is_array($validated['address']) ? trim(implode(', ', $validated['address']), ',') : $validated['address'];

        $service = new ReservationService();
        $reservation = $service->create($validated);

        // Dispatch event
        $this->reservation_rid = $reservation->rid;
        $this->dispatch('reservation-created');
        $this->reset('can_select_a_room', 'can_select_address');
        $this->toast('Success!', description: 'Reservation sent!');
        $this->step++;
    }

    public function updateReservationType() {
        $this->reset('date_in', 'date_out', 'reservation_type', 'can_select_a_room');
        $this->selected_rooms = collect();
        $this->toast('Change Reservation Type', 'info', 'Select your new reservation type');
        $this->resetErrorBag();
    }

    public function render()
    {
        $available_rooms = [];

        if ($this->show_available_rooms) {
            $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');

            $available_rooms = Room::whereNotIn('id', $reserved_rooms)
                                        ->where('room_type_id', $this->room_type_id)
                                        ->where('status', RoomStatus::AVAILABLE)
                                        ->paginate(10);
        }

        return view('livewire.guest.reservation-form', [
            'available_rooms' => $available_rooms
        ]);
    }
}
