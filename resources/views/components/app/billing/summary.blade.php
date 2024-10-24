@props([
    'selected_amenities' => [],
    'selected_rooms' => []
])

<div class="p-10 space-y-5 bg-white min-w-[800px]">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-5">
            <x-application-logo class="rounded-full" />

            <hgroup>
                <h2 class="text-xl font-semibold">Amazing View Mountain Resort</h2>
                <address class="text-xs not-italic">Little Baguio, Paagahan Mabitac, Laguna, Philippines</address>
            </hgroup>
        </div>

        <hgroup class="text-right">
            @if (!empty($reservation->invoice))
                <h2 class="text-lg font-semibold">{{ $reservation->invoice->iid }}</h2>
            @else    
                <h2 class="text-lg font-semibold">IYYMMDD0000X</h2>
            @endif
            <p class="text-xs">Invoice ID</p>
        </hgroup>
    </header>

    <div class="h-[1px] border-b border-dotted"></div>

    <div>
        <div class="flex gap-5 text-sm">
            <p class="w-20 font-semibold">Issue Date</p>
            <p>:</p>
            <p class="" x-text="formatDate(issue_date)"></p>
        </div>

        <div class="flex gap-5 text-sm">
            <p class="w-20 font-semibold">Due Date</p>
            <p>:</p>
            <p class="" x-text="formatDate(due_date)"></p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <section class="p-5 space-y-3 border rounded-md">
            <h3 class="text-sm font-semibold">Reservation Details</h3>

            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <p x-show="rid != '' && rid != null" class="text-sm font-semibold" x-text="rid"></p>
                    <x-form.text-loading x-show="rid == '' || rid == null" class="w-1/3" />
                    <p class="text-xs text-zinc-800">Reservation ID</p>
                </div>

                <div>
                    <p x-show="date_in != '' && date_in != null" class="text-sm font-semibold" x-text="formatDate(date_in)"></p>
                    <x-form.text-loading x-show="date_in == '' || date_in == null" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Check-in Date</p>
                </div>
        
                <div>
                    <p x-show="date_out != '' && date_out != null" class="text-sm font-semibold" x-text="formatDate(date_out)"></p>
                    <x-form.text-loading x-show="date_out == '' || date_out == null" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Check-out Date</p>
                </div>
        
                <div>
                    <p x-show="adult_count != '' && adult_count != 0" class="text-sm font-semibold">
                        <span x-text="adult_count"></span> Adult<span x-show="adult_count > 1">s</span><span x-show="children_count > 0">, <span x-text="children_count"></span> Child<span x-show="children_count > 1">ren</span></span></p>
                    <x-form.text-loading x-show="adult_count == '' || adult_count == 0" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Number of Guests</p>
                </div>
            </div>
        </section>

        <section class="p-5 space-y-3 border rounded-md">
            <h3 class="text-sm font-semibold">Guest Details</h3>
            
            <div class="space-y-5">
                <div>
                    <p x-show="(first_name != '' && first_name != null) || (last_name != '' && last_name != null)"class="text-sm font-semibold">
                        <span class="capitalize" x-text="first_name"></span> <span class="capitalize" x-text="last_name"></span> <span x-show="email != '' && email != null">&lpar;<span x-text="email"></span>&rpar;</span></p>
                    <x-form.text-loading x-show="(first_name == '' || first_name == null) && (last_name == '' || last_name == null)" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Name &lpar;Email&rpar;</p>
                </div>
                <div>
                    <p x-show="phone != '' &&  phone != null" class="text-sm font-semibold" x-text="phone"></p>
                    <x-form.text-loading x-show="phone == '' || phone == null" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Contact Number</p>
                </div>
                <div>
                    <p x-show="address != '' && address != null" class="text-sm font-semibold">
                        <template x-for="item in address">
                            <span x-text="item"></span>
                        </template>
                    </p>
                    <x-form.text-loading x-show="address == '' || address == null" class="w-1/2" />
                    <p class="text-xs text-zinc-800">Address</p>
                </div>
            </div>
        </section>
    </div>

    {{-- Breakdown --}}
    <div class="p-5 space-y-3 border rounded-lg">
        {{-- Header --}}
        <div class="grid grid-cols-2 pb-3 text-sm font-semibold border-b border-dotted">
            <p>Description</p>
            <div class="grid grid-cols-3 place-items-end">
                <p>Quantity</p>
                <p>Amount</p>
                <p>Total</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="space-y-1">
            @forelse ($selected_rooms as $room)
                <div class="grid grid-cols-2 text-xs">
                    <p>{{ $room->building->prefix . ' ' . $room->room_number }}</p>

                    <div class="grid grid-cols-3 place-items-end">
                        <p>&lpar;night<span x-show="night_count > 1">s</span>&rpar; {{ $night_count }}</p>
                        <p>{{ number_format($room->rate, 2) }}</p>
                        <p>{{ number_format($room->rate * $night_count, 2) }}</p>
                    </div>
                </div>
            @empty
                <div class="flex justify-between">
                    <x-form.text-loading class="w-1/3" />
                    <x-form.text-loading class="w-20" />
                </div>
            @endforelse
        </div>

        <div class="space-y-1">
            @if ($selected_amenities->count() > 0)
                {{-- Old Selected Amenities --}}
                @foreach ($additional_amenities as $amenity)
                    <div class="grid grid-cols-2 text-xs">
                        <p class="uppercase">{{ $amenity->name }}</p>

                        <div class="grid grid-cols-3 place-items-end">
                            <p>
                                @php
                                    $quantity = 1;

                                    // If quantity is 0, change it to 1
                                    $quantity != 0 ?: $quantity = 1;
                                    
                                    foreach ($additional_amenity_quantities as $selected_amenity) {
                                        if ($selected_amenity['amenity_id'] == $amenity->id) {
                                            $quantity = $selected_amenity['quantity'];
                                            break;
                                        }
                                    }
                                @endphp
                                
                                <span>{{ $quantity }}</span>
                            </p>
                            <p>{{ number_format($amenity->price, 2) }}</p>
                            <p>{{ number_format($amenity->price * $quantity, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Balance --}}
    <div class="flex justify-end gap-5 px-5">
        <div class="text-right">
            <p class="text-xs">Vatable Sales</p>
            <p class="text-xs">VAT Amount</p>
            <p class="text-xs font-semibold text-blue-500">Total Amount Due</p>
            @if ($discount_amount > 0)
                <p class="py-1 text-xs"></p>
                
                @foreach ($selected_discounts as $index => $discount)
                    <p class="text-xs">
                        @if ($index == 0)
                            {{ __('Less') }} 
                        @endif
                        
                        {{ ucwords(strtolower($discount->name)) }}</p>
                @endforeach

                <p class="text-sm font-semibold text-blue-500">Net Payable Amount</p>
            @endif
        </div>
        <div class="text-right">
            <p class="text-xs">{{ number_format($vatable_sales, 2) }}</p>
            <p class="text-xs">{{ number_format($vat, 2) }}</p>
            <p class="text-xs font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p>
            @if ($discount_amount > 0)
                <p class="py-1 text-xs"></p>

                @foreach ($selected_discounts as $discount)
                    @if (empty($discount->amount))
                        <p class="text-xs">{{ number_format(($discount->percentage / 100) * $net_total, 2) }}</p>
                    @else
                        <p class="text-xs">{{ number_format($discount->amount, 2) }}</p>
                    @endif
                @endforeach

                <p class="text-sm font-semibold text-blue-500">{{ number_format($net_total - $discount_amount, 2) }}</p>
            @endif
        </div>
    </div>

    {{-- Payment methods --}}
    <div class="grid grid-cols-2 border-t border-dotted">
        <div class="pt-5 space-y-3 border-r border-dotted">
            <h3 class="text-sm font-semibold">Payment Methods</h3>
            <div class="flex gap-5">
                <div>
                    <p class="text-xs font-semibold">GCash</p>
                    <p class="text-xs">+63 917 139 9334</p>
                    <p class="text-xs">Fabio Basbaño</p>
                </div>
                <div>
                    <p class="text-xs font-semibold">Philippine National Bank</p>
                    <p class="text-xs">0000-0000-0000</p>
                    <p class="text-xs">Amazing View Mountain Resort</p>
                </div>
            </div>
        </div>

        {{-- Contact Details --}}
        <div class="pt-5 pl-5 space-y-3">
            <h3 class="text-sm font-semibold">Contact us</h3>
            <div class="flex gap-5">
                <div>
                    <p class="text-xs font-semibold">Phone number</p>
                    <p class="text-xs">+63 917 139 9334</p>
                    <p class="text-xs">Fabio Basbaño</p>
                </div>
                <div>
                    <p class="text-xs font-semibold">Email</p>
                    <p class="text-xs">reservation@amazingviewresort.com</p>
                    <p class="text-xs">Amazing View Mountain Resort</p>
                </div>
            </div>
        </div>
    </div>
</div>