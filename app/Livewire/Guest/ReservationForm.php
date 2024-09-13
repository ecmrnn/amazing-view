<?php

namespace App\Livewire\Guest;

use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ReservationForm extends Component
{
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

    // Operational Variables
    public $can_select_a_room = false;
    public $room_types;

    public function mount() {
        $this->room_types = RoomType::all();
        $this->reservable_amenities = Amenity::where('is_reservable', 1)->get();

        $this->selected_rooms = new Collection;
        $this->selected_amenities = new Collection;
    }

    // Custome Validation Messages
    public function messages() 
    {
        return [
            'selected_rooms.required' => 'Atleast 1 :attribute is required.',
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
        ];
    }

    public function addRoom(Room $room) {
        if ($room && !$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);
            $this->capacity += $room->max_capacity;
        }
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
        
        // Retrieve available rooms for the selected date
        // ...

        // Turn can_select_a_room to 'true'
        $this->can_select_a_room = true;
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

    public function toggleAmenity(Amenity $amenity_clicked) {
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

    public function submit()
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
            default:
                # code...
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
        return view('livewire.guest.reservation-form');
    }
}
