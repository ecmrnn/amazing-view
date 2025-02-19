<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomReservation;
use App\Models\RoomType;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditReservationDetails extends Component
{
    use DispatchesToast;

    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $adult_count;
    #[Validate] public $children_count;
    #[Validate] public $selected_rooms;
    public $senior_count;
    public $pwd_count;
    public $reservation;
    public $conflict_rooms;
    public $disable_date = false;
    
    public $rooms;
    public $buildings;
    public $floor_number = 1;
    public $floor_count = 1;
    public $column_count = 1;
    public $is_map_view = false;
    public $max_capacity = 0;

    public $min_date;
    public $step = 1;

    public function mount(Reservation $reservation)
    {
        $this->date_in = $reservation->resched_date_in == null ? $reservation->date_in : $reservation->resched_date_in;
        $this->date_out = $reservation->resched_date_out == null ? $reservation->date_out : $reservation->resched_date_out;
        $this->adult_count = $reservation->adult_count;
        $this->children_count = $reservation->children_count;
        $this->pwd_count = $reservation->pwd_count;
        $this->senior_count = $reservation->senior_count;
        $this->selected_rooms = $reservation->rooms;
        $this->reservation = $reservation;
        $this->rooms = collect();

        if (in_array($reservation->status, [
            ReservationStatus::AWAITING_PAYMENT->value,
            ReservationStatus::PENDING->value,
            ReservationStatus::CONFIRMED->value
        ])) {
            $this->min_date = Carbon::now()->format('Y-m-d');
        } else {
            $this->min_date = $reservation->date_in;
            $this->disable_date = true;
        }

        // Initialize max capacity
        foreach ($reservation->rooms as $room) {
            $this->max_capacity += $room->max_capacity;
        }
    }

    public function rules() {
        return [
            'date_in' => 'required|date|after_or_equal:min_date',
            'date_out' => 'required|date|after_or_equal:date_in',
        ];
    }

    public function messages() {
        return [
            'selected_rooms.required' => 'Please select a room first',
        ];
    }
    
    public function validateReservationDetails() {
        // Edit Reservation Details (Update Date Algo.)
        // 1. User change date-in or date-out
        // 2. Get all the reservations on the selected dates
        $reservations = Reservation::whereIn('status', [
            ReservationStatus::AWAITING_PAYMENT->value, 
            ReservationStatus::PENDING->value, 
            ReservationStatus::CONFIRMED->value
        ])
        ->where('id', '!=', $this->reservation->id) // Exclude the current reservation
        ->where(function ($query) {
            $query->where(function ($q) {
                // Case 1: Reservation is NOT rescheduled, use `date_in` and `date_out`
                $q->whereNull('resched_date_in')
                    ->whereNull('resched_date_out')
                    ->where(function ($subQuery) {
                        $subQuery->whereBetween('date_in', [$this->date_in, $this->date_out])
                                 ->orWhereBetween('date_out', [$this->date_in, $this->date_out])
                                 ->orWhere(function ($innerQuery) {
                                     $innerQuery->where('date_in', '<=', $this->date_in)
                                                ->where('date_out', '>=', $this->date_out);
                                 });
                    });
            })->orWhere(function ($q) {
                // Case 2: Reservation is rescheduled, use `resched_date_in` and `resched_date_out`
                $q->whereNotNull('resched_date_in')
                    ->whereNotNull('resched_date_out')
                    ->where(function ($subQuery) {
                        $subQuery->whereBetween('resched_date_in', [$this->date_in, $this->date_out])
                                 ->orWhereBetween('resched_date_out', [$this->date_in, $this->date_out])
                                 ->orWhere(function ($innerQuery) {
                                     $innerQuery->where('resched_date_in', '<=', $this->date_in)
                                                ->where('resched_date_out', '>=', $this->date_out);
                                 });
                    });
            });
        })
        ->get();
    

        // 3. Get all the reserved rooms from the reservations
        $reserved_rooms = $reservations->map(function ($reservation) {
            return $reservation->rooms->pluck('id')->toArray();
        })->flatten()->toArray();

        // 4. Check whether the existing rooms is currently reserved by another reservation
        $this->conflict_rooms = array_intersect($this->selected_rooms->pluck('id')->toArray(), $reserved_rooms);
        if (!empty($this->conflict_rooms)) {
            // 4.1. Reserved: Choose another room
            $this->toast('Update failed', 'danger', 'The selected rooms is already reserved on the selected dates');
            $this->conflict_rooms = Room::whereIn('id', $this->conflict_rooms)->get();
            return;
        } else {
            // 4.2. Not Reserved: Continue with the update
            $this->step = 2;
        }

        // Initialize the buildings and rooms
        $this->buildings = Building::with('rooms')->withCount('rooms')->get();
        $this->rooms = RoomType::with('rooms')->get();
    }

    public function back() {
        if ($this->step > 0) {
            $this->step--;
        }
    }

    public function goToStep($step) {
        $this->step = $step;
    }

    #[On('add-room')]
    public function toggleRoom(Room $room)
    {
        if ($room && !$this->selected_rooms->contains('id', $room->id)) {
            $this->selected_rooms->push($room);
            $this->max_capacity += (int) $room->max_capacity;
        } else {
            $this->selected_rooms = $this->selected_rooms->reject(function ($room_loc) use ($room) {
                return $room_loc->id == $room->id;
            });
            $this->max_capacity -= $room->max_capacity;
        }
    }

    #[On('add-rooms')]
    public function addRoom($rooms) {
        foreach ($rooms as $room) {
            // Check if the room is not yet selected
            if (!$this->selected_rooms->contains('id', $room)) {
                $room = Room::find($room);
                $this->toggleRoom($room);
                break;
            }
        }
    }

    public function removeRoom(Room $room) {
        $this->toggleRoom($room);
    }

    public function viewRooms($roomType) {
        $this->dispatch('view-rooms', [
            'room_type' => $roomType,
            'date_in' => $this->date_in,
            'date_out' => $this->date_out,
            'selected_rooms' => $this->selected_rooms->pluck('id')->toArray(),
        ]);
    }

    public function viewBuilding($building) {
        $this->dispatch("select-building", [
                'date_in' => $this->date_in,
                'date_out' => $this->date_out,
                'building' => $building,
                'selected_rooms' => $this->selected_rooms->pluck('id')->toArray(),
        ])->to(EditReservation::class);
    }

    public function updateGuests() {
        $this->dispatch('update-guests', [
            'adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
        ]);
    }

    #[On('apply-discount')] 
    public function applyDiscount($data) {
        $this->senior_count = $data['senior_count'];
        $this->pwd_count = $data['pwd_count'];
    }

    public function edit() {
        switch ($this->step) {
            case 1:
                $this->validate([
                    'date_in' => 'required|date',
                    'date_out' => 'required|date|after_or_equal:date_in',
                ]);

                $this->validateReservationDetails();
                break;
            case 2:
                $this->validate([
                    'adult_count' => 'required|gte:1|integer',
                    'children_count' => 'nullable|integer',
                    'selected_rooms' => 'required',
                ]);

                if ($this->adult_count + $this->children_count > $this->max_capacity) {
                    $this->addError('selected_rooms', 'Selected rooms cannot accommodate the total number of guests.');
                    return;
                }
                if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
                    $this->addError('adult_count', 'Total Seniors and PWDs cannot exceed total guests');
                    return;
                }

                $this->update();
            default:
                # code...
                break;
        }
    }

    public function update() {
        // 1. Update date and number of guests
        $reservation = $this->reservation;
        
        if ($reservation->date_in != $this->date_in) {
            $reservation->resched_date_in = $this->date_in;
        } else {
            $reservation->resched_date_in = null;
            $reservation->date_in = $this->date_in;
        }
        
        if ($reservation->date_out != $this->date_out) {
            $reservation->resched_date_out = $this->date_out;
        } else {
            $reservation->resched_date_out = null;
            $reservation->date_out = $this->date_out;
        }
        
        $reservation->adult_count = $this->adult_count;
        $reservation->children_count = $this->children_count;
        $reservation->senior_count = $this->senior_count;
        $reservation->pwd_count = $this->pwd_count;

        $reservation->save();
        
        // 2. Reassign rooms
        $room_service = new RoomService;
        $room_service->sync($reservation, $this->selected_rooms);

        // 3. Pop a toast
        $this->toast('Edit Success!', description: 'Reservation details updated!');
        $this->dispatch('reservation-details-updated');
    }

    public function render()
    {
        return <<<'HTML'
        <div class="space-y-5" 
            x-data="{
                hide: true,
                date_in: @entangle('date_in'),
                date_out: @entangle('date_out'),
                adult_count: @entangle('adult_count'),
                children_count: @entangle('children_count'),
                selected_rooms: @entangle('selected_rooms'),
                disable_date: @entangle('disable_date'),
                is_map_view: $wire.entangle('is_map_view'),
            }">
            <hgroup>
                <h2 class="text-lg font-semibold">Reservation Details</h2>
                <p class="max-w-sm text-xs">Enter your new reservation details below</p>
            </hgroup>

            {{-- Reservation steps --}}
            <div class="flex items-start gap-5 mb-10">
                <x-web.reservation.steps step="1" currentStep="{{ $step }}" icon="bed" name="Reservation Details" />
                <x-web.reservation.steps step="2" currentStep="{{ $step }}" icon="face" name="Select a Room" />
            </div>

            @switch($step)
                @case(1)
                    <div class="grid grid-cols-2 gap-5 p-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <x-form.input-label for='date_in'>Check-in date</x-form.input-label>
                            <x-form.input-date x-bind:disabled="disable_date ? true : false" x-model="date_in" min="{{ $min_date }}" id='date_in' name='date_in' class="w-full" />
                            <x-form.input-error field="date_in" />
                        </x-form.input-group>
                        <x-form.input-group>
                            <x-form.input-label for='date_out'>Check-out date</x-form.input-label>
                            <x-form.input-date x-model="date_out" x-bind:min="date_in" id='date_out' name='date_out' class="w-full" />
                            <x-form.input-error field="date_out" />
                        </x-form.input-group>
                    </div>

                    @if (!empty($conflict_rooms))
                        <div class="p-5 space-y-5 text-red-500 border border-red-500 rounded-md bg-red-50">
                            <p class="text-sm font-semibold">The selected rooms is already reserved on the selected dates, create a new reservation or select another date.</p>
                            <ul class="list-disc list-inside">
                                @foreach ($conflict_rooms as $room)
                                    <li>{{ $room->building->prefix . ' ' . $room->room_number }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex items-center justify-between gap-1">
                        <div>
                            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                            <x-primary-button type="button" wire:click="edit">Continue</x-primary-button>
                        </div>
                        <div class="ml-4">
                            <x-loading wire:loading wire:target="edit">Checking available rooms</x-loading>
                        </div>
                    </div>
                    @break
                @case(2)
                    <x-form.input-error field="selected_rooms" />

                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <x-secondary-button x-on:click="is_map_view = !is_map_view"
                                    x-show="!is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
                                    <p class="text-xs font-semibold">Map View</p>
                                </x-secondary-button>
                                <x-primary-button x-show="is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
                                    <p class="text-xs font-semibold">Map View</p>
                                </x-primary-button>
                            </div>

                            <div>
                                <x-primary-button x-show="!is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-collapse"><path d="m3 10 2.5-2.5L3 5" /><path d="m3 19 2.5-2.5L3 14" /><path d="M10 6h11" /><path d="M10 12h11" /><path d="M10 18h11" /></svg>
                                    <p class="text-xs font-semibold">List View</p>
                                </x-primary-button>
                                <x-secondary-button x-on:click="is_map_view = !is_map_view"
                                    x-show="is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-collapse"><path d="m3 10 2.5-2.5L3 5" /><path d="m3 19 2.5-2.5L3 14" /><path d="M10 6h11" /><path d="M10 12h11" /><path d="M10 18h11" /></svg>
                                    <p class="text-xs font-semibold">List View</p>
                                </x-secondary-button>
                            </div>
                        </div>
                        
                        {{-- 1. List View --}}
                        <template x-if="!is_map_view">
                            <div class="space-y-1">
                                @forelse ($rooms as $room)
                                    <div key="{{ $room->id }}" class="flex items-start justify-between gap-5 p-3 border rounded-lg">
                                        <div class="flex items-start w-full gap-5">
                                            <div class="w-full max-w-[150px] hidden sm:block">
                                                <x-img-lg src="{{ asset('storage/', $room->image_1_path) }}" />
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-md">{{ $room->name }}</h3>
                                                <p class="text-xs">Minimum rate: <x-currency /> {{ number_format($room->min_rate, 2) }}</p>
                                                <p class="text-xs">Maximum rate: <x-currency /> {{ number_format($room->max_rate, 2) }}</p>
                                            </div>
                                        </div>
                                        <x-secondary-button class="flex-shrink-0 text-xs" wire:click="viewRooms({{ $room->id }})">
                                            View Rooms
                                        </x-secondary-button>
                                    </div>
                                @empty
                                    <div class="border rounded-lg">
                                        <x-table-no-data.rooms />
                                    </div>
                                @endforelse
                            </div>
                        </template>

                        {{-- 2. Map View --}}
                        <template x-if="is_map_view">
                            <div class="grid grid-cols-3 gap-1 p-5 rounded-lg place-items-start min-h-80 bg-gradient-to-tr from-teal-500/20 to-teal-600/20">
                                @forelse ($buildings as $building)
                                    <button type="button" key="{{ $building->id }}" class="w-full"
                                        wire:click="viewBuilding({{ $building->id }})">
                                        <div
                                            class="relative grid w-full font-semibold bg-white border rounded-lg aspect-square place-items-center">
                                            <div class="text-center">
                                                <p>{{ $building->name }}</p>
                                                <p class="text-xs text-zinc-800/50">{{ $building->floor_count }}
                                                    @if ($building->floor_count > 1)
                                                        Floors
                                                    @else
                                                        Floor
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                @empty
                        
                                @endforelse
                            </div>
                        </template>

                        @if ($selected_rooms->count() > 0)
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold">Selected Rooms &lpar;{{ $selected_rooms->count() }}&rpar;</h3>
                                <button type="button" x-on:click="hide = false" x-show="hide" class="text-xs font-semibold text-blue-500">Hide Rooms</button>
                                <button type="button" x-on:click="hide = true" x-show="!hide" class="text-xs font-semibold text-blue-500">Show Rooms</button>
                            </div>
        
                            <div x-show="hide" class="space-y-5">
                                @forelse ($selected_rooms as $room)
                                <div wire:key="{{ $room->id }}" class="relative flex items-center gap-2 px-3 py-2 bg-white border rounded-lg border-slate-200">
                                    {{-- Room Details --}}
                                    <div>
                                        <p class="font-semibold capitalize border-r border-dashed line-clamp-1">{{ $room->building->prefix . ' ' . $room->room_number}}</p>
                                        <p class="text-sm">Room Rate: <x-currency />{{ $room->rate }} &#47; night</p>
                                        <p class="text-sm text-zinc-800">Good for {{ $room->max_capacity }} guests.</p>
                                    </div>

                                    {{-- Remove Room button --}}
                                    <button
                                        type="button"
                                        class="absolute text-xs font-semibold text-red-500 top-2 right-3"
                                        wire:click="removeRoom({{ $room }})">
                                        <span wire:loading.remove wire:target="removeRoom({{ $room }})">Remove</span>
                                        <span wire:loading wire:target="removeRoom({{ $room }})">Removing</span>
                                    </button>
                                </div>
                                @empty
                                    <div class="border rounded-lg">
                                        <x-table-no-data.rooms />
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <div class="grid grid-cols-2 gap-5">
                            <x-form.input-group>
                                <x-form.input-label for='adult_count'>Number of Adults</x-form.input-label>
                                <x-form.input-number x-model="adult_count" wire:model.live='adult_count' id='adult_count' name='adult_count' class="w-full" />
                                <x-form.input-error field="adult_count" />
                            </x-form.input-group>
                            <x-form.input-group>
                                <x-form.input-label for='children_count'>Number of Children</x-form.input-label>
                                <x-form.input-number x-model="children_count" wire:model.live='children_count' id='children_count' name='children_count' class="w-full" />
                                <x-form.input-error field="children_count" />
                            </x-form.input-group>
                            <x-form.input-error field="max_capacity" />
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <x-icon-button wire:click="updateGuests">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
                            </x-icon-button>

                            <div wire:click="updateGuests">
                                <p class="text-sm font-semibold">Apply Discounts</p>
                                <p class="text-sm">For Senior and PWD Guests</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-1">
                        <x-secondary-button type="button" wire:click="back">Back</x-secondary-button>
                        <x-primary-button type="button" wire:click="edit">Save Changes</x-primary-button>
                    </div>
                    @break
            @endswitch
        </div>
        HTML;
    }
}
