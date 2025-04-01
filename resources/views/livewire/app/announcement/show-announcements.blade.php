<div x-data="{ grid_view: true }" class="max-w-screen-lg mx-auto space-y-5">
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Announcements</h2>
            <p class="text-xs">View your announcements here</p>
        </hgroup>
        
        @if ($announcements->count() > 0)
            <div class="grid gap-5 lg:grid-cols-3 sm:grid-cols-2">
                @foreach ($announcements as $announcement)
                    <div wire:key='{{ $announcement->id }}' class="flex flex-col justify-between gap-5 p-5 border rounded-md border-slate-200">
                        <div class="space-y-5">
                            <x-img src="{{ $announcement->image }}" />

                            <div>
                                <h2 class="font-semibold line-clamp-1">{{ $announcement->title }}</h2>
                                <p class="text-xs line-clamp-3">{{ $announcement->description }}</p>
                            </div>

                            @if ($announcement->status == \App\Enums\AnnouncementStatus::ACTIVE->value && $announcement->expires_at)
                                <p class="text-xs">Active until: {{ date_format(date_create($announcement->expires_at), 'F j, Y') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <x-status type="announcement" :status="$announcement->status" />

                            <div class="flex gap-1">
                                <x-tooltip text="Edit">
                                    <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'edit-announcement-modal-{{ $announcement->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                                @if ($announcement->status == \App\Enums\AnnouncementStatus::ACTIVE->value)
                                    <x-tooltip text="Disable">
                                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'disable-announcement-modal-{{ $announcement->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban-icon lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
                                @else
                                    <x-tooltip text="Enable">
                                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'enable-announcement-modal-{{ $announcement->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                        </x-icon-button>
                                    </x-tooltip>
                                @endif
                                <x-tooltip text="Delete">
                                    <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'delete-announcement-modal-{{ $announcement->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </x-icon-button>
                                </x-tooltip>
                            </div>
                        </div>

                        <x-modal.full name='edit-announcement-modal-{{ $announcement->id }}' maxWidth='sm'>
                            <livewire:app.announcement.edit-announcement wire:key='edit-{{ $announcement->image }}' :announcement="$announcement" />
                        </x-modal.full>

                        <x-modal.full name='disable-announcement-modal-{{ $announcement->id }}' maxWidth='sm'>
                            <livewire:app.announcement.disable-announcement wire:key='disable-{{ $announcement->image }}' :announcement="$announcement" />
                        </x-modal.full>

                        <x-modal.full name='enable-announcement-modal-{{ $announcement->id }}' maxWidth='sm'>
                            <livewire:app.announcement.enable-announcement wire:key='enable-{{ $announcement->image }}' :announcement="$announcement" />
                        </x-modal.full>

                        <x-modal.full name='delete-announcement-modal-{{ $announcement->id }}' maxWidth='sm'>
                            <livewire:app.announcement.delete-announcement wire:key='delete-{{ $announcement->image }}' :announcement="$announcement" />
                        </x-modal.full>
                    </div>
                @endforeach
            </div>
            
            <x-note>Note: Only one announcement can be active at a time. Enabling an announcement will automatically disable the currently active one.</x-note>
        @else
            <div class="font-semibold text-center lg:col-span-3 sm:col-span-2">
                <x-table-no-data.announcement />
            </div>
        @endif
    </div>

</div>
