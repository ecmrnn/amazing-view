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
                <h2 class="text-lg font-semibold">Edit Room ({{ $room->room_number }})</h2>
                <p class="max-w-sm text-xs">Update your room details here</p>
            </hgroup>
        </div>

        @if ($room->status == App\Enums\RoomStatus::AVAILABLE->value)
            <x-actions>
                <x-action-button>
                    Hello
                </x-action-button>
            </x-actions>
        @endif
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
                <!-- Image -->
                <x-form.input-group>
                    <div>
                        <x-form.input-label for="image_1_path">Image</x-form.input-label>
                        <p class="text-xs">Upload an image of your new room here</p>
                    </div>

                    <x-img src="{{ $room->image_1_path }}" />

                    <x-filepond::upload
                        wire:model.live="image_1_path"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="image_1_path" />
                </x-form.input-group>
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

        <x-primary-button>Edit Room</x-primary-button>
    </form>
</div>