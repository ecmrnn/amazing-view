<?php

namespace App\Livewire\App\Reservation;

use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\RoomService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditReservationDetails extends Component
{
    use DispatchesToast;

    #[Validate] public $adult_count;
    #[Validate] public $children_count;
    #[Validate] public $pwd_count;
    #[Validate] public $senior_count;

    public $reservation;
    public $rooms;
    public $selected_rooms;
    public $buildings;
    public $max_capacity;
    public $is_map_view = false;
    public $step = 1;

    public function mount(Reservation $reservation) {
        $this->reservation = $reservation;
        $this->adult_count = $reservation->adult_count;
        $this->children_count = $reservation->children_count;
        $this->pwd_count = $reservation->pwd_count;
        $this->senior_count = $reservation->senior_count;
    }

    public function rules() {
        return [
            'adult_count' => 'required|gte:1|integer',
            'children_count' => 'nullable|integer',
            'pwd_count' => 'nullable|integer',
            'senior_count' => 'nullable|integer',
        ];
    }

    public function messages() {
        return [
            'selected_rooms.required' => 'Please select a room first',
        ];
    }

    public function goToStep($step) {
        $this->step = $step;
    }

    public function viewBuilding($building) {
        $this->dispatch("select-building", [
                'date_in' => $this->reservation->date_in,
                'date_out' => $this->reservation->date_out,
                'building' => $building,
                'selected_rooms' => $this->selected_rooms->pluck('id')->toArray(),
        ])->to(EditReservation::class);
    }

    public function viewRooms($roomType) {
        $this->dispatch('view-rooms', [
            'room_type' => $roomType,
            'date_in' => $this->reservation->date_in,
            'date_out' => $this->reservation->date_out,
            'selected_rooms' => $this->selected_rooms->pluck('id')->toArray(),
        ]);
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

    #[On('apply-discount')]
    public function applyDiscount($data) {
        $this->senior_count = $data['senior_count'];
        $this->pwd_count = $data['pwd_count'];
    }

    public function updateGuests() {
        $this->dispatch('update-guests', [
            'adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
        ]);
    }

    public function submit() {
        switch ($this->step) {
            // Manage Guests
            case 1:
                $this->validate([
                    'adult_count' => $this->rules()['adult_count'],
                    'children_count' => $this->rules()['children_count'],
                    'pwd_count' => $this->rules()['pwd_count'],
                    'senior_count' => $this->rules()['senior_count'],
                ]);

                if ($this->senior_count + $this->pwd_count > $this->adult_count + $this->children_count) {
                    $this->addError('pwd_count', 'Total Seniors and PWDs cannot exceed total guests');
                    return;
                }
                
                if ($this->senior_count > $this->adult_count) {
                    $this->addError('senior_count', 'Total seniors cannot exceed total adults');
                    return;
                }

                $this->step = 2;

                // Initialize the buildings and rooms
                $this->buildings = Building::with('rooms')->withCount('rooms')->get();
                $this->rooms = RoomType::with('rooms')->get();
                $this->selected_rooms = $this->reservation->rooms;

                // Initialize max capacity
                $this->max_capacity = 0;
                foreach ($this->reservation->rooms as $room) {
                    $this->max_capacity += $room->max_capacity;
                }

                $this->toast('Succcess!', description: 'Next, manage rooms!');
                break;

            // Manage Rooms
            default:
                $this->validate([
                    'selected_rooms' => 'required',
                ]);

                if ($this->adult_count + $this->children_count > $this->max_capacity) {
                    $this->addError('selected_rooms', 'Selected rooms cannot accommodate all guests.');
                    return;
                }

                // Update rooms and guests
                $this->reservation->update([
                    'adult_count' => $this->adult_count,
                    'children_count' => $this->children_count,
                    'senior_count' => $this->senior_count,
                    'pwd_count' => $this->pwd_count,
                ]);

                $service = new RoomService;
                $service->sync($this->reservation, $this->selected_rooms);

                $this->toast('Edit Success!', description: 'Rooms and Guests edited successfully');
                $this->dispatch('reservation-edited');
                break;
        }
    }

    public function render()
    {
        return <<<'HTML'
        <div x-data="{ 
                adult_count: @entangle('adult_count'),
                children_count: @entangle('children_count'),
                is_map_view: @entangle('is_map_view'),
                hide: true,
            }" 
            x-on:reservation-edited.window="show = false"
            class="block p-5 space-y-5">
            <hgroup>
                <h2 class="text-lg font-semibold">Manage Rooms and Guests</h2>
                <p class="text-xs">Edit reserved rooms and guests count</p>
            </hgroup>

            {{-- Reservation steps --}}
            <div class="flex items-start gap-5 mb-10">
                <x-web.reservation.steps step="1" currentStep="{{ $step }}" name="Manage Guests" />
                <x-web.reservation.steps step="2" currentStep="{{ $step }}" name="Manage Rooms" />
            </div>

            @switch($step)
                @case(1)
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <x-form.input-group>
                                <x-form.input-label for='adult_count'>Number of Adults</x-form.input-label>
                                <x-form.input-number x-model="adult_count" id="adult_count" name="adult_count" />
                                <x-form.input-error field="adult_count" />
                            </x-form.input-group>
                            <x-form.input-group>
                                <x-form.input-label for='children_count'>Number of Children</x-form.input-label>
                                <x-form.input-number x-model="children_count" id="children_count" name="children_count" />
                                <x-form.input-error field="children_count" />
                            </x-form.input-group>
                        </div>

                        <x-form.input-error field="pwd_count" />
                        <x-form.input-error field="senior_count" />

                        <div class="flex items-center w-full gap-3 p-5 border rounded-md border-slate-200 hover:cursor-pointer" wire:click="updateGuests">
                            <x-icon-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>
                            </x-icon-button>

                            <div>
                                <p class="text-sm font-semibold">Apply Discounts</p>
                                <p class="text-xs">For Senior and PWD Guests</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <x-loading wire:loading wire:target="submit">Preparing rooms, please wait</x-loading>
                                <x-loading wire:loading wire:target="updateGuests">Syncing guests, please wait</x-loading>
                            </div>
                        
                            <div class="flex justify-end gap-1">
                                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                                <x-primary-button type="button" wire:click="submit">Continue</x-primary-button>
                            </div>
                        </div>
                    </div>
                    @break
                @case(2)
                <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <x-secondary-button type="button" x-on:click="$wire.set('is_map_view', true)"
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
                                <x-secondary-button type="button" x-on:click="$wire.set('is_map_view', false)"
                                    x-show="is_map_view" class="flex w-full gap-2 m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-collapse"><path d="m3 10 2.5-2.5L3 5" /><path d="m3 19 2.5-2.5L3 14" /><path d="M10 6h11" /><path d="M10 12h11" /><path d="M10 18h11" /></svg>
                                    <p class="text-xs font-semibold">List View</p>
                                </x-secondary-button>
                            </div>
                        </div>

                        <x-form.input-error field="selected_rooms" />
                        
                        {{-- 1. List View --}}
                        @if (!$is_map_view)
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
                        @else
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
                                    <!-- No Buildings -->
                                @endforelse
                            </div>
                        @endif

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

                    <div class="flex justify-end gap-1">
                        <x-secondary-button type="button" x-on:click="$wire.set('step', 1)">Back</x-secondary-button>
                        <x-primary-button type="button" wire:click="submit">Save</x-primary-button>
                    </div>
                    @break
            @endswitch
        </div>
        HTML;
    }
}
