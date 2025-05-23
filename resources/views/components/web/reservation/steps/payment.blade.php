{{-- Loader --}}
<div class="fixed top-0 left-0 z-[9999] w-screen h-screen bg-white place-items-center" wire:loading.delay.longest wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Processing your amazing reservation~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="grid gap-5 lg:grid-cols-3">
    <div class="p-5 space-y-5 overflow-hidden bg-white rounded-lg shadow-sm lg:col-span-2">
        {{-- Step Header --}}
        <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
            <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                <p class="text-5xl font-bold">3</p>
            </div>
    
            <div>
                <p class="text-lg font-bold">Payment</p>
                <p class="max-w-sm text-sm leading-tight">Review your reservation details or if you can, you may upload an image of your downpayment</p>
            </div>
        </div>

        <x-note>A minimum of <strong><x-currency />{{ number_format($minimum_payment, 2) }}</strong> must be paid to process your reservation. Kindly send an image of your receipt to the email we will send to your email<strong>{{ ' ' . $email }}</strong> or <strong>upload</strong> an image on the dropbox below. </x-note>
    
        <div class="space-y-5">
            <hgroup>
                <h2 class="font-semibold">Reservation Summary</h2>
                <p class="text-sm">Kindly check if the information you provided is <strong class="text-blue-500">correct</strong>.</p>
            </hgroup>

            {{-- Guest Details --}}
            <div class="p-5 space-y-5 border rounded-md border-slate-200">
                <h3 class="font-semibold">Guest Details</h3>

                <table class="text-sm table-fixed">
                    <tr>
                        <th class="font-semibold text-left">Name</th>
                        <td class="capitalize">{{ $first_name . " " . $last_name }}</td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Contact Number</th>
                        <td class="capitalize">{{ $phone }}</td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Email</th>
                        <td>{{ $email }}</td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Address</th>
                        <td class="capitalize">{{ is_array($address) ? trim(implode(', ', $address), ',') : $address }}</td>
                    </tr>
                </table>
            </div>

            {{-- Reservation Details --}}
            <div class="p-5 space-y-5 border rounded-md border-slate-200">
                <h3 class="font-semibold">Reservation Details</h3>

                <table class="text-sm table-fixed">
                    <tr>
                        <th class="font-semibold text-left">Check-in Details</th>
                        <td class="capitalize">{{ date_format(date_create($date_in), 'F j, Y') }} -
                            @if ($date_in == $date_out)
                                8:00 AM
                            @else
                                2:00 PM
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Check-out Details</th>
                        <td class="capitalize">{{ date_format(date_create($date_out), 'F j, Y') }} - 
                            @if ($date_in == $date_out)
                                6:00 PM
                            @else
                                12:00 PM
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Number of Guests</th>
                        <td>
                            <span x-text="adult_count"></span> <span>Adult<span x-show="adult_count > 1">s</span></span>
                            <span x-show="children_count > 0">&amp; <span x-text="children_count"></span> <span>Child<span x-show="children_count > 1">ren</span></span></span>
                        </td>
                    </tr>
                    <tr>
                        <th class="font-semibold text-left">Rooms</th>
                        <td class="capitalize">
                            @foreach ($selected_rooms as $room)
                                <p>
                                    {{ $room->room_number . ' - ' . $room->roomType->name }}
                                </p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Breakdown --}}
            <div class="w-full space-y-2">
                <p class="text-xs"><strong>Note:</strong> Quantity on rooms are the total nights the guest will stay.</p>
                <div class="w-full overflow-auto border rounded-md border-slate-200">
                    <div class="min-w-[600px]">
                        <div class="grid grid-cols-6 px-5 py-3 text-sm font-semibold bg-slate-50 text-zinc-800/60 border-slate-200">
                            <p>No.</p>
                            <p>Item</p>
                            <p>Type</p>
                            <p class="text-center">Quantity</p>
                            <p class="text-right">Price</p>
                            <p class="text-right">Total</p>
                        </div>
                
                        <div>
                            <?php $counter = 0; ?>
                            <!-- Rooms -->
                            @foreach ($selected_rooms as $room)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-t border-solid hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $room->room_number}}</p>
                                    <p>Room</p>
                                    <p class="text-center">{{ $night_count }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($room->rate, 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($room->rate * $night_count, 2) }}</p>
                                </div>
                            @endforeach
                            <!-- Services -->
                            @foreach ($selected_services as $service)
                                <?php $counter++ ?>
                                <div class="grid grid-cols-6 px-5 py-3 text-sm border-t border-solid hover:bg-slate-50 border-slate-200">
                                    <p class="font-semibold opacity-50">{{ $counter }}</p>
                                    <p>{{ $service->name }}</p>
                                    <p>Service</p>
                                    <p class="text-center">1</p>
                                    <p class="text-right"><x-currency />{{ number_format($service->price, 2) }}</p>
                                    <p class="text-right"><x-currency />{{ number_format($service->price, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Taxes --}}
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
    </div>
    
    <aside class="self-start p-5 space-y-5 bg-white rounded-lg shadow-sm">
        <hgroup>
            <h3 class="text-lg font-semibold">Submit Payment</h3>
            <p class="text-sm">Upload your receipt here so that our staff can confirm your payment</p>
        </hgroup>

        <div class="flex items-center gap-3" x-on:click="$dispatch('open-modal', 'show-discounts-modal')">
            <x-icon-button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-percent-icon lucide-badge-percent"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m15 9-6 6"/><path d="M9 9h.01"/><path d="M15 15h.01"/></svg>
            </x-icon-button>
            
            <div>
                <p class="text-sm font-semibold">Apply Discounts</p>
                <p class="text-xs">Save a peso, apply discount!</p>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="grid">
            <div class="flex items-center gap-3 p-3 bg-white border rounded-lg border-slate-200">
                <x-img src="{{ $settings['site_gcash_qr'] ?? '' }}" alt="QR Code" class="max-w-20" aspect="square" />
                
                <div>
                    <h3 class="text-xs">GCash</h3>

                    <div class="flex items-center gap-5">
                        <div>
                            <p class="font-semibold">{{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}</p>
                            <p class="text-xs">{{ Arr::get($settings, 'site_gcash_name', 'Fabio Basbaño') }}</p>
                        </div>
                        <x-copy text="{{ Arr::get($settings, 'site_gcash_phone', '09171399334') }}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">
            <x-filepond::upload
                wire:model.live="proof_image_path"
                placeholder="Drag & drop your images or <span class='filepond--label-action'> Browse </span>"
            />
            <x-form.input-error field="proof_image_path" />
            <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
        </div>
        
        <div class="flex justify-end gap-1">
            <x-secondary-button wire:click="submit(true)">Back</x-secondary-button>
            <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Submit</x-primary-button>
        </div>
    </aside>
</div>

<x-modal.full name='show-discounts-modal' maxWidth='sm'>
    <div x-data="{ discount: '' }" class="p-5" x-on:discount-applied.window="show = false">
        <div x-show="discount == ''" class="space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Apply Discounts</h2>
                <p class='text-xs'>Select what type of discounts you want to apply</p>
            </hgroup>
            
            <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'promo'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/></svg>
                
                <div>
                    <h3 class="font-semibold">Promo Code</h3>
                    <p class="text-xs">Enter your promo code</p>
                </div>
            </button>

            <button type="button" class="inline-flex items-center w-full gap-5 p-5 text-left bg-white border rounded-md border-slate-200" x-on:click="discount = 'discount'">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-accessibility-icon lucide-accessibility"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>

                <div>
                    <h3 class="font-semibold">Senior and PWDs</h3>
                    <p class="text-xs">Avail 20&#37; discount</p>
                </div>
            </button>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
            </div>
        </div>

        <div x-show="discount == 'promo'" class="space-y-5">
            <hgroup>
                <h2 class='font-semibold'>Promo Code</h2>
                <p class='text-xs'>Enter your promo code to avail discounts!</p>
            </hgroup>

            <x-form.input-group>
                <x-form.input-label for='promo_code'>Enter promo code here</x-form.input-label>
                <x-form.input-text id="promo_code" name="promo_code" label="Promo Code" class="uppercase" wire:model.live='promo_code' />
                <x-form.input-error field="promo_code" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='applyPromo'>Checking promo, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyPromo'>Apply</x-primary-button>
            </div>
        </div>

        <div x-show="discount == 'discount'" class="space-y-5">
            <hgroup>
                <h2 class="font-semibold">Senior and PWDs</h2>
                <p class="text-xs">The number of seniors and PWDs are limited to the number of guests you have.</p>
            </hgroup>

            <div class="grid grid-cols-2 gap-5">
                <x-form.input-group>
                    <x-form.input-label for='senior_count'>Number of Seniors</x-form.input-label>
                    <x-form.input-number x-model="senior_count" id="senior_count" name="senior_count" label="Seniors" />
                </x-form.input-group>
            
                <x-form.input-group>
                    <x-form.input-label for='pwd_count'>Number of PWDs</x-form.input-label>
                    <x-form.input-number x-model="pwd_count" id="pwd_count" name="pwd_count" label="PWD" />
                </x-form.input-group>
            </div>

            <x-form.input-group>
                <x-filepond::upload
                    wire:model="discount_attachments"
                    multiple
                    x-bind:max-files="pwd_count + senior_count"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />
                <x-form.input-error field="discount_attachments" />
                <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">3MB or 3000kb</strong>&rpar;</p>
            </x-form.input-group>
            
            <x-form.input-error field="senior_count" />
            <x-form.input-error field="pwd_count" />

            <x-note>This will not be immediately applied and will require confirmation first upon your arrival at the resort.</x-note>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" x-on:click="discount = ''">Cancel</x-secondary-button>
                <x-primary-button type="button" wire:click='applyDiscount'>Save</x-primary-button>
            </div>
        </div>
    </div>
</x-modal.full>