<div x-data="{ quantity: @entangle('quantity'), item_type: @entangle('item_type') }" class="space-y-5">
    <div class="flex items-center justify-between">
        <hgroup>
            <h2 class="font-semibold">Add Item</h2>
            <p class="text-xs">Select an item you want to add</p>
        </hgroup>

        @if ($items->count() > 0)
            <x-primary-button type="button" x-on:click="$dispatch('open-modal', 'add-item-modal')">Add Item</x-primary-button>
        @endif
    </div>

    <div class="space-y-2">
        <p class="text-xs"><strong>Note: </strong>Quantity on rooms are the duration of the guest&apos;s stay. Any changes made here will be automatically saved.</p>
        
        <div>
            @if ($items->count() > 0)
                <div class="space-y-2">
                    <div class="overflow-auto border rounded-md border-slate-200">
                        <div class="min-w-[600px]">
                            <div class="grid grid-cols-7 px-5 py-3 text-sm font-semibold border-b bg-slate-50 text-zinc-800/60 border-slate-200">
                                <p>No.</p>
                                <p>Item</p>
                                <p>Type</p>
                                <p class="text-center">Quantity</p>
                                <p class="text-right">Price</p>
                                <p class="text-right">Total</p>
                                <p></p> {{-- Action --}}
                            </div>
                    
                            <div>
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
                                                            @this.call('updateQuantity', '{{ $item['id'] }}', value, '{{ $item['type'] }}');
                                                        }
                                                    }, 300); // Adjust debounce delay (300ms is a good default)
                                                })"
                                            class="grid items-center grid-cols-7 px-5 py-1 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                                            <p class="font-semibold opacity-50">{{ $counter }}</p>
                                            <p>{{ $item['name'] }}</p>
                                            <p class="capitalize">{{ $item['type'] }}</p>
                                            @if ($item['type'] == 'amenity')
                                                <x-form.input-number x-model="quantity" max="{{ $item['max'] }}" min="1" id="quantity" name="quantity" class="text-center" />
                                            @else
                                                <p class="text-center">{{ $item['quantity'] }}</p>
                                            @endif
                                            <p class="text-right"><x-currency />{{ number_format($item['price'], 2) }}</p>
                                            <p class="text-right"><x-currency />{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                            <div class="ml-auto text-right w-max">
                                                @if ($item['type'] == 'room')
                                                    <x-icon-button disabled x-ref="content" x-on:click="$dispatch('open-modal', 'remove-item-modal-{{ $key }}')" type="button">
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
                    </div>

                    
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
                                            <div x-data="{ quantity: @js($item['quantity']) }" wire:key="{{ $item['id'] }}" class="grid items-center grid-cols-7 px-5 py-1 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200"
                                                x-init="
                                                let timeout;
                                                $watch('quantity', (value) => {
                                                    clearTimeout(timeout); // Cancel the previous request if another change happens quickly
                                                    timeout = setTimeout(() => { 
                                                        if (value > 0) {
                                                            @this.call('updateQuantity', '{{ $item['id'] }}', value, '{{ $item['type'] }}');
                                                        }
                                                    }, 300); // Adjust debounce delay (300ms is a good default)
                                                })"
                                                >
                                                <p class="font-semibold opacity-50">{{ $counter }}</p>
                                                <p>{{ $item['name'] }}</p>
                                                <p class="capitalize">{{ $item['type'] }}</p>
                                                <x-form.input-number x-model="quantity" min="1" id="quantity" name="quantity" class="text-center" />
                                                <p class="text-right"><x-currency />{{ number_format($item['price'], 2) }}</p>
                                                <p class="text-right"><x-currency />{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                                <div class="ml-auto text-right w-max">
                                                    <x-tooltip text="Remove" dir="left">
                                                        <x-icon-button x-ref="content" x-on:click="$dispatch('open-modal', 'remove-item-modal-{{ $key }}')" type="button">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                        </x-icon-button>
                                                    </x-tooltip>
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
            <hgroup>
                <h2 class="text-lg font-semibold">Add Item</h2>
                <p class="text-xs">Select an item to add</p>
            </hgroup>

            <div class="grid gap-2 p-5 bg-white border rounded-lg border-slate-200">
                <x-form.input-radio wire:model.live='item_type' wire:change='selectItem' id="others" name="item_type" value="others" label="Others" />
                <x-form.input-radio wire:model.live='item_type' wire:change='selectItem' id="amenity" name="item_type" value="amenity" label="Amenity" />
                <x-form.input-radio wire:model.live='item_type' wire:change='selectItem' id="service" name="item_type" value="service" label="Service" />
            </div>

            @if ($item_type)
                <div>
                    @switch($item_type)
                        @case('amenity')
                            @if ($amenities->count() > 0)
                                <x-form.input-group>
                                    <x-form.input-label for='amenity'>Select an Amenity</x-form.input-label>
                                    <x-form.select wire:model.live='amenity' id="amenity" name="amenity" wire:change='selectedItem'>
                                        @foreach ($amenities as $amenity)
                                            <option value="{{ $amenity->id }}" class="capitalize">{{ $amenity->name }}</option>
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
            @endif
            
            <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
                <x-form.input-group>
                    <x-form.input-label for='quantity'>Quantity</x-form.input-label>
                    <x-form.input-number x-bind:disabled="item_type == 'service'" min="1" x-model="quantity" wire:model.live='quantity' id="quantity" name="quantity" max="{{ $max }}" label="Item Name" />
                    <x-form.input-error field="quantity" />
                    <p x-show="item_type == 'amenity'" class="text-xs">Remaining: {{ $max }}</p>
                </x-form.input-group>
    
                <x-form.input-group>
                    <x-form.input-label for='price'>Price</x-form.input-label>
                    <x-form.input-currency x-bind:disabled="item_type != 'others'" wire:model.live='price' id="price" name="price" label="Item Name" />
                    <x-form.input-error field="price" />
                </x-form.input-group>
            </div>

            <x-loading wire:loading.delay wire:target='selectItem'>Getting available items, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='addItem'>Add item</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</div>
