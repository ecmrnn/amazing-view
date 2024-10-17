@props([
    'addons' => [],
])

<x-form.form-section>
    <x-form.form-header step="4" title="Additional Details" />
    
    <div x-show="can_add_amenity" x-collapse.duration.1000ms>
        <x-form.form-body>
            {{-- Addons --}}
            <div class="p-5 space-y-3">
                <div class="space-y-3">
                    <hgroup>
                        <h2 class="text-sm font-semibold">Reservation Addons</h2>
                        <p class="max-w-xs text-xs">Add fees or services to the customer&apos;s reservation.
                        </p>
                    </hgroup>
                    <div class="grid gap-1 sm:grid-cols-2 lg:grid-cols-4">
                        @forelse ($addons as $addon)
                            <div key="{{ $addon->id }}">
                                <x-form.checkbox-toggle id="amenity{{ $addon->id }}" name="amenity" wire:click="toggleAmenity({{ $addon->id }})">
                                    <div class="px-3 py-2 select-none">
                                        <div class="w-full font-semibold capitalize text-md">
                                            {{ $addon->name }}
                                        </div>
                                        <div class="w-full text-xs">Standard Fee: &#8369;{{ $addon->price }}
                                        </div>
                                    </div>
                                </x-form.checkbox-toggle>
                            </div>
                        @empty
                            <div
                                class="py-5 text-sm font-semibold text-center border rounded-lg sm:col-span-2 lg:col-span-4 text-zinc-800/50">
                                No add ons yet...</div>
                        @endforelse
                    </div>
                </div>
        
                @include('components.web.reservation.add-amenity', [
                    'additional_amenities' => $selected_amenities
                ])
        
                <div class="flex items-center gap-1">
                    <x-secondary-button type="button" x-on:click="can_select_room = true; can_add_amenity = false">Edit Rooms</x-secondary-button>
                    <x-primary-button type="button" x-on:click="can_submit_payment = true; can_add_amenity = false">Payment</x-primary-button>
                    <p class="max-w-xs text-xs font-semibold" wire:loading.delay wire:target="sendPayment()">Please wait while we load the next form.</p>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>