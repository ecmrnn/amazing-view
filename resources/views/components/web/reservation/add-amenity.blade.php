<hgroup>
    <h3 class="text-sm font-semibold">Add Amenity</h3>
    <p class="max-w-sm text-xs">Select an amenity to add and its quantity</p>
</hgroup>

<div class="p-3 bg-white border rounded-md">
    @if (!empty($available_amenities))
        <div x-data="{ 
                additonal_amenity: $wire.entangle('additional_amenity_id'),
                additional_amenity_quantity: $wire.entangle('additional_amenity_quantity'),
            }" class="sm:space-y-3">
            <div class="hidden gap-1 text-sm sm:grid-cols-3 sm:grid">
                <strong>Amenity</strong>
                <strong>Quantity &amp; Price</strong>
                <strong>Total</strong>
            </div>

            <div class="space-y-1">
                {{-- Selected Additional Amenities --}}
                <div class="grid gap-1 sm:grid-cols-3">
                    @if ($additional_amenities->count() > 0)
                        @foreach ($additional_amenities as $amenity)
                            @if ($amenity->is_addons == 0)
                                @php
                                    $quantity = 0;

                                    foreach ($additional_amenity_quantities as $selected_amenity) {
                                        if ($selected_amenity['amenity_id'] == $amenity->id) {
                                            $quantity = $selected_amenity['quantity'];
                                        }
                                    }
                                @endphp
                                
                                <x-form.select disabled>
                                    <option>{{ $amenity->name }}</option>
                                </x-form.select>

                                <div class="grid gap-1 sm:grid-cols-2">
                                    <div>
                                        <input disabled class="text-xs py-2 sm:py-0 disabled:opacity-50 w-full h-full px-2.5 border outline-0 border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600" value="{{ $quantity }}" />
                                    </div>
                                    <p class="grid px-2 text-xs border rounded-lg place-items-center">
                                        <span>
                                            <x-currency /> {{ number_format($amenity->price, 2) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="flex gap-1">
                                    <p class="grid w-full px-2 py-2 text-xs font-semibold text-blue-500 border border-blue-500 rounded-lg sm:py-0 place-items-center">
                                        <span>
                                            <x-currency /> {{ number_format($amenity->price * $quantity, 2) }}
                                        </span>
                                    </p>
                                    <x-tooltip text="Remove Amenity" dir="left">
                                        <x-secondary-button x-ref="content" type="button" class="p-0 text-xs" wire:click="removeAmenity({{ $amenity->id }})">
                                            <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </x-secondary-button>
                                    </x-tooltip>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                
                {{-- Add Additional Amenity --}}
                @if ($additional_amenities->count() != $available_amenities->count())
                    <div class="grid-cols-3 gap-1 space-y-1 sm:space-y-0 sm:grid">
                        <p class="text-xs font-semibold sm:hidden">Amenity</p>
                        <x-form.select
                            x-model="additonal_amenity"
                            wire:model.live="additional_amenity_id"
                            x-on:change="$wire.selectAmenity(additonal_amenity)"
                            >
                            <option value="">Select an Amenity</option>
                            @foreach ($available_amenities as $amenity)
                                @if (!$additional_amenities->contains('id', $amenity->id) && !$selected_amenities->contains('id', $amenity->id))
                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                @endif
                            @endforeach
                        </x-form.select>

                        <div class="grid grid-cols-2 gap-1">
                            <p class="text-xs font-semibold sm:hidden">Quantity</p>
                            <p class="text-xs font-semibold sm:hidden">Price</p>

                            <div>
                                <input
                                    x-on:change="$wire.getTotal()"
                                    x-model="additional_amenity_quantity"
                                    wire:model.live="additional_amenity_quantity"
                                    type="number"
                                    min="1"
                                    @if (!empty($additional_amenity_id))
                                        max="{{ $additional_amenity->quantity }}"
                                    @endif
                                    value="1"
                                    class="text-xs w-full h-full px-2.5 py-2 sm:py-0 border outline-0 border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-blue-600" />
                            </div>
                            <p class="grid px-2 py-2 text-xs border border-gray-300 rounded-lg sm:py-0 place-items-center">
                                @if (!empty($additional_amenity_id))
                                    <span>
                                        <x-currency /> {{ number_format($additional_amenity->price, 2) }}
                                    </span>
                                @else
                                    <span>
                                        <x-currency /> 0.00
                                    </span>
                                @endif
                            </p>
                        </div>

                        <p class="text-xs font-semibold sm:hidden">Total</p>

                        <div class="flex flex-col gap-1 sm:flex-row">
                            <p class="grid w-full px-2 py-2 text-xs font-semibold text-blue-500 border border-blue-500 rounded-lg sm:py-0 place-items-center">
                                @if (!empty($additional_amenity_id))
                                    <span>
                                        <x-currency /> {{ number_format($additional_amenity_total, 2) }}
                                    </span>
                                @else
                                    <span>
                                        <x-currency /> 0.00
                                    </span>
                                @endif
                            </p>
                            <x-tooltip text="Add Amenity" dir="left">
                                <x-primary-button x-ref="content" type="button" class="flex items-center justify-center w-full gap-3 p-0 text-sm sm:text-xs sm:w-min" wire:click="addAmenity()">
                                    <svg class="hidden mx-auto sm:block" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                                    <p class="sm:hidden">Add Amenity</p>
                                </x-primary-button>
                            </x-tooltip>
                        </div>

                        {{-- Errors --}}
                        <div class="grid-cols-3 col-span-3 gap-1 space-y-1 sm:grid sm:space-y-0">
                            <div>
                                <x-form.input-error field="additional_amenity" />
                            </div>
                            <div>
                                <x-form.input-error field="additional_amenity_quantity" />
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-xs font-semibold">
                        Selected all available amenities
                    </p>
                @endif
            </div>
        </div>
    @else
        <div class="pt-2 font-semibold text-center">
            No amenities yet.
        </div>
    @endif
</div>