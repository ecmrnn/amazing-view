<?php

namespace App\Livewire\Guest;

use App\Http\Controllers\AddressController;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Providers\AppServiceProvider;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class ReservationForm extends Component
{
    use WithFilePond;

    public $step = 1;
    public $capacity = 0;

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_amenities;
    public $available_rooms;
    public $suggested_rooms;
    public $reservable_amenities;
    public $room_type_name;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    // Address
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

    // Operational Variables
    public $can_select_a_room = false;
    public $can_submit_payment = false;
    public $can_select_address = false;
    public $room_types;
    public $sub_total = 0;
    public $vat = 0;
    public $net_total = 0;
    private $vat_percent = .12; /* Should be in Global */

    public function mount() {
        $this->reservable_amenities = Amenity::where('is_reservable', 1)->get();
        $this->selected_rooms = new Collection;
        $this->selected_amenities = new Collection;

        $this->regions = AddressController::getRegions();
        $this->districts = AddressController::getDistricts();
    }

    // Custome Validation Messages
    public function messages() 
    {
        return [
            'selected_rooms.required' => 'Atleast 1 :attribute is required.',
            'proof_image_path.required' => 'Upload your proof of payment here.',
            'proof_image_path.mimes' => 'File must be a valid image format (JPG, JPEG, PNG).',
            'proof_image_path.max' => 'Maximum file size is 1MB (1024KB).',
        ];
    }

    // Validation Methods
    public function rules()
    {
        return [
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
            'proof_image_path' => 'required|mimes:jpg,jpeg,png|file|max:1000',
        ];
    }

    public function validationAttributes()
    {
        return [
            'date_in' => 'check-in date',
            'date_out' => 'check-out date',
            'adult_count' => 'adult',
            'children_count' => 'children',
            'selected_rooms' => 'room',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone' => 'contact number',
            'proof_image_path' => 'proof of payment',
        ];
    }

    public function addRoom(Room $room) {
        if ($room && !$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);
            $this->capacity += $room->max_capacity;
            $this->sub_total += $room->rate;
            $this->vat = ($this->vat_percent * $this->sub_total);
            $this->net_total = $this->sub_total + $this->vat;
        }
    }

    public function removeRoom(Room $room_to_delete) {
        $this->capacity -= $room_to_delete->max_capacity;
        $this->sub_total -= $room_to_delete->rate;
        $this->vat = ($this->vat_percent * $this->sub_total);
        $this->net_total = $this->sub_total + $this->vat;
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
        
        // Retrieve available rooms for the selected date
        // ...

        // Turn can_select_a_room to 'true'
        $this->can_select_a_room = true;
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
        $this->suggested_rooms = Room::where('status', 0)->get();
    }

    // Populate 'available_rooms' property
    public function getAvailableRooms(RoomType $roomType) {
        // Query the available rooms for a specific room type
        $this->available_rooms = $roomType->rooms()->where('status', 0)->get();

        // Set the name for the selected room type
        $this->room_type_name = $roomType->name;
    }

    // Selects and Deselect Amenity
    public function toggleAmenity(Amenity $amenity_clicked) {
        // If: the amenity is already selected, remove it from the 'selected_amenities'
        // Else: push it to the 'selected_amenities'
        if ($this->selected_amenities->contains('id', $amenity_clicked->id)) {
            $this->selected_amenities = $this->selected_amenities->reject(function ($amenity) use ($amenity_clicked) {
                return $amenity->id == $amenity_clicked->id;
                $this->sub_total -= $amenity_clicked->price;
            });
        } else {
            $this->selected_amenities->push($amenity_clicked);
            $this->sub_total += $amenity_clicked->price;
        } 
        $this->vat = ($this->vat_percent * $this->sub_total);
        $this->net_total = $this->sub_total + $this->vat;
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
                break;
            case 2:
                $this->validate([
                    'first_name' => $this->rules()['first_name'],
                    'last_name' => $this->rules()['last_name'],
                    'email' => $this->rules()['email'],
                    'phone' => $this->rules()['phone'],
                    'address' => $this->rules()['address'],
                ]);
                break;
            case 3:
                $this->validate([
                    'proof_image_path' => $this->rules()['proof_image_path']
                ]);
                break;
            default:
                break;
        }

        // Proceed to next step
        // Dispatch an event that will be received by Alpine
        // Check: reservation-form
        $this->step++;
        if ($this->step != 3) {
            $this->dispatch('next-step', $this->step);
        }
    }

    public function render()
    {
        $this->room_types = RoomType::withCount(['rooms' => function ($query) {
            $query->where('status', Room::STATUS_AVAILABLE);
        }])->get();

        return view('livewire.guest.reservation-form');
    }
}
