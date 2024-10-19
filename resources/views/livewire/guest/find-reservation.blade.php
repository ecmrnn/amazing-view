<div>
    <form wire:submit="submit" class="relative flex gap-1 mb-5">
        <div><x-form.input-search wire:model="reservation_id" label="Reservation ID" id="reservation_id" /></div>
        <x-primary-button type="submit">Find my Reservation</x-primary-button>
        <div class="absolute -bottom-5">
            <x-form.input-error field="reservation_id" />
        </div>
    </form>

    {{-- Reservation Details --}}
    @if (!empty($reservation_id))
        @if (!empty($reservation))
            <div class="space-y-5">
                <p class="text-xs">Reservation found!</p>
                
                <div class="p-5 space-y-3 border rounded-lg shadow-lg">
                    <hgroup class="flex justify-between">
                        <h3 class="text-xl font-semibold text-blue-500">{{ $reservation->rid }}</h3>
        
                        <div class="space-x-3">
                            <strong class="text-xs">Status: </strong>
                            <x-status type="reservation" :status="$reservation->status" />
                        </div>
                    </hgroup>
        
                    {{-- Summary --}}
                    <section class="grid border rounded-lg md:grid-cols-2">
                        {{-- Guest Details --}}
                        <div class="px-3 py-2 space-y-2 border-b border-dashed md:border-b-0 md:border-r">
                            <h4 class="font-semibold">Guest Details</h4>
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3 capitalize">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smile"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/></svg>
                                    <span>{{ $reservation->first_name . " " . $reservation->last_name }}</span></p>
                                <p class="flex items-center gap-3 capitalize">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                                    <span>{{ $reservation->address }}</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 7.94"/><path d="M14.05 6A5 5 0 0 1 18 10"/></svg>
                                    <span>{{ $reservation->phone }}</span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    <span>{{ $reservation->email }}</span></p>
                            </div>
                        </div>
    
                        {{-- Reservation Details --}}
                        <div class="px-3 py-2 space-y-2">
                            <h4 class="font-semibold">Reservation Details</h4>
                            
                            <div class="space-y-1 text-sm">
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-single"><path d="M3 20v-8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8"/><path d="M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4"/><path d="M3 18h18"/></svg>
                                    <span>
                                        @if ($reservation->date_in == $reservation->date_out)
                                            {{ __('Day Tour') }}    
                                        @else
                                            {{ __('Overnight') }}    
                                        @endif
                                    </span></p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>2:00 PM - 12:00 PM</span></p>
                                <p class="flex items-center gap-3"
                                        x-data="{ 
                                            adult_count: @js($reservation->adult_count), 
                                            children_count: @js($reservation->children_count), 
                                        }"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                                    <span>
                                        <span x-text="adult_count"></span> <span>Adult<span x-show="adult_count > 1">s</span></span>
                                        <span x-show="children_count > 0">&amp; <span x-text="children_count"></span> <span>Child<span x-show="children_count > 1">ren</span></span></span>
                                    </span>
                                </p>
                                <p class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                                    <span class="space-x-1">
                                        @foreach ($selected_rooms as $room)
                                            <span key="{{ $room->id }}" class="inline-block px-2 py-1 font-semibold capitalize rounded-md bg-slate-200">
                                                {{ $room->building->prefix . " " . $room->room_number }}
                                            </span>
                                        @endforeach
                                    </span>
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Reservation Breakdown --}}
                    <div class="gap-5 text-sm md:flex">
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                            <span><strong>Check in: </strong>{{ date_format(date_create($reservation->date_in),"F j, Y") }}</span></p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                            <span><strong>Check out: </strong>{{ date_format(date_create($reservation->date_out),"F j, Y") }}</span></p>
                    </div>

                    {{-- Breakdown --}}
                    <section class="p-3 space-y-3 border rounded-lg sm:p-5">
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
                            @forelse ($reservation->rooms as $room)
                                <div class="grid grid-cols-2 text-sm">
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
                            @foreach ($reservation->amenities as $amenity)
                            @php
                                $quantity = $amenity->pivot->quantity;

                                // If quantity is 0, change it to 1
                                $quantity != 0 ?: $quantity = 1;
                            @endphp

                            <div class="grid grid-cols-2">
                                <p class="uppercase">{{ $amenity->name }}</p>
                                
                                <div class="grid grid-cols-3 place-items-end">
                                        <p>{{ $quantity }}</p>
                                        <p>{{ number_format($amenity->price, 2) }}</p>
                                        <p>{{ number_format($amenity->price * $quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Bills --}}
                    <div class="flex justify-end gap-5 px-5">
                        <div class="text-right">
                            <p class="text-sm">Vatable Sales</p>
                            <p class="text-sm">VAT Amount</p>
                            <p class="text-sm font-semibold text-blue-500">Total Amount Due</p>
                            @if (!empty($reservation->invoice))
                                <p class="py-1 text-sm"></p>
                                
                                @foreach ($reservation->invoice->discounts as $index => $discount)
                                    <p class="text-sm">
                                        @if ($index == 0)
                                            {{ __('Less') }} 
                                        @endif
                                        
                                        {{ ucwords(strtolower($discount->name)) }}</p>
                                @endforeach
                
                                <p class="text-sm font-semibold text-blue-500">Net Payable Amount</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm">{{ number_format($vatable_sales, 2) }}</p>
                            <p class="text-sm">{{ number_format($vat, 2) }}</p>
                            <p class="text-sm font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p>
                            @if (!empty($reservation->invoice))
                                <p class="py-1 text-sm"></p>
                
                                @foreach ($reservation->invoice->discounts as $discount)
                                    @if (!empty($reservation->invoice))
                                        <p class="text-sm">{{ number_format(($discount->percentage / 100) * $net_total, 2) }}</p>
                                    @else
                                        <p class="text-sm">{{ number_format($discount->amount, 2) }}</p>
                                    @endif
                                @endforeach
                
                                <p class="text-sm font-semibold text-blue-500">{{ number_format($net_total - $discount_amount, 2) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-5">
                <p class="text-2xl font-semibold text-red-500">Oh no!</p>
                <p class="">There are no reservation with the ID <strong class="font-semibold text-blue-500">&quot;{{ $reservation_id }}&quot;</strong></p>
            </div>
        @endif
    @endif
</div>