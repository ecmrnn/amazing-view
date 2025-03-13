<?php

namespace App\Livewire\App\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomReservation;
use App\Models\RoomType;
use App\Services\ReservationService;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RescheduleReservation extends Component
{
    use DispatchesToast;

    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $selected_rooms;
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
        $this->date_in = $reservation->date_in;
        $this->date_out = $reservation->date_out;
        $this->selected_rooms = $reservation->rooms;
        $this->reservation = $reservation;

        // Initialize the buildings and rooms
        $this->buildings = Building::with('rooms')->withCount('rooms')->get();
        $this->rooms = RoomType::with('rooms')->get();

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
    
    public function checkRoomConflict() {
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
            $query->whereBetween('date_in', [$this->date_in, $this->date_out])
                  ->orWhereBetween('date_out', [$this->date_in, $this->date_out])
                  ->orWhere(function ($innerQuery) {
                      $innerQuery->where('date_in', '<=', $this->date_in)
                                 ->where('date_out', '>=', $this->date_out);
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
            $this->toast('Unavailable Rooms', 'warning', 'One of the rooms reserved is unavailable on the selected dates.');
            $this->conflict_rooms = Room::whereIn('id', $this->conflict_rooms)->get();
            return;
        } 
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

        $this->checkRoomConflict();
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

        $this->checkRoomConflict();
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

    public function reschedule() {
        switch ($this->step) {
            case 1:
                $this->validate([
                    'date_in' => 'required|date',
                    'date_out' => 'required|date|after_or_equal:date_in',
                ]);

                if ($this->reservation->date_in == $this->date_in && $this->reservation->date_out == $this->date_out) {
                    $this->addError('date_in', 'Select a new check-in date');
                    $this->addError('date_out', 'Select a new check-out date');
                    return;
                }

                $this->step = 2;
                $this->checkRoomConflict();
                break;
            default:
                $this->validate([
                    'selected_rooms' => 'required',
                ]);

                if (!empty($this->conflict_rooms)) {
                    $this->toast('Reschedule Failed', 'warning', 'One of the Rooms selected is already reserved.');
                    return;
                }

                $this->update();
                break;
        }
    }

    public function update() {
        $validated = $this->validate([
            'date_in' => 'date|required',
            'date_out' => 'date|required',
            'selected_rooms' => 'required',
        ]);
        $validated['selected_rooms'] = $this->selected_rooms;
        
        // 1. Reschedule reservation
        $service = new ReservationService;
        $service->reschedule($this->reservation, $validated);

        // 2. Pop a toast
        $this->toast('Reservation Rescheduled!', description: 'Succesfully rescheduled this reservation!');
        $this->dispatch('reservation-details-updated');
    }

    public function render()
    {
        return <<<'HTML'
        <form wire:submit="reschedule" class="p-5 space-y-5" x-on:reservation-details-updated.window="show = false"
            x-data="{
                hide: true,
                date_in: @entangle('date_in'),
                date_out: @entangle('date_out'),
                selected_rooms: @entangle('selected_rooms'),
                is_map_view: $wire.entangle('is_map_view'),
            }">
            <hgroup>
                <h2 class="text-lg font-semibold">Reschedule Reservation</h2>
                <p class="max-w-sm text-xs">Enter your new reservation details below</p>
            </hgroup>

            {{-- Reservation steps --}}
            <div class="flex items-start gap-5 mb-10">
                <x-web.reservation.steps step="1" currentStep="{{ $step }}" icon="bed" name="Reservation Dates" />
                <x-web.reservation.steps step="2" currentStep="{{ $step }}" icon="face" name="Confirm Rooms" />
            </div>

            @switch($step)
                @case(1)
                    <div class="grid grid-cols-2 gap-5 p-5 bg-white border rounded-md border-slate-200">
                        <x-form.input-group>
                            <x-form.input-label for='date_in'>New check-in date</x-form.input-label>
                            <x-form.input-date x-model="date_in" min="{{ $min_date }}" id='date_in' name='date_in' class="w-full" />
                            <x-form.input-error field="date_in" />
                        </x-form.input-group>
                        <x-form.input-group>
                            <x-form.input-label for='date_out'>New check-out date</x-form.input-label>
                            <x-form.input-date x-model="date_out" x-bind:min="date_in" id='date_out' name='date_out' class="w-full" />
                            <x-form.input-error field="date_out" />
                        </x-form.input-group>
                    </div>

                    <div class="flex items-center justify-between gap-1">
                        <div>
                            <x-loading wire:loading wire:target="reschedule">Checking room availability</x-loading>
                        </div>
                        <div>
                            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                            <x-primary-button type="submit">Continue</x-primary-button>
                        </div>
                    </div>
                    @break
                @case(2)
                    <x-form.input-error field="selected_rooms" />

                    @if (!empty($conflict_rooms))
                        <div class="p-5 space-y-5 text-red-500 border border-red-500 rounded-md bg-red-50">
                            <p class="text-xs font-semibold">The following rooms is already reserved on the selected dates.</p>
                            <ul class="list-disc list-inside">
                                @foreach ($conflict_rooms as $room)
                                    <li class="text-xs font-semibold">{{ $room->building->prefix . ' ' . $room->room_number }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <x-secondary-button type="button" x-on:click="is_map_view = !is_map_view"
                                    x-show="!is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
                                    <p class="text-xs font-semibold">Map View</p>
                                </x-secondary-button>
                                <x-primary-button type="button" x-show="is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map"><path d="M14.106 5.553a2 2 0 0 0 1.788 0l3.659-1.83A1 1 0 0 1 21 4.619v12.764a1 1 0 0 1-.553.894l-4.553 2.277a2 2 0 0 1-1.788 0l-4.212-2.106a2 2 0 0 0-1.788 0l-3.659 1.83A1 1 0 0 1 3 19.381V6.618a1 1 0 0 1 .553-.894l4.553-2.277a2 2 0 0 1 1.788 0z"/><path d="M15 5.764v15"/><path d="M9 3.236v15"/></svg>
                                    <p class="text-xs font-semibold">Map View</p>
                                </x-primary-button>
                            </div>

                            <div>
                                <x-primary-button type="button" x-show="!is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-collapse"><path d="m3 10 2.5-2.5L3 5" /><path d="m3 19 2.5-2.5L3 14" /><path d="M10 6h11" /><path d="M10 12h11" /><path d="M10 18h11" /></svg>
                                    <p class="text-xs font-semibold">List View</p>
                                </x-primary-button>
                                <x-secondary-button type="button" x-on:click="is_map_view = !is_map_view"
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
                                                <p class="text-xs">Minimum rate: <x-currency />{{ number_format($room->min_rate, 2) }}</p>
                                                <p class="text-xs">Maximum rate: <x-currency />{{ number_format($room->max_rate, 2) }}</p>
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
                                <h3 class="flex items-center gap-3 font-semibold">
                                    <p>Selected Rooms</p>
                                    <div class="px-2 py-1 text-xs text-blue-800 border border-blue-500 rounded-md bg-blue-50 aspect-square">{{ $selected_rooms->count() }}</div>
                                </h3>
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
                    
                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" wire:click="back">Back</x-secondary-button>
                        <x-primary-button type="submit">Reschedule</x-primary-button>
                    </div>
                    @break
            @endswitch
        </form>
        HTML;
    }
}
