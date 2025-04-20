<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="flex items-center justify-between gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.room.index', ['type' => $room->roomType->id]) }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <hgroup>
                <h2 class="text-lg font-semibold">Edit Room <span class="px-2 py-1 ml-2 text-xs border rounded-md bg-slate-50 border-slate-200">{{ $room->room_number }}</span></h2>
                <p class="max-w-sm text-xs">Update your room details here</p>
            </hgroup>
        </div>

        <div class="flex items-center gap-5">
            <x-status type="room" :status="$room->status" />
            @if (in_array($room->status, [
                    App\Enums\RoomStatus::AVAILABLE->value,
                    App\Enums\RoomStatus::UNAVAILABLE->value,
                    App\Enums\RoomStatus::DISABLED->value,
                ]))
                <x-actions>
                    <div class="space-y-1">
                        <x-action-button type="button" x-on:click="$dispatch('open-modal', 'change-status-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                            <p>Change Status</p>
                        </x-action-button>
                        <x-action-button type="button" x-on:click="$dispatch('open-modal', 'add-inclusion-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bath-icon lucide-bath"><path d="M10 4 8 6"/><path d="M17 19v2"/><path d="M2 12h20"/><path d="M7 19v2"/><path d="M9 5 7.621 3.621A2.121 2.121 0 0 0 4 5v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/></svg>
                            <p>Add Inclusion</p>
                        </x-action-button>
                        <x-action-button type="button" x-on:click="$dispatch('open-modal', 'disable-room-modal'); dropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ban"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                            <p>Disable Room</p>
                        </x-action-button>
                        @empty($room->reservations->count())
                            <x-action-button type="button" x-on:click="$dispatch('open-modal', 'delete-room-modal'); dropdown = false" class="text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                <p>Delete Room</p>
                            </x-action-button>
                        @endempty
                    </div>
                </x-actions>
            @endif
        </div>
    </div>

    <form x-data="{
            min_capacity: @entangle('min_capacity'),
            max_capacity: @entangle('max_capacity'),
        }" wire:submit="submit" class="space-y-5">
        <div class="p-5 bg-white border rounded-lg border-slate-200">
            <div class="w-full space-y-5 md:w-1/2">
                <hgroup>
                    <h3 class="font-semibold">General Room Details</h3>
                    <p class="text-xs">Edit room details here</p>
                </hgroup>

                <x-form.input-group>
                    <x-form.input-label for='room_number'>Room Number</x-form.input-label>

                    <div class="flex gap-2">
                        <div class="grid px-3 text-sm font-semibold border rounded-md border-slate-200 place-items-center text-zinc-800/75">{{ $room->building->prefix  }}</div>
                        <x-form.input-text id="room_number" name="room_number" label="room_number" wire:model.live='room_number' wire:keypress.debounce.200ms='checkRoomNumber' />
                    </div>
                    
                    <x-form.input-error field="room_number" />
                </x-form.input-group>

                <!-- Capacity -->
                <div class="grid grid-cols-2 gap-5">
                    <x-form.input-group>
                        <x-form.input-label for="min_capacity">Min. Capacity</x-form.input-label>
                        <x-form.input-number id="min_capacity" min="1" name="min_capacity" wire:model.live="min_capacity" x-model="min_capacity" class="w-full" />
                        <x-form.input-error field="min_capacity" />
                    </x-form.input-group>
                    <x-form.input-group>
                        <x-form.input-label for="max_capacity">Max. Capacity</x-form.input-label>
                        <x-form.input-number id="max_capacity" min="{{ $min_capacity }}" name="max_capacity" wire:model.live="max_capacity" x-model="max_capacity" class="w-full" />
                        <x-form.input-error field="max_capacity" />
                    </x-form.input-group>
                </div>

                {{-- Rate --}}
                <x-form.input-group>
                    <div>
                        <x-form.input-label for="rate">Room Rate</x-form.input-label>
                        <p class="text-xs">Enter a price on how much you are going to charge for this room</p>
                    </div>
                    <x-form.input-currency id="rate" name="rate" wire:model.live='rate' class="w-min" />
                    <x-form.input-error field="rate" />
                </x-form.input-group>
            </div>
        </div>

        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <hgroup>
                <h3 class="font-semibold">Image Gallery</h3>
                <p class="text-xs">View your room images here</p>
            </hgroup>

            <!-- Image -->
            <x-form.input-group>
                <div>
                    <x-form.input-label for="image_1_path">Image</x-form.input-label>
                    <p class="text-xs">Upload images of your room here</p>
                </div>

                @if ($room->attachments->count() > 0)
                    <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-4">
                        @foreach ($room->attachments as $image)
                            <div wire:key='{{ $image->id }}' class="relative">
                                <x-img src="{{ $image->path }}" :zoomable="true" />
    
                                <x-icon-button x-on:click="$dispatch('open-modal', 'delete-image-modal-{{ $image->id }}')" class="absolute bg-white top-2 right-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </x-icon-button>
                                
                                <x-modal.full name='delete-image-modal-{{ $image->id }}' maxWidth='sm'>
                                    <livewire:app.room.delete-room-image wire:key='{{ $image->id }}' :image="$image" />
                                </x-modal.full>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="w-full md:w-1/2">
                    <x-filepond::upload
                        wire:model.live="images"
                        multiple
                        placeholder="Drag & drop your images or <span class='filepond--label-action'> Browse </span>"
                    />
                </div>
                
                <x-form.input-error field="image_1_path" />
            </x-form.input-group>
        </div>

        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between">
                <hgroup>
                    <h2 class='font-semibold'>Inclusions</h2>
                    <p class='text-xs'>Manage room inclusions here</p>
                </hgroup>

                <button type="button" class="text-xs font-semibold text-blue-500" x-on:click="$dispatch('open-modal', 'add-inclusion-modal')">Add Inclusion</button>
            </div>

            <livewire:tables.room-inclusions-table :room="$room" />
        </div>
        <x-primary-button>Edit Room</x-primary-button>
    </form>

    <x-modal.full name='add-inclusion-modal' maxWidth='sm'>
      <livewire:app.room.create-inclusion :room="$room" />  
    </x-modal.full>
</div>