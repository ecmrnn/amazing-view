<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Users') }}
                </h1>
                <p class="text-xs">Manage your users here</p>
            </hgroup>
        </div>
    </x-slot:header>

    {{-- Room  Table --}}
    <div class="p-5 space-y-5 rounded-lg bg-gradient-to-r from-white to-white/0">
        <div class="flex items-center gap-3 sm:gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.users.index')}}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">Create User</h2>
                <p class="max-w-sm text-xs">Create a new user here.</p>
            </div>
        </div>

        <livewire:app.users.create-user />
    </div>
</x-app-layout>
