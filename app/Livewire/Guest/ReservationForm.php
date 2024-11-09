<?php

namespace App\Livewire\Guest;

use App\Http\Controllers\AddressController;
use App\Models\Amenity;
use App\Models\Reservation;
use App\Models\ReservationAmenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\LivewireFilepond\WithFilePond;

class ReservationForm extends Component
{
    use WithFilePond, WithPagination, DispatchesToast;

    public $step = 1;
    public $capacity = 0;

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $senior_count = 0;
    #[Validate] public $pwd_count = 0;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_amenities;
    public $suggested_rooms;
    public $reservable_amenities = [];
    public $room_type_name;
    public $room_type_id;
    public $max_senior_count;
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
    public $can_select_a_room = false;
    public $can_submit_payment = false;
    public $can_select_address = false;
    public $show_available_rooms = false;
    public $available_room_types;
    public $room_types;
    public $selected_type;
    public $sub_total = 0;
    public $net_total = 0;
    public $vat = 0;
    public $vatable_sales = 0;
    public $reservation_rid;
    public $night_count;

    public function mount() {
        $this->selected_rooms = new Collection;
        $this->selected_amenities = new Collection;
        $this->available_room_types = new Collection;
        
        $this->room_types = RoomType::all();
        $this->reservable_amenities = Amenity::where('is_addons', 1)->get();
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
            'date_out' => 'required|date|after_or_equal:date_in',
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

    public function setMaxSeniorCount() {
        if ($this->pwd_count > 0) {
            $this->max_senior_count = $this->adult_count - $this->pwd_count + $this->children_count;
        } else {
            $this->max_senior_count = $this->adult_count - $this->pwd_count;
        }
    }

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

    public function viewRooms(RoomType $roomType) {
        $this->selected_type = $roomType;
        
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');
        
        $room_by_capacity = Room::whereNotIn('id', $reserved_rooms)
            ->where('room_type_id', $roomType->id)
            ->orderBy('max_capacity')
            ->get()
            ->toBase();

        $this->available_room_types = $room_by_capacity->groupBy('max_capacity');
        // dd($this->available_room_types);
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

    public function removeRoom(Room $room_to_delete) {
        $this->capacity -= $room_to_delete->max_capacity;

        $this->sub_total -= ($room_to_delete->rate * $this->night_count);
        
        $this->selected_rooms = $this->selected_rooms->reject(function ($room) use ($room_to_delete) {
            return $room->id == $room_to_delete->id;
        });

        $this->toast('For Your Info.', 'info', 'Room removed');
    }

    public function computeBreakdown() {
        $this->vatable_sales = $this->sub_total / 1.12;
        $this->vat = ($this->sub_total) - $this->vatable_sales;
        $this->net_total = $this->vatable_sales + $this->vat;
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

        // Get the number of nights between 'date_in' and 'date_out'
        $this->night_count = Carbon::parse($this->date_in)->diffInDays(Carbon::parse($this->date_out));

        // If 'date_in' == 'date_out', 'night_count' = 1
        $this->night_count != 0 ?: $this->night_count = 1;

        // Turn can_select_a_room to 'true'
        $this->can_select_a_room = true;

        // Reset the suggested_rooms property
        $this->suggested_rooms = [];
    }

    public function selectAddress() {
        // Validate the following variables
        $this->validate([
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
        ]);

        // Turn can_select_a_room to 'true'
        $this->can_select_address = true;
    }
    
    // Populate 'suggested_rooms' property
    public function suggestRooms() {
        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');

        $this->suggested_rooms = Room::whereNotIn('id', $reserved_rooms)
                                    ->where('status', Room::STATUS_AVAILABLE)
                                    ->where('max_capacity', '>=', $this->capacity)
                                    ->orderByDesc('rate')
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
    public function toggleAmenity(Amenity $amenity_clicked) {
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

    // Address Get Methods
    public function getProvinces($region) {
        $this->provinces = AddressController::getProvinces($region);
        $this->setAddress();
    }   

    public function getCities($province) {
        $this->cities = AddressController::getCities($province);
        $this->setAddress();
    }

    public function getBaranggays($city) {
        $this->baranggays = AddressController::getBaranggays($city);
        $this->setAddress();
    }

    public function getDistrictBaranggays($district) {
        $this->baranggays = AddressController::getDistrictBaranggays($district);
        $this->setAddress();
    }

    // Concatenates the address altogether
    public function setAddress() {
        empty($this->street) ? $this->address[0] = null: $this->address[0] = trim($this->street) . ', ';
        empty($this->baranggay) ? $this->address[1] = null: $this->address[1] = trim($this->baranggay) . ', ';
        empty($this->district) ? $this->address[2] = null: $this->address[2] = trim($this->district) . ', ';
        empty($this->city) ? $this->address[3] = null: $this->address[3] = trim($this->city) . ', ';
        empty($this->province) ? $this->address[4] = null: $this->address[4] = trim($this->province);
    }

    public function submit($previous = false)
    {
        if ($previous) {
            $this->step -= 1;
        } else {
            // Validate input for each step
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

                    $this->step++;

                    // Fetch regions and districts
                    try {
                        $this->regions = AddressController::getRegions();
                        $this->districts = AddressController::getDistricts();
                    } catch (\Throwable $th) {
                        $this->toast('Oh no', 'warning', 'Failed getting data from server');
                    }

                    $this->toast('Success!', 'success', 'Next, Guest Details');
                    break;
                case 2:
                    $this->validate([
                        'first_name' => $this->rules()['first_name'],
                        'last_name' => $this->rules()['last_name'],
                        'email' => $this->rules()['email'],
                        'phone' => $this->rules()['phone'],
                        'address' => $this->rules()['address'],
                    ]);

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
    }

    public function store() {
        $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'senior_count' => $this->rules()['adult_count'],
            'pwd_count' => $this->rules()['adult_count'],
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
            'selected_rooms' => $this->rules()['selected_rooms'],
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
        ]);

        // Create Reservation
        $reservation = Reservation::create([
            'date_in' => $this->date_in,
            'date_out' => $this->date_out,
            'senior_count' => $this->senior_count,
            'pwd_count' => $this->pwd_count,
            'adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
            'status' => Reservation::STATUS_PENDING,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'address' => trim(implode($this->address), ', '),
            'email' => $this->email,
        ]);

        $this->reservation_rid = $reservation->rid;

        if (!empty($this->selected_rooms)) {
            // Store rooms
            foreach ($this->selected_rooms as $room) {
                $room->reservations()->attach($reservation->id);
                $room->status = Room::STATUS_RESERVED;
                $room->save();
            }
        }

        if (!empty($this->selected_amenities)) {
            // Store amenities
            foreach ($this->selected_amenities as $amenity) {
                ReservationAmenity::create([
                    'reservation_id' => $reservation->id,
                    'amenity_id' => $amenity->id,
                    'quantity' => 0,
                ]);
            }
        }

        // Dispatch event
        $this->dispatch('reservation-created');
        $this->toast('Success!', 'success', 'Reservation sent!');
        $this->step++;
    }

    public function render()
    {
        $available_rooms = [];

        if ($this->show_available_rooms) {
            $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');

            $available_rooms = Room::whereNotIn('id', $reserved_rooms)
                                        ->where('room_type_id', $this->room_type_id)
                                        ->where('status', Room::STATUS_AVAILABLE)
                                        ->paginate(10);
        }

        return view('livewire.guest.reservation-form', [
            'available_rooms' => $available_rooms
        ]);
    }
}
