<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Content') }}
                </h1>
                <p class="text-xs">Manage your content here</p>
            </hgroup>
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div class="flex items-center gap-3 sm:gap-5">
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

        {{-- Contents --}}
        @switch($page->id)
            @case(1)
                <livewire:app.content.home.edit-home />
                @break
            @case(2)
                
                @break
            @case(3)
                 <livewire:app.content.about.edit-about />
                @break
            @case(4)
                 <livewire:app.content.contact.edit-contact />
                @break
            @default
                
        @endswitch
    </div>
</x-app-layout>