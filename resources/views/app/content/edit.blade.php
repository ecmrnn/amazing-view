<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    Contents
                </h1>
                <p class="text-xs">Manage your contents here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <div class="max-w-screen-lg mx-auto space-y-5">
        <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-center gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.contents.index') }}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-lg font-semibold">Edit {{ $page->title }}</h2>
                    <p class="max-w-sm text-xs">Update {{ strtolower($page->title) }} contents here</p>
                </hgroup>
            </div>

            <x-actions>
                <x-action-button x-on:click="$dispatch('open-modal', 'change-status-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                    <p>Change Status</p>
                </x-action-button>
            </x-actions>
        </div>

        {{-- Contents --}}
        @switch($page->id)
            @case(1)
                <livewire:app.content.home.edit-home />
                @break
            @case(2)
                <livewire:app.content.rooms.edit-rooms />
                @break
            @case(3)
                 <livewire:app.content.about.edit-about />
                @break
            @case(4)
                 <livewire:app.content.contact.edit-contact />
                @break
            @case(5)
                 <livewire:app.content.reservation.edit-reservation />
                @break
            @default
        
        @endswitch
    </div>
</x-app-layout>