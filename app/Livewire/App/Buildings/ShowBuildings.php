<?php

namespace App\Livewire\App\Buildings;

use App\Models\Building;
use Livewire\Component;

class ShowBuildings extends Component
{
    protected $listeners = ['building-created' => '$refresh',];

    public $buildings;
    
    public function render()
    {
        $this->buildings = Building::withCount('rooms')->get();
        
        return <<<'HTML'
            <div class="grid gap-1 p-5 bg-white rounded-lg lg:grid-cols-5 sm:grid-cols-2">
                @foreach ($buildings as $building)
                    <div key="{{ $building->id }}" x-data="{ rooms_count: @js($building->rooms_count) }" class="p-5 space-y-5 bg-white border border-gray-300 rounded-lg group">
                        <div class="relative">
                            <x-img-lg src="{{ asset('storage/' . $building->image) }}" />

                            <div class="absolute hidden gap-1 top-3 right-3 group-hover:flex">
                                <x-tooltip text="Edit" dir="bottom">
                                    <a href="{{ route('app.rooms.edit', ['room' => $building->id]) }}" wire:navigate>
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
                                
                                <x-tooltip text="Delete" dir="bottom">
                                    <x-icon-button x-ref="content" x-bind:disabled="rooms_count > 0" x-on:click="$dispatch('open-modal', 'delete-room-type-{{ $building->id }}-modal')">
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
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold">{{ $building->name }} <span class="text-sm">({{ $building->prefix }})</span></h2>
                            <p class="text-sm line-clamp-3">{{ $building->description }}</p>
                            
                            <a href="{{ route('app.room.index', ['type' => $building->id]) }}" wire:navigate>
                                <x-primary-button class="mt-3">View Rooms</x-primary-button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </section>
        HTML;
    }
}
