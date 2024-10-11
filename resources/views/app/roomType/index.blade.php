<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Rooms') }}
                </h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>

            @can('create room')
                <x-primary-button class="text-xs">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        <span>Add Room</span>
                    </div>
                </x-primary-button>
            @endcan
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div class="flex justify-between gap-5">
            <hgroup>
                <h2 class="text-lg font-semibold">Rooms</h2>
                <p class="max-w-sm text-xs">Manage all your rooms here.</p>
            </hgroup>

            @can('create room type')
                <x-primary-button class="text-xs">
                    Add Room Type
                </x-primary-button>
            @endcan
        </div>

        <div>
            <div class="grid gap-5 lg:grid-cols-4 sm:grid-cols-2">
                @forelse ($rooms as $room)
                    <div key="{{ $room->id }}" class="space-y-1 group">
                        <div class="relative">
                            <x-img-lg src="{{ $room->image_1_path }}" />

                            <div class="absolute hidden gap-1 top-3 right-3 group-hover:flex">
                                @can('update room type')
                                    <x-tooltip text="Edit" dir="bottom">
                                        <a href="{{ route('app.rooms.edit', ['room' => $room->id]) }}" wire:navigate>
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
                                        <x-icon-button x-ref="content">
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

                        <div class="p-3 border rounded-lg">
                            <h2 class="text-lg font-semibold">{{ $room->name }}</h2>
                            <p class="text-sm">{{ $room->description }}</p>

                            <a href="{{ route('app.room.index', ['type' => $room->id]) }}" wire:navigate>
                                <x-primary-button class="mt-3">View Rooms</x-primary-button>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-4">
                        @include('components.table-no-data.rooms')
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
