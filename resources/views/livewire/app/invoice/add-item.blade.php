<div x-data="{ quantity: @entangle('quantity'), item_type: @entangle('item_type') }">
    <div class="flex items-start justify-between mb-5">
        <hgroup>
            <h2 class="font-semibold">Add Item</h2>
            <p class="text-xs">Select an item you want to add</p>
        </hgroup>

        @if ($items->count() > 0 && in_array($invoice->reservation->status, [
                \App\Enums\ReservationStatus::CONFIRMED->value,
                \App\Enums\ReservationStatus::CHECKED_IN->value,
            ]))
            <button type="button" x-on:click="$dispatch('open-modal', 'add-item-modal')" class="text-xs font-semibold text-blue-500">Add Item</button>
        @endif
    </div>

    <div class="space-y-2">
        <p class="text-xs"><strong>Note: </strong>Quantity on rooms are the duration of the guest&apos;s stay. Any changes made here will be automatically saved.</p>
        
        <div>
            @if ($items->count() > 0)
                <div class="space-y-2">
                    <x-table.table headerCount="7">
                        <x-slot:headers>
                            <p>No.</p>
                            <p>Item</p>
                            <p>Type</p>
                            <p class="text-center">Quantity</p>
                            <p class="text-right">Price</p>
                            <p class="text-right">Total</p>
                            <p></p> {{-- Action --}}
                        </x-slot:headers>
                
                        <?php $counter = 0; ?>
                        @foreach ($items as $key => $item)
                            @if ($item['type'] != 'others')
                                <?php $counter++ ?>
                                <div x-data="{ quantity: @js($item['quantity']) }" wire:key="{{ $item['id'] }}"
                                    x-init="
                                        let timeout;
                                        $watch('quantity', (value) => {
                                            clearTimeout(timeout); // Cancel the previous request if another change happens quickly
                                            timeout = setTimeout(() => { 
                                                if (value > 0) {
                                                    @this.call('updateQuantity', '{{ $item['id'] }}', value, '{{ $item['type'] }}', '{{ Arr::get($item, 'room_number', null) }}');
                                                }
                                            }, 300); // Adjust debounce delay (300ms is a good default)
                                        })"
                                    class="grid items-center grid-cols-7 px-5 py-1 text-sm hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    @if ($item['type'] == 'amenity')
                                        <div class="flex items-center gap-3 w-max">
                                            <p>{{ $item['name'] }}</p>
                                            <p class="px-2 py-1 text-xs font-semibold border rounded-md bg-slate-50 border-slate-200">{{ $item['room_number'] }}</p>
                                        </div>
                                    @else
                                        <p>{{ $item['name'] }}</p>
                                    @endif
                                    <p class="capitalize">{{ $item['type'] }}</p>
                                    @if ($item['type'] == 'amenity' && in_array(Arr::get($item, 'status', null), [
                                        App\Enums\ReservationStatus::CHECKED_IN->value,
                                        App\Enums\ReservationStatus::CONFIRMED->value,
                                    ]))
                                        <x-form.input-number x-model="quantity" max="{{ $item['max'] }}" min="1" id="quantity-{{ $key }}" name="quantity" class="text-center" />
                                    @else
                                        <p class="text-center">{{ $item['quantity'] }}</p>
                                    @endif
                                    <p class="text-right"><x-currency />{{ number_format($item['price'], 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    <div class="ml-auto text-right w-max">
                                        @if ($item['type'] == 'room' || Arr::get($item, 'status', null) == App\Enums\ReservationStatus::CHECKED_OUT->value)
                                            <x-icon-button x-ref="content" type="button" class="opacity-0 hover:cursor-default">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </x-icon-button>
                                        @else
                                            <x-tooltip text="Remove" dir="left">
                                                <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'remove-item-modal-{{ $key }}')" type="button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                </x-icon-button>
                                            </x-tooltip>
                                        @endif 
                                    </div>

                                    <x-modal.full name='remove-item-modal-{{ $key }}' maxWidth='sm'>
                                        <div class="p-5 space-y-5" x-on:item-removed.window="show = false">
                                            <hgroup>
                                                <h2 class="text-lg font-semibold text-red-500">Remove Item</h2>
                                                <p class="text-xs">Are you sure you want to remove this item?</p>
                                            </hgroup>
                
                                            <div class="flex justify-end gap-1">
                                                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                                                <x-danger-button type="button" wire:click="removeItem({{ json_encode($item) }})">Remove item</x-danger-button>
                                            </div>
                                        </div>
                                    </x-modal.full>
                                </div>
                            @endif
                        @endforeach
                    </x-table.table>

                    
                    @if (!empty($invoice))
                        @if ($invoice->items->count() > 0)
                            <h2 class="text-xs font-semibold">Other Charges</h2>
                            <div class="overflow-auto border rounded-md border-slate-200">
                                <div class="min-w-[600px]">
                                    {{-- Other Charges --}}
                                    <?php $counter = 0; ?>

                                    @foreach ($items as $key => $item)
                                        @if ($item['type'] == 'others')
                                            <?php $counter++ ?>
                                            <div x-data="{ quantity: @js($item['quantity']) }" wire:key="{{ $item['id'] }}" class="grid items-center grid-cols-7 px-5 py-1 text-sm border-b border-solid hover:bg-slate-50 last:border-b-0 border-slate-200"
                                                x-init="
                                                let timeout;
                                                $watch('quantity', (value) => {
                                                    clearTimeout(timeout); // Cancel the previous request if another change happens quickly
                                                    timeout = setTimeout(() => { 
                                                        if (value > 0) {
                                                            @this.call('updateQuantity', '{{ $item['id'] }}', value, '{{ $item['type'] }}', '{{ Arr::get($item, 'room_number', null) }}');
                                                        }
                                                    }, 300); // Adjust debounce delay (300ms is a good default)
                                                })"
                                                >
                                                <p class="font-semibold opacity-50">{{ $counter }}</p>
                                                <div class="flex items-center gap-3 w-max">
                                                    <p>{{ $item['name'] }}</p>
                                                    <p class="px-2 py-1 text-xs font-semibold border rounded-md bg-slate-50 border-slate-200">{{ $item['room_number'] }}</p>
                                                </div>
                                                <p class="capitalize"></p>
                                                @if (Arr::get($item, 'status', null) == App\Enums\ReservationStatus::CHECKED_IN->value)
                                                    <x-form.input-number x-model="quantity" min="1" id="quantity" name="quantity" class="text-center" />
                                                @else
                                                    <p class="text-center">{{ $item['quantity'] }}</p>
                                                @endif
                                                <p class="text-right"><x-currency />{{ number_format($item['price'], 2) }}</p>
                                                <p class="text-right"><x-currency />{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                                <div class="ml-auto text-right w-max">
                                                    @if (Arr::get($item, 'status', null) == App\Enums\ReservationStatus::CHECKED_OUT->value)
                                                        <x-icon-button x-ref="content" type="button" class="opacity-0 hover:cursor-default">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                        </x-icon-button>
                                                    @else
                                                        <x-tooltip text="Remove" dir="left">
                                                            <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'remove-item-modal-{{ $key }}')" type="button">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                            </x-icon-button>
                                                        </x-tooltip>
                                                    @endif 
                                                </div>

                                                <x-modal.full name='remove-item-modal-{{ $key }}' maxWidth='sm'>
                                                    <div class="p-5 space-y-5" x-on:item-removed.window="show = false">
                                                        <hgroup>
                                                            <h2 class="text-lg font-semibold">Remove Item</h2>
                                                            <p class="text-xs">Are you sure you want to remove this item?</p>
                                                        </hgroup>
                                                        <div class="flex justify-end gap-1">
                                                            <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                                                            <x-danger-button type="button" wire:click="removeItem({{ json_encode($item) }})">Remove item</x-danger-button>
                                                        </div>
                                                    </div>
                                                </x-modal.full>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                    
                    <!-- Taxes -->
                    <div class="flex justify-end text-sm">
                        <table class="w-max">
                            <tr>
                                <td class="pr-5 font-semibold text-right">Subtotal</td>
                                <td class="text-right"><x-currency />{{ number_format($breakdown['sub_total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="pt-5 pr-5 text-right">Vatable Sales</td>
                                <td class="pt-5 text-right"><x-currency />{{ number_format($breakdown['taxes']['vatable_sales'], 2) }}</td>
                            </tr>
                            @if ($breakdown['taxes']['vatable_exempt_sales'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Vatable Exempt Sales</td>
                                    <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['vatable_exempt_sales'], 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="pr-5 text-right">VAT</td>
                                <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['vat'], 2) }}</td>
                            </tr>
                            @if ($breakdown['taxes']['other_charges'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Other Charges</td>
                                    <td class="text-right"><x-currency />{{ number_format($breakdown['taxes']['other_charges'], 2) }}</td>
                                </tr>
                            @endif
                            @if ($breakdown['taxes']['discount'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Discount</td>
                                    <td class="text-right"><x-currency />&lpar;{{ number_format($breakdown['taxes']['discount'], 2) }}&rpar;</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="pt-5 pr-5 font-semibold text-right text-blue-500">Net Total</td>
                                <td class="pt-5 font-semibold text-right text-blue-500"><x-currency />{{ number_format($breakdown['taxes']['net_total'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <div class="py-10 space-y-3 text-sm font-semibold text-center border rounded-md border-slate-200">
                    <p>No items added yet!</p>
                    <x-primary-button type="button" x-on:click="$dispatch('open-modal', 'add-item-modal')">Add Item</x-primary-button>
                </div>
            @endif
        </div>
    </div>

    <x-modal.full name='add-item-modal' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:item-added.window="show = false">
            @if (empty($item_type))
                <hgroup>
                    <h2 class="text-lg font-semibold">Select an Item</h2>
                    <p class="text-xs">Select an item to add</p>
                </hgroup>

                <button type="button" class="flex items-center justify-between w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" wire:click='selectItem("others")'">
                    <div>
                        <p class="font-semibold text-md">Others</p>
                        <p class="text-xs">For custom items that require specific rates or prices.</p>
                    </div>
                    <div class="pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                    </div>
                </button>

                <button type="button" class="flex items-center justify-between w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" wire:click='selectItem("amenity")'">
                    <div>
                        <p class="font-semibold text-md">Amenities</p>
                        <p class="text-xs">For availed room amenities of the guests.</p>
                    </div>
                    <div class="pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-concierge-bell"><path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1Z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/></svg>
                    </div>
                </button>

                <button type="button" class="flex items-center justify-between w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" wire:click='selectItem("service")'">
                    <div>
                        <p class="font-semibold text-md">Services</p>
                        <p class="text-xs">For availed room services  of the guests.</p>
                    </div>
                    <div class="pr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dog"><path d="M11.25 16.25h1.5L12 17z"/><path d="M16 14v.5"/><path d="M4.42 11.247A13.152 13.152 0 0 0 4 14.556C4 18.728 7.582 21 12 21s8-2.272 8-6.444a11.702 11.702 0 0 0-.493-3.309"/><path d="M8 14v.5"/><path d="M8.5 8.5c-.384 1.05-1.083 2.028-2.344 2.5-1.931.722-3.576-.297-3.656-1-.113-.994 1.177-6.53 4-7 1.923-.321 3.651.845 3.651 2.235A7.497 7.497 0 0 1 14 5.277c0-1.39 1.844-2.598 3.767-2.277 2.823.47 4.113 6.006 4 7-.08.703-1.725 1.722-3.656 1-1.261-.472-1.855-1.45-2.239-2.5"/></svg>
                    </div>
                </button>

                <x-loading wire:loading.delay wire:target='selectItem'>Getting available items, please wait</x-loading>
            @else
                <div class="flex items-start justify-between">
                    <hgroup>
                        <h2 class="text-lg font-semibold">Add {{ ucwords($item_type) }}</h2>
                        <p class="text-xs">Enter the necessary details for your item</p>
                    </hgroup>

                    <button type='button' class="text-xs font-semibold text-blue-500" x-on:click="$wire.set('item_type', null);">Change Item</button>
                </div>

                <div>
                    @switch($item_type)
                        @case('amenity')
                            @if ($amenities->count() > 0)
                                <x-form.input-group>
                                    <x-form.input-label for='amenity'>Select an Amenity</x-form.input-label>
                                    <x-form.select wire:model.live='amenity' id="amenity" name="amenity" wire:change='selectedItem'>
                                        <option value="">Select an Amenity</option>
                                        @foreach ($amenities as $amenity)
                                            @if ($items->doesntContain(function ($item) use ($amenity, $invoice, $room_number) {
                                                return Arr::get($item, 'room_number', null) == $room_number && $amenity->id == $item['id'];
                                            }))
                                                <option value="{{ $amenity->id }}" class="capitalize">{{ $amenity->name }}</option>
                                            @endif
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error field="amenity" />
                                </x-form.input-group>
                            @else
                                <div>
                                    <h2 class="font-semibold">All Amenities Selected!</h2>
                                    <p class="text-xs">Edit the quantity of the amenity using the table</p>
                                </div>
                            @endif
                            @break
                        @case('service')
                            @if ($services->count() > 0)
                                <x-form.input-group>
                                    <x-form.input-label for='service'>Select a Service</x-form.input-label>
                                    <x-form.select wire:model.live='service' id="service" name="service" wire:change='selectedItem'>
                                        <option value="">Select a Service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" class="capitalize">{{ $service->name }}</option>
                                        @endforeach
                                    </x-form.select>
                                    <x-form.input-error field="service" />
                                </x-form.input-group>
                            @else
                                <div>
                                    <h2 class="font-semibold">All Services Selected!</h2>
                                    <p class="text-xs">Modify the services availed using the table</p>
                                </div>
                            @endif
                            @break
                        @case('others')
                            <x-form.input-group>
                                <x-form.input-label for='name'>Enter Item Description</x-form.input-label>
                                <x-form.input-text wire:model.live='name' id="name" name="name" label="Item Name" />
                                <x-form.input-error field="name" />
                            </x-form.input-group>
                            @break
                    @endswitch
                </div>
                
                <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                    @if ($item_type != 'service')
                        <x-form.input-group>
                            <x-form.input-label for='room_number'>Select a Room</x-form.input-label>
                            <x-form.select wire:model.live='room_number' id="room_number" name="room_number" wire:change='selectRoom'>
                                @foreach ($invoice->reservation->rooms as $room)
                                    @if ($room->pivot->status == App\Enums\ReservationStatus::CHECKED_IN->value)
                                        <option value="{{ $room->room_number }}">{{ $room->room_number }}</option>
                                    @endif
                                @endforeach
                            </x-form.select>
                            <x-form.input-error field="room_number" />
                        </x-form.input-group>
                        
                        <x-form.input-group>
                            <x-form.input-label for='quantity'>Quantity</x-form.input-label>
                            <x-form.input-number x-bind:disabled="item_type == 'service'" min="1" x-model="quantity" wire:model.live='quantity' id="quantity" name="quantity" max="{{ $max }}" label="Item Name" />
                            <x-form.input-error field="quantity" />
                            @if ($max != 99999 && $item_type == 'amenity')
                            <p class="text-xs">Remaining: {{ $max }}</p>
                            @endif
                        </x-form.input-group>
                    @endif
                        
                    <x-form.input-group>
                        <x-form.input-label for='price'>Price</x-form.input-label>
                        <x-form.input-currency x-bind:disabled="item_type != 'others'" wire:model.live='price' id="price" name="price" label="Item Name" />
                        <x-form.input-error field="price" />
                    </x-form.input-group>
                </div>

                <div class="flex justify-end gap-1">
                    <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                    <x-primary-button type="button" wire:click='addItem'>Add item</x-primary-button>
                </div>
            @endif
        </div>
    </x-modal.full>
</div>
