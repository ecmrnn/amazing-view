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

            <div class="flex items-center gap-5">
                <x-status type="page" :status="$page->status" />
                
                <x-actions>
                    <div class="space-y-1">
                        <x-action-button x-on:click="$dispatch('open-modal', 'change-status-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                            <p>Change Status</p>
                        </x-action-button>
                        @if ($page->title != 'Global')
                            <x-action-button x-on:click="$dispatch('open-modal', 'show-preview-modal')" class="hidden xl:flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-view"><path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2"/><path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"/><circle cx="12" cy="12" r="1"/><path d="M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"/></svg>
                                <p>Preview</p>
                            </x-action-button>
                        @endif
                    </div>
                </x-actions>
            </div>
        </div>

        <x-note>
            <p>Any update made on this page will be automatically applied to the website. You may view what your changes may look like using the preview in the action button. <strong>Proceed with caution</strong>!</p>
        </x-note>

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
                 <livewire:app.content.global.edit-global />
                @break
        @endswitch
    </div>
</x-app-layout>