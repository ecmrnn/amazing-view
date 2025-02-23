<div x-data="{ quantity: @entangle('quantity') }" class="space-y-5">
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
        <p class="text-xs"><strong>Note: </strong> Discounts are not applied here. 
            @if (!empty($invoice))
                Any changes made here will be automatically saved.
            @endif
        </p>
        <div>
            @if ($items->count() > 0)
                <div class="space-y-3">
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
                                        <div wire:key="{{ $item['id'] }}" class="grid items-center grid-cols-7 px-5 py-1 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                                            <p class="font-semibold opacity-50">{{ $counter }}</p>
                                            <p>{{ $item['name'] }}</p>
                                            <p class="capitalize">{{ $item['type'] }}</p>
                                            <p class="text-center">{{ $item['quantity'] }}</p>
                                            <p class="text-right"><x-currency /> {{ number_format($item['price'], 2) }}</p>
                                            <p class="text-right"><x-currency /> {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
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
                                            <div wire:key="{{ $item['id'] }}" class="grid items-center grid-cols-7 px-5 py-1 text-sm border-b border-dashed hover:bg-slate-50 last:border-b-0 border-slate-200">
                                                <p class="font-semibold opacity-50">{{ $counter }}</p>
                                                <p>{{ $item['name'] }}</p>
                                                <p class="capitalize">{{ $item['type'] }}</p>
                                                <p class="text-center">{{ $item['quantity'] }}</p>
                                                <p class="text-right"><x-currency /> {{ number_format($item['price'], 2) }}</p>
                                                <p class="text-right"><x-currency /> {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
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
                                <td class="text-right"><x-currency /> {{ number_format($breakdown['sub_total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="pt-5 pr-5 text-right">Vatable Sales</td>
                                <td class="pt-5 text-right"><x-currency /> {{ number_format($breakdown['taxes']['vatable_sales'], 2) }}</td>
                            </tr>
                            @if ($breakdown['taxes']['vatable_exempt_sales'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Vatable Exempt Sales</td>
                                    <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['vatable_exempt_sales'], 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="pr-5 text-right">VAT</td>
                                <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['vat'], 2) }}</td>
                            </tr>
                            @if ($breakdown['taxes']['other_charges'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Other Charges</td>
                                    <td class="text-right"><x-currency /> {{ number_format($breakdown['taxes']['other_charges'], 2) }}</td>
                                </tr>
                            @endif
                            @if ($breakdown['taxes']['discount'] > 0)
                                <tr>
                                    <td class="pr-5 text-right">Discount</td>
                                    <td class="text-right"><x-currency /> &lpar;{{ number_format($breakdown['taxes']['discount'], 2) }}&rpar;</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="pt-5 pr-5 font-semibold text-right text-blue-500">Net Total</td>
                                <td class="pt-5 font-semibold text-right text-blue-500"><x-currency /> {{ number_format($breakdown['taxes']['net_total'], 2) }}</td>
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

            <x-form.select id="item_type" name="item_type" label="Item Type" wire:model.live="item_type">
                <option value="">Select an item type</option>
                <option value="amenity">Amenity</option>
                <option value="service">Service</option>
                <option value="others">Others</option>
            </x-form.select>

            @switch($item_type)
                @case('amenity')
                    Amenity
                    @break
                @case('service')
                    Service
                    @break
                @case('others')
                    <div class="p-5 space-y-5 border rounded-md border-slate-200">
                        <x-form.input-group>
                            <x-form.input-label for='name'>Enter Item Description</x-form.input-label>
                            <x-form.input-text wire:model.live='name' id="name" name="name" label="Item Name" />
                            <x-form.input-error field="name" />
                        </x-form.input-group>

                        <x-form.input-group>
                            <x-form.input-label for='quantity'>Quantity</x-form.input-label>
                            <x-form.input-number x-model="quantity" wire:model.live='quantity' id="quantity" name="quantity" label="Item Name" />
                            <x-form.input-error field="quantity" />
                        </x-form.input-group>

                        <x-form.input-group>
                            <x-form.input-label for='price'>Price</x-form.input-label>
                            <x-form.input-currency wire:model.live='price' id="price" name="price" label="Item Name" />
                            <x-form.input-error field="price" />
                        </x-form.input-group>
                    </div>
                    @break
                @default
                    
            @endswitch

            <div class="flex justify-end gap-1">
                <x-secondary-button x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='addItem'>Add item</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</div>
