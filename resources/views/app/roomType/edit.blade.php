<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Rooms') }}
                </h1>
                <p class="text-xs">Manage your rooms here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <livewire:app.room-type.edit-room-type :room_type="$room_type" />
</x-app-layout>  