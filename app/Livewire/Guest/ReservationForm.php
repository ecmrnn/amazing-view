<?php

namespace App\Livewire\Guest;

use App\Enums\RoomStatus;
use App\Enums\ServiceStatus;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\DateController;
use App\Models\AdditionalServices;
use App\Models\Promo;
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
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use phpDocumentor\Reflection\Types\This;
use Spatie\LivewireFilepond\WithFilePond;

use function PHPUnit\Framework\fileExists;

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
    public $discount_attachment;
    public $promo_code;
    public $promo = null;

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
    public $max_date;
    public $today;

    public function mount() {
        $this->selected_rooms = collect();
        $this->selected_services = collect();
        $this->available_room_types = collect();
        $this->cars = collect();

        $this->today = DateController::today();
        $this->min_date_in = Carbon::parse($this->today)->addDay()->format('Y-m-d');
        $this->room_types = RoomType::all();
        $this->additional_services = AdditionalServices::where('status', ServiceStatus::ACTIVE)->get();
    }

    public function setMinDateOut($date_in) {
        $this->min_date_out = Carbon::parse($date_in)->addDay()->format('Y-m-d');
        $this->max_date = Carbon::parse($this->date_in)->addMonth()->format('Y-m-d');
    }

    public function resetReservation() {
        $this->reset();
        
        $this->selected_rooms = collect();
        $this->selected_services = collect();
        $this->available_room_types = collect();
        $this->cars = collect();
        $this->today = DateController::today();
        $this->min_date_in = Carbon::parse($this->today)->addDay()->format('Y-m-d');
        
        $this->room_types = RoomType::all();
        $this->additional_services = AdditionalServices::where('status', ServiceStatus::ACTIVE)->get();
        $this->dispatch('reservation-reset');
        sleep(2);
    }

    // Custome Validation Messages
    public function messages() 
    {
        return [
            'date_in.required' => 'Select a :attribute',
            'date_out.required_if' => 'Select a :attribute',
            'date_in.after_or_equal' => ':attribute must be after to today',
            'date_out.after_or_equal' => ':attribute must be after or equal to check-in date',
            
            'adult_count.required' => 'Enter number of :attribute',
            'adult_count.min' => 'Minimum number of :attribute is 1',
            'children_count.min' => 'Minimum number of :attribute is 0',

            'first_name.required' => 'Enter a :attribute',
            'first_name.min' => 'Minimum length of :attribute is 2',
            'first_name.regex' => 'Name can only contain letters, hyphens, and apostrophes',
            
            'last_name.required' => 'Enter a :attribute',
            'last_name.min' => 'Minimum length of :attribute is 2',
            'last_name.regex' => 'Name can only contain letters, hyphens, and apostrophes',

            'phone.required' => 'Enter a :attribute',
            'phone.min' => 'The length of :attribute must be 11',
            'phone.starts_with' => ':attribute must start with "09"',
            'address.required' => 'Enter your home address',
            
            'email.required' => 'Enter an :attribute',
            'email.email' => 'Enter a valid :attribute',

            'selected_rooms.required' => 'Select a room first',
            'discount_attachment.required' => 'Upload Senior or PWD ID for confirmation',
        ];
    }

    // Validation Methods
    public function rules()
    {
        return [
            'date_in' => 'required|date|after_or_equal:' . Carbon::parse($this->today)->addDay()->format('Y-m-d'),
            'date_out' => 'required_if:reservation_type,overnight|date|after_or_equal:date_in',
            'senior_count' => 'required|integer',
            'pwd_count' => 'required|integer',
            'adult_count' => 'required|integer|min:1',
            'children_count' => 'integer|min:0',
            'selected_rooms' => 'required',
            'first_name' => 'required|min:2|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\'\-\s]+$/u|max:255',
            'last_name' => 'required|min:2|string|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\'\-\s]+$/u|max:255',
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
        // Delete temporary uploaded file
        $file_exists = !$this->proof_image_path ? false : file_exists($this->proof_image_path->getRealPath());
        
        if ($file_exists) {
            unlink($this->proof_image_path->getRealPath());
        }
        $this->reset('proof_image_path');

        $file_exists = !$this->discount_attachment ? false : file_exists($this->discount_attachment->getRealPath());
        if ($file_exists) {
            unlink($this->discount_attachment->getRealPath());
        }
        $this->reset('discount_attachment');

        $this->step = $step;
        $this->toast('Uploaded files will be removed', 'info', 'Going back a previous step will remove all uploaded files');
    }

    public function applyDiscount() {
        $this->validate([
            'senior_count' => 'nullable|lte:adult_count|integer',
            'pwd_count' => 'nullable|integer',
        ]);

        $file_exists = !$this->discount_attachment ?: file_exists($this->discount_attachment->getRealPath());
        
        if ($file_exists) {
            $this->validate([
                'discount_attachment' => 'nullable|mimes:jpg,jpeg,png|file|max:5000',
            ]);
        } else {
            $this->discount_attachment = null;
        }

        if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
            $this->addError('pwd_count', 'Total Seniors and PWDs cannot exceed total guests');
            return false;
        }

        if (($this->senior_count > 0 || $this->pwd_count > 0) && !$this->discount_attachment) {
            $this->addError('discount_attachment', 'Upload Senior or PWD ID for confirmation');
            return false;
        }

        $this->dispatch('discount-applied');
        $this->toast('Success!', description: 'Senior and PWDs updated successfully!');
        return true;
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

        if ($this->reservation_type == 'overnight' && $this->date_in == $this->date_out) {
            $this->addError('date_out', 'Check-out date must be after check-in date');
            return;
        }

        if ($this->date_out > $this->max_date) {
            $this->addError('date_out', 'Maximum check-out date is one month after check-in date');
            return;
        }

        if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
            $this->addError('adult_count', 'Total Seniors and PWDs cannot exceed total guests');
            return;
        }
        if ($this->senior_count > $this->adult_count) {
            $this->addError('adult_count', 'Total seniors cannot exceed total adults');
            return;
        }

        // Get the room with the largest capacity
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id')->toArray();
        $max_guests = Room::whereNotIn('id', $reserved_rooms)->sum('max_capacity');
        
        if ($this->children_count + $this->adult_count > 30) {
            $this->addError('adult_count', 'Maximum number of guests are 30');
            return;
        }

        if ($this->children_count + $this->adult_count > $max_guests) {
            $this->addError('adult_count', 'Maximum number of guest are ' . $max_guests);
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
        if ($previous) {
            // Delete temporary uploaded file
            $file_exists = !$this->proof_image_path ? false : file_exists($this->proof_image_path->getRealPath());
            if ($file_exists) {
                unlink($this->proof_image_path->getRealPath());
            }
            $this->reset('proof_image_path');

            $file_exists = !$this->discount_attachment ? false : file_exists($this->discount_attachment->getRealPath());
            if ($file_exists) {
                unlink($this->discount_attachment->getRealPath());
            }
            $this->reset('discount_attachment');

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
                    $file_exists = !$this->proof_image_path ?: file_exists($this->proof_image_path->getRealPath());

                    if ($file_exists) {
                        $this->validate([
                            'proof_image_path' => $this->rules()['proof_image_path']
                        ]);
                    } else {
                        $this->proof_image_path = null;
                    }

                    if (!$this->applyDiscount()) {
                        $this->toast('Oops!', 'warning', 'Please upload Senior or PWD ID for confirmation');
                        return;
                    }
    
                    $this->dispatch('open-modal', 'show-reservation-confirmation');
                    break;
                default:
                    break;
            }
        }
        
        $this->resetErrorBag();
    }

    public function applyPromo() {
        $this->validate([
            'promo_code' => 'required|exists:promos,code',
        ]);

        $promo = Promo::whereCode($this->promo_code)->first();

        if ($promo) {
            if ($promo->isValid($promo)) {
                $this->promo = $promo;
                $this->toast('Success!', description: 'Promo code applied successfully!');
                $this->dispatch('discount-applied');
                return;
            }
            $this->addError('promo_code', 'Promo code has expired');
        }

        $this->addError('promo_code', 'Promo code is invalid');
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

        $validated['promo'] = $this->promo;
        $validated['selected_rooms'] = $this->selected_rooms;
        $validated['selected_services'] = $this->selected_services;
        $validated['cars'] = $this->cars;
        $validated['note'] = null;
        $validated['address'] = is_array($validated['address']) ? trim(implode(', ', $validated['address']), ',') : $validated['address'];
        $validated['discount_attachment'] = $this->discount_attachment;

        $service = new ReservationService();
        $reservation = $service->create($validated);

        if ($reservation) {
            $this->reservation_rid = $reservation->rid;
            $this->dispatch('reservation-created');
            $this->reset('can_select_a_room', 'can_select_address');
            $this->toast('Success!', description: 'Reservation sent!');
            $this->step++;
            return;
        }

        $this->step = 1;
        $this->addError('selected_rooms', 'One of the selected rooms is already reserved, select another room');
        $this->toast('Reservation Error!', 'warning', 'Failed to create reservation');
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
