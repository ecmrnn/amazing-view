<?php

namespace App\Livewire\App\Room;

use App\Http\Controllers\DateController;
use App\Models\Room;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FindRoom extends Component
{
    use DispatchesToast;

    #[Validate] public $date_in;
    #[Validate] public $date_out;
    #[Validate] public $guest_count = 1;
    public $min_date;
    public $rooms;
    public $index = 0;
    public $today;

    public function rules() {
        return [
            'date_in' => 'required|date|after_or_equal:' . $this->today,
            'date_out' => 'required|date|after_or_equal:date_in',
            'guest_count' => 'required|integer|min:1',
        ];
    }

    public function mount() {
        $this->min_date = now()->format('Y-m-d');
        $this->date_in = Carbon::now()->format('Y-m-d');
        $this->date_out = Carbon::now()->addDay()->format('Y-m-d');
        $this->today = DateController::today();
        $this->rooms = collect();
    }

    public function resetForm() {
        $this->reset();
        
        $this->min_date = now()->format('Y-m-d');
        $this->date_in = Carbon::now()->format('Y-m-d');
        $this->date_out = Carbon::now()->addDay()->format('Y-m-d');
        $this->rooms = collect();
    }

    public function previous() {
        if ($this->index - 1 > 0) {
            $this->index--;
        } else {
            $this->index = $this->rooms->count() - 1;
        }
    }

    public function next() {
        if ($this->index + 1 < $this->rooms->count()) {
            $this->index++;
        } else {
            $this->reset('index');
        }
    }

    public function submit() {
        $this->validate();

        $reserved_rooms = Room::reservedRooms($this->date_in, $this->date_out)->pluck('id');

        $this->rooms = Room::whereNotIn('id', $reserved_rooms)->where('max_capacity', '>=', $this->guest_count)->get();
        
        if (!$this->rooms) {
            $this->resetForm();
            $this->dispatch('no-rooms-found');
            $this->toast('No room found', 'info', 'Offer multiple rooms to avail!');
        }
    }

    public function render()
    {
        return <<<'HTML'
        <form x-data="{ guest_count: @entangle('guest_count') }" wire:submit="submit" class="p-5 space-y-5" x-on:no-rooms-found.window="show = false">
            @if ($this->rooms->count() == 0)
                <hgroup>
                    <h2 class='font-semibold'>Find a Room</h2>
                    <p class='text-xs'>Enter the number of guests</p>
                </hgroup>

                <div class="grid grid-cols-2 gap-5">
                    <x-form.input-group>
                        <x-form.input-label for='date_in'>Check-in Date</x-form.input-label>
                        <x-form.input-date id="date_in" name="date_in" wire:model.live="date_in" class="w-full" min="{{ $min_date }}" />
                        <x-form.input-error field="date_in" />
                    </x-form.input-group>
                    <x-form.input-group>
                        <x-form.input-label for='date_out'>Check-out Date</x-form.input-label>
                        <x-form.input-date id="date_out" name="date_out" wire:model.live="date_out" class="w-full" min="{{ $date_in }}" />
                        <x-form.input-error field="date_out" />
                    </x-form.input-group>
                </div>

                <x-form.input-group>
                    <x-form.input-label for='guest_count'>Enter total number of guests</x-form.input-label>
                    <x-form.input-number id="guest_count" name="guest_count" x-model="guest_count" min="1" />
                    <x-form.input-error field="guest_count" />
                </x-form.input-group>

                <x-loading wire:loading wire:target='submit'>Looking for available rooms</x-loading>

                <div class="flex justify-end gap-1">
                    <x-secondary-button type='button' x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button>Find</x-primary-button>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <hgroup>
                        <h2 class='font-semibold'>Room Found</h2>
                        @if ($rooms->count() > 1)
                            <p class='text-xs'>Check out this amazing rooms!</p>
                        @else
                            <p class='text-xs'>Check out this amazing room!</p>
                        @endif
                    </hgroup>

                    <div>
                        <x-tooltip text='Reset'>
                            <x-icon-button x-ref='content' wire:click="resetForm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            </x-icon-button>
                        </x-tooltip>
                    </div>
                </div>

                @foreach ($rooms as $key => $room)
                    @if ($key == $index)
                        <div class="space-y-5">
                            <x-img src="{{ $room->image_1_path ?? $room->roomType->image_1_path }}" />

                            <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                                <hgroup>
                                    <h2 class='font-semibold capitalize'>{{ $room->room_number }}</h2>
                                    <p class='text-xs'>{{ $room->roomType->name }}</p>
                                </hgroup>

                                <div class="grid grid-cols-2 gap-5">
                                    <div class="flex items-center gap-3">
                                        <x-icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag-icon lucide-tag"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                                        </x-icon>
                                        <div>
                                            <p class="text-sm font-semibold"><x-currency />{{ number_format($room->rate, 2) }}</p>
                                            <p class="text-xs">Rate</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        </x-icon>
                                        <div>
                                            <p class="text-sm font-semibold">{{ $room->max_capacity }} guests</p>
                                            <p class="text-xs">Capacity</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                        </x-icon>
                                        <div>
                                            <p class="text-sm font-semibold">{{ $room->building->name }}</p>
                                            <p class="text-xs">Building</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                @if ($rooms->count() > 1)
                    <div class="flex items-center justify-between">
                        <x-tooltip text='Previous'>
                            <x-icon-button x-ref='content' wire:click="previous">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                            </x-icon-button>
                        </x-tooltip>

                        <x-tooltip text='Next'>
                            <x-icon-button x-ref='content' wire:click="next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </x-icon-button>
                        </x-tooltip>
                    </div>
                @endif
            @endif
        </form>
        HTML;
    }
}
