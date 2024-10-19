<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reservations') }}
                </h1>
                <p class="text-xs">Manage your reservations here</p>
            </hgroup>

            @can('create room')
                <x-primary-button class="text-xs">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span>Add Room</span>
                    </div>
                </x-primary-button>
            @endcan
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div class="flex items-center justify-between gap-3 sm:items-start">
            <div class="flex items-center gap-3 sm:gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.reservations.index')}}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
            
                <div>
                    <h2 class="text-lg font-semibold">{{ $reservation->rid }}</h2>
                    <p class="max-w-sm text-xs">Confirm and view reservation.</p>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex items-start gap-1">
                <x-secondary-button class="hidden text-xs md:block" x-on:click="alert('Downloading PDF... soon...')">Download PDF</x-secondary-button>
                <x-icon-button class="md:hidden" x-on:click="alert('Downloading PDF... soon...')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                </x-icon-button>

                @if (empty($reservation->invoice))
                    <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                        <x-primary-button class="hidden text-xs md:block">Create Invoice</x-primary-button>
                    </a>
                @else
                    <a href="{{ route('app.billings.index', ['rid' => $reservation->rid]) }}" wire:navigate>
                        <x-secondary-button class="hidden text-xs md:block">View Invoice</x-secondary-button>
                    </a>
                @endif
                <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                    <x-icon-button class="md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    </x-icon-button>
                </a>
            </div>
        </div>

        <section class="grid grid-cols-1 gap-3 lg:grid-cols-3 sm:gap-5">
            <article class="self-start space-y-3 lg:col-span-2 sm:space-y-5">
                {{-- Guest and Reservation Details --}}
                <section class="grid border rounded-lg md:grid-cols-2 ">
                    {{-- Guest Details --}}
                    <div class="p-3 space-y-2 border-b border-dashed sm:p-5 md:border-b-0 md:border-r">
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
                    <div class="p-3 space-y-2 sm:p-5">
                        <div class="flex justify-between">
                            <h4 class="font-semibold">Reservation Details</h4>
                            <x-status type="reservation" :status="$reservation->status" />
                        </div>
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
                                    @foreach ($reservation->rooms as $room)
                                        <span key="{{ $room->id }}" class="inline-block px-2 py-1 font-semibold capitalize rounded-md bg-slate-100">
                                            {{ $room->building->prefix . " " . $room->room_number }}
                                        </span>
                                    @endforeach
                                </span>
                            </p>
                        </div>
                    </div>
                </section>

                {{-- Check-in and Check-out date --}}
                <div class="flex flex-col gap-3 text-sm md:gap-5 md:flex-row">
                    <p class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-up"><path d="m14 18 4-4 4 4"/><path d="M16 2v4"/><path d="M18 22v-8"/><path d="M21 11.343V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h9"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                        <span><strong>Check in: </strong>{{ $reservation->date_in }}</span></p>
                    <p class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                        <span><strong>Check out: </strong>{{ $reservation->date_out }}</span></p>
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
                <div class="flex items-start justify-between gap-5 pr-5">
                    {{-- Payment Receipt --}}
                    <x-secondary-button class="text-xs justify-self-start" x-on:click="$dispatch('open-modal', 'show-downpayment-modal')">
                        View Sent Receipt
                    </x-secondary-button>

                    <div class="flex gap-5">
                        <div class="text-right">
                            <p class="text-sm">Vatable Sales</p>
                            <p class="text-sm">VAT Amount</p>
                            <p class="text-sm font-semibold text-blue-500">Total Amount Due</p>
                            {{-- @if (!empty($reservation->invoice))
                                <p class="py-1 text-sm"></p>
                                
                                @if (!empty($reservation->invoice->discounts))
                                    @foreach ($reservation->invoice->discounts as $index => $discount)
                                        <p class="text-sm">
                                            @if ($index == 0)
                                                {{ __('Less') }} 
                                            @endif
                                            
                                            {{ ucwords(strtolower($discount->name)) }}</p>
                                    @endforeach
                                @endif
                
                                <p class="text-sm font-semibold text-blue-500">Net Payable Amount</p>
                            @endif --}}
                        </div>
                        <div class="text-right">
                            <p class="text-sm">{{ number_format($vatable_sales, 2) }}</p>
                            <p class="text-sm">{{ number_format($vat, 2) }}</p>
                            <p class="text-sm font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p>
                            {{-- @if (!empty($reservation->invoice))
                                <p class="py-1 text-sm"></p>
                
                                @if (!empty($reservation->invoice->discounts))
                                    @foreach ($reservation->invoice->discounts as $discount)
                                        @if (!empty($reservation->invoice))
                                            <p class="text-sm">{{ number_format(($discount->percentage / 100) * $net_total, 2) }}</p>
                                        @else
                                            <p class="text-sm">{{ number_format($discount->amount, 2) }}</p>
                                        @endif
                                    @endforeach
                                @endif
                
                                <p class="text-sm font-semibold text-blue-500">{{ number_format($net_total - $discount_amount, 2) }}</p>
                            @endif --}}
                        </div>
                    </div>
                    
                </div>
                
                {{-- Note --}}
                {{-- <form action="{{ route('app.reservation.update-note', $reservation) }}" class="space-y-1" method="POST">
                    @csrf
                    @method('PATCH')

                    <x-form.input-label for="note">Reservation Note</x-form.input-label>
                    <x-form.textarea name="note" rows="3" class="w-full" id="note">
                        @if ($reservation->note)
                            {{ $reservation->note }}
                        @endif
                    </x-form.textarea>
                    <x-primary-button>Save Note</x-primary-button>
                </form> --}}
                <livewire:app.reservation.update-note :reservation="$reservation" />
            </article>

            {{-- Activities --}}
            <aside class="p-3 space-y-3 rounded-lg sm:p-5 bg-slate-50/50 sm:space-y-5">
                <hgroup>
                    <h3 class="font-semibold">Activity of this reservation</h3>
                    <p class="max-w-sm text-xs">Track the activities made on this reservation here</p>
                </hgroup>

                <div class="space-y-1">
                    <div class="flex items-center gap-5 py-3 pl-5 bg-white rounded-md shadow-sm sm:gap-5">
                        <time datetime="{{ $created_at_time }}" class="text-xs">{{ $created_at_time_formatted }}</time>

                        <x-line class="hidden bg-zinc-800/50 sm:block" />

                        <hgroup>
                            <h4 class="text-sm font-semibold line-clamp-1">Reservation Created</h4>
                            <p class="text-xs capitalize">{{ $reservation->first_name . " " . $reservation->last_name }}</p>
                        </hgroup>
                    </div>
                </div>
            </aside>
        </section>
    </div>

    {{-- Proof of image modal --}}
    <x-modal.full name="show-downpayment-modal" maxWidth="lg">
        <div x-data="{ checked: false }">
            <section class="p-5 space-y-5 bg-white">
                <hgroup>
                    <h2 class="font-semibold text-center capitalize text">Payment upon Reservation</h2>
                    <p class="max-w-sm mx-auto text-sm text-center 11">Confirm that the payment made below are successful before approving the reservation.</p>
                </hgroup>

                <div class="relative">
                    <x-img-lg class="w-full" />

                    <div class="absolute flex gap-1 top-3 right-3">
                        <x-tooltip text="Download" dir="top">
                            <a x-ref="content" x-on:click="alert('Downloading image... soon')">
                                <x-icon-button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-down"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/><path d="m14 19 3 3v-5.5"/><path d="m17 22 3-3"/><circle cx="9" cy="9" r="2"/></svg>
                                </x-icon-button>
                            </a>
                        </x-tooltip>

                        <x-tooltip text="View" dir="top">
                            <a href="https://placehold.co/400x400" target="_blank" x-ref="content">
                                <x-icon-button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                </x-icon-button>
                            </a>
                        </x-tooltip>
                    </div>
                </div>
            </section>
        </div>
    </x-modal.full> 
</x-app-layout>  