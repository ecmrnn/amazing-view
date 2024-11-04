<?php

namespace App\Livewire\App\RoomType;

use App\Models\RoomType;
use Livewire\Component;

class ShowRoomTypes extends Component
{
    protected $listeners = ['room-type-deleted' => '$refresh'];
    
    public $room_types;

    public function render()
    {
        $this->room_types = RoomType::withCount('rooms')->get();
        
        return <<<'HTML'
        <div class="grid gap-1 p-5 bg-white rounded-lg lg:grid-cols-5 sm:grid-cols-2">
            @forelse ($room_types as $room_type)
                <div key="{{ $room_type->id }}" x-data="{ rooms_count: @js($room_type->rooms_count) }" class="p-5 space-y-5 bg-white border border-gray-300 rounded-lg group">
                    <div class="relative">
                        <x-img-lg src="{{ asset('storage/' . $room_type->image_1_path) }}" />

                        <div class="absolute hidden gap-1 top-3 right-3 group-hover:flex">
                            @can('update room type')
                                <x-tooltip text="Edit" dir="bottom">
                                    <a href="{{ route('app.rooms.edit', ['room' => $room_type->id]) }}" wire:navigate>
                                        <x-icon-button x-ref="content">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path
                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                        </x-icon-button>
                                    </a>
                                </x-tooltip>
                            @endcan

                            @can('delete room type')
                                <x-tooltip text="Delete" dir="bottom">
                                    <x-icon-button x-ref="content" x-bind:disabled="rooms_count > 0" x-on:click="$dispatch('open-modal', 'delete-room-type-{{ $room_type->id }}-modal')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash-2">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </x-icon-button>
                                </x-tooltip>
                            @endcan
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold">{{ $room_type->name }}</h2>
                        <p class="text-sm line-clamp-3">{{ $room_type->description }}</p>

                        <a href="{{ route('app.room.index', ['type' => $room_type->id]) }}" wire:navigate>
                            <x-primary-button class="mt-3">View Rooms</x-primary-button>
                        </a>
                    </div>

                    {{-- Delete Modal --}}
                    <x-modal.full name='delete-room-type-{{ $room_type->id }}-modal' maxWidth='sm'>
                        <livewire:app.room-type.delete-room-type :room_type="$room_type" />
                    </x-modal.full>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-4">
                    @include('components.table-no-data.rooms')
                </div>
            @endforelse
        </div>
        HTML;
    }
}
