@props([
    'reservation' => [],
    'selected_amenities' => [],
    'selected_rooms' => []
])

<div class="p-10 space-y-5 bg-white min-w-[800px]">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-5">
            <x-application-logo class="rounded-full" />

            <hgroup>
                <h2 class="text-xl font-semibold">Amazing View Mountain Resort</h2>
                <address class="text-xs not-italic">Little Baguio, Paagahan Mabitac, Laguna, Philippines
                </address>
            </hgroup>
        </div>

        <hgroup class="text-right">
            @if (!empty($reservation))
                <h2 class="text-lg font-semibold">{{ $reservation->rid }}</h2>
            @else
                <h2 class="text-lg font-semibold">RYYYYMMDD0000X</h2>
            @endif
            <p class="text-xs">Reservation ID</p>
        </hgroup>
    </header>

    <div class="h-[1px] w-full border-b border-dotted"></div>

    <h3 class="text-sm font-semibold">Reservation Details</h3>

    <div class="p-5 space-y-3 rounded-lg bg-slate-100">
        <div class="grid grid-cols-2 gap-3">
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
    </div>

    <h3 class="text-sm font-semibold">Guest Details</h3>

    <div class="grid grid-cols-2 gap-3 p-5 rounded-lg bg-slate-100">
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

    <h3 class="text-sm font-semibold">Reservation Breakdown</h3>

    <div class="p-3 px-4 space-y-3 border rounded-lg">
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
                @foreach ($selected_amenities as $amenity)
                    <div class="grid grid-cols-2 text-xs">
                        <p class="uppercase">{{ $amenity->name }}</p>

                        <div class="grid grid-cols-3 place-items-end">
                            <p>
                                @php    
                                    $quantity = 1;
                                    $quantity != 0 ?: $quantity = 1;
                                    
                                    if ($additional_amenity_quantities->count() > 0) {
                                        foreach ($additional_amenity_quantities as $selected_amenity) {
                                            if ($selected_amenity['amenity_id'] == $amenity->id) {
                                                $quantity = $selected_amenity['quantity'];
                                                break;
                                            }
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
    <div class="flex justify-end gap-5 px-4">
        <div class="text-sm font-semibold text-right">
            <p>Vatable Sales</p>
            <p>VAT Amount</p>
            <p class="text-blue-500">Total Amount Due</p>
        </div>
        <div class="text-sm font-semibold text-right">
            <p>{{ number_format($vatable_sales, 2) }}</p>
            <p>{{ number_format($vat, 2) }}</p>
            <p class="text-blue-500">{{ number_format($net_total, 2) }}</p>
        </div>
    </div>
</div>