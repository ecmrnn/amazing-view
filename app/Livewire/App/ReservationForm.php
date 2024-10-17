<?php

namespace App\Livewire\App;

use App\Http\Controllers\AddressController;
use App\Models\Amenity;
use App\Models\Building;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\ReservationAmenity;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;
use Spatie\LivewireFilepond\WithFilePond;

class ReservationForm extends Component
{
    use WithFilePond;

    // Reservation Details
    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count = 1;
    #[Validate] public $children_count = 0;
    #[Validate] public $selected_rooms;
    #[Validate] public $selected_amenities;
    // Guest Details
    #[Validate] public $first_name;
    #[Validate] public $last_name;
    #[Validate] public $email;
    #[Validate] public $phone;
    #[Validate] public $address = [];
    // Payment
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
    public $is_map_view = true; /* Must be set to true */
    public $can_select_room = false; /* Must be set to false */
    public $can_enter_guest_details = false; /* Must be set to false */
    public $can_submit_payment = false; /* Must be set to false */
    public $can_add_amenity = false; /* Must be set to false */
    public $selected_type; 
    public $selected_building;
    public $additional_amenity;
    public $available_amenities;
    public $additional_amenity_total;
    public $additional_amenity_quantity = 1;
    public $additional_amenity_quantities;
    public $additional_amenity_id;
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
    public $addons;
    public $rooms;
    public $sub_total = 0;
    public $net_total = 0;
    public $vat = 0;
    public $vatable_sales = 0;
    public $payment_method = 'online';

    public function mount()
    {
        $this->min_date = date_format(Carbon::now(), 'Y-m-d');
        $this->selected_rooms = collect();
        $this->selected_amenities = collect();
        $this->additional_amenity_quantities = collect();

        $this->buildings = Building::all();
        $this->rooms = RoomType::all();
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

    // Address Get Methods
    public function getProvinces($region)
    {
        $this->provinces = AddressController::getProvinces($region);
        $this->setAddress();
    }

    public function getCities($province)
    {
        $this->cities = AddressController::getCities($province);
        $this->setAddress();
    }

    public function getBaranggays($city)
    {
        $this->baranggays = AddressController::getBaranggays($city);
        $this->setAddress();
    }

    public function getDistrictBaranggays($district)
    {
        $this->baranggays = AddressController::getDistrictBaranggays($district);
        $this->setAddress();
    }

    // Concatenates the address altogether
    public function setAddress()
    {
        empty($this->street) ? $this->address[0] = null : $this->address[0] = trim($this->street) . ', ';
        empty($this->baranggay) ? $this->address[1] = null : $this->address[1] = trim($this->baranggay) . ', ';
        empty($this->district) ? $this->address[2] = null : $this->address[2] = trim($this->district) . ', ';
        empty($this->city) ? $this->address[3] = null : $this->address[3] = trim($this->city) . ', ';
        empty($this->province) ? $this->address[4] = null : $this->address[4] = trim($this->province);
    }

    public function computeBreakdown()
    {
        $this->vatable_sales = $this->sub_total / 1.12;
        $this->vat = ($this->sub_total) - $this->vatable_sales;
        $this->net_total = $this->vatable_sales + $this->vat;
    }

    public function addAmenity() {
        $this->validate([
            'additional_amenity_quantity' => 'integer|min:1|required',
            'additional_amenity' => 'required',
        ]);

        $amenity = $this->additional_amenity;

        if ($this->additional_amenity_quantity <= $amenity->quantity) {
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
            $this->dispatch('toast', json_encode(['message' => 'Oof, not enough item!', 'type' => 'warning', 'description' => 'Item quantity is not enough']));
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

    public function selectAmenity($id) {
        if (!empty($id)) {
            $this->additional_amenity_id = $id;
            $this->additional_amenity = Amenity::find($id);
            $this->getTotal();
        }
    }

    public function getTotal() {
        if ($this->additional_amenity_id && $this->additional_amenity_quantity) {
            $this->additional_amenity_total = $this->additional_amenity->price * $this->additional_amenity_quantity;
        }
    }

    public function selectBuilding(Building $id)
    {
        $this->floor_number = 1;
        $this->selected_building = $id;
        $this->floor_count = $this->selected_building->floor_count;
        $this->column_count = $this->selected_building->room_col_count;

        // Get the rooms in the building
        $this->available_rooms = Room::where('building_id', $this->selected_building->id)
            ->where('floor_number', $this->floor_number)
            ->get();
    }

    public function selectedRoom()
    {
        $this->validate([
            'selected_rooms' => $this->rules()['selected_rooms'],
        ]);

        $this->can_select_room = false;
        $this->can_add_amenity = true;
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

    public function guestDetails() {
        $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
        ]);

        $this->can_enter_guest_details = true;

        if (empty($this->regions) || empty($this->districts)) {
            $this->regions = AddressController::getRegions();
            $this->districts = AddressController::getDistricts();
        }
    }

    public function selectRoom()
    {
        $this->validate([
            'date_in' => $this->rules()['date_in'],
            'date_out' => $this->rules()['date_out'],
            'adult_count' => $this->rules()['adult_count'],
            'children_count' => $this->rules()['children_count'],
            'first_name' => $this->rules()['first_name'],
            'last_name' => $this->rules()['last_name'],
            'email' => $this->rules()['email'],
            'phone' => $this->rules()['phone'],
            'address' => $this->rules()['address'],
        ]);
        
        $this->can_select_room = true;
        if ($this->buildings->count() > 0 || $this->rooms->count() > 0) {
            $this->buildings = Building::all();
            $this->rooms = RoomType::all();
        }

        $this->addons = Amenity::where('is_addons', 1)->get();
        $this->available_amenities = Amenity::where('quantity', '>', 0)->orderBy('name')->get();

        // Get the number of nights between 'date_in' and 'date_out'
        $this->night_count = Carbon::parse($this->date_in)->diffInDays(Carbon::parse($this->date_out));

        // If 'date_in' == 'date_out', 'night_count' = 1
        $this->night_count != 0 ?: $this->night_count = 1;

        // Get all the reserved rooms
        $this->reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id')->toArray();

        // Recomputes the total amount due
        $this->vatable_sales = 0;
        $this->sub_total = 0;
        $this->vat = 0;
        $this->net_total = 0;
        foreach ($this->selected_rooms as $room) {
            $this->sub_total += ($room->rate * $this->night_count);
        }
        foreach ($this->selected_amenities as $amenity) {
            $this->sub_total += $amenity->price;
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

    public function removeRoom(Room $room) {
        $this->toggleRoom($room);
    }

    public function submit()
    {
        $this->validate([
            'proof_image_path' => $this->rules()['proof_image_path'],
            'cash_payment' => $this->rules()['cash_payment'],
        ]);

        // Open success modal
        $this->dispatch('open-modal', 'show-reservation-confirmation');
    }

    public function store() {
        $reservation = Reservation::create([
            'date_in' => $this->date_in,
            'date_out' => $this->date_out,
            'adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
            'status' => Reservation::STATUS_PENDING,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'address' => trim(implode($this->address), ', '),
            'email' => $this->email,
        ]);

        if (!empty($this->selected_rooms)) {
            // Store rooms
            foreach ($this->selected_rooms as $room) {
                $room->reservations()->attach($reservation->id);
            }
        }

        if (!empty($this->selected_amenities)) {
            // Store amenities
            foreach ($this->selected_amenities as $amenity) {
                $quantity = 0;

                foreach ($this->additional_amenity_quantities as $selected_amenity) {
                    if ($selected_amenity['amenity_id'] == $amenity->id) {
                        $amenity->quantity -= $selected_amenity['quantity'];
                        $quantity = $selected_amenity['quantity'];
                        $amenity->save();
                        break;
                    }
                }
                
                $reservation->amenities()->attach($amenity->id, ['quantity' => $quantity]);
            }
        }

        // Reset all properties
        $this->reset();

        $this->min_date = date_format(Carbon::now(), 'Y-m-d');
        $this->selected_rooms = collect();
        $this->selected_amenities = collect();
        $this->additional_amenity_quantities = collect();
    }

    public function render()
    {

        return view('livewire.app.reservation-form', [
            'buildings' => $this->buildings,
            'addons' => $this->addons,
            'rooms' => $this->rooms,
        ]);
    }
}
