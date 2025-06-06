<div x-data="{
    {{-- Reservation Details --}}
    min: new Date(),
    date_in: $persist($wire.entangle('date_in')).using(sessionStorage),
    date_out: $persist($wire.entangle('date_out')).using(sessionStorage),
    max_senior_count: $persist($wire.entangle('max_senior_count')).using(sessionStorage),
    senior_count: $persist($wire.entangle('senior_count')).using(sessionStorage),
    pwd_count: $persist($wire.entangle('pwd_count')).using(sessionStorage),
    adult_count: $persist($wire.entangle('adult_count')).using(sessionStorage),
    children_count: $persist($wire.entangle('children_count')).using(sessionStorage),
    capacity: $wire.entangle('capacity'),

    {{-- Guest Details --}}
    first_name: $persist($wire.entangle('first_name')).using(sessionStorage),
    last_name: $persist($wire.entangle('last_name')).using(sessionStorage),
    email: $persist($wire.entangle('email')).using(sessionStorage),
    phone: $persist($wire.entangle('phone')).using(sessionStorage),
    region: $wire.entangle('region'),
    province: $wire.entangle('province'),
    city: $wire.entangle('city'),
    district: $wire.entangle('district'),
    baranggay: $wire.entangle('baranggay'),
    street: $persist($wire.entangle('street')).using(sessionStorage),
    
    {{-- Payment --}}
    downpayment: $persist($wire.entangle('downpayment')).using(sessionStorage),

    {{-- Operations --}}
    is_map_view: $wire.entangle('is_map_view'),
    can_select_room: $wire.entangle('can_select_room'),
}" x-ref="form">
@csrf

<div class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center justify-between p-5 bg-white border rounded-lg border-slate-200">
        <div class="flex items-center gap-5">
            <x-tooltip text="Back" dir="bottom">
                <a x-ref="content" href="{{ route('app.reservations.index') }}" wire:navigate>
                    <x-icon-button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </a>
            </x-tooltip>
        
            <div>
                <h2 class="text-lg font-semibold">Create Reservation</h2>
                <p class="max-w-sm text-xs">Create reservation for guests here.</p>
            </div>
        </div>
        
        <x-actions>
            <div class="space-y-1">
                <button type="button" x-on:click="$dispatch('open-modal', 'search-guest-modal'); dropdown = false" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-search"><circle cx="10" cy="8" r="5"/><path d="M2 21a8 8 0 0 1 10.434-7.62"/><circle cx="18" cy="18" r="3"/><path d="m22 22-1.9-1.9"/></svg>
                    <p>Search Guest</p>
                </button>
                <button type="button" x-on:click="$dispatch('open-modal', 'reset-reservation-modal'); dropdown = false" class="flex items-center w-full gap-5 px-3 py-2 text-xs font-semibold rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    <p>Reset</p>
                </button>
            </div>
        </x-actions>
    </div>
    
    <section class="w-full space-y-5">
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <div class="flex items-start justify-between gap-5">
                <hgroup>
                    <h2 class="font-semibold">Reservation Details</h2>
                    <p class="max-w-sm text-xs">Enter reservation date, number of guests, and then select a room you want to reserve.</p>
                </hgroup>

                <button x-on:click="$wire.set('can_select_room', false)" type="button" @class(['text-xs font-semibold text-blue-500 transition-all duration-200 ease-in-out', 'scale-0' => !$can_select_room, 'scale-100' => $can_select_room])>
                    Edit
                </button>
            </div>

            {{-- Step 1: Reservation Details --}}
            @include('components.app.reservation.reservation_details')

            {{-- Step 2: Room & Additional Details --}}
            @include('components.app.reservation.room_add_details', [
                'available_rooms' => $available_rooms,
                'available_room_types' => $available_room_types,
                'buildings' => $buildings,
                'column_count' => $column_count,
                'selected_building' => $selected_building,
                'selected_rooms' => $selected_rooms,
                'reserved_rooms' => $reserved_rooms,
                'rooms' => $rooms,
            ])
        </div>
        {{-- Step 3: Guest Details --}}
        @include('components.app.reservation.guest_details')

        {{-- Step 4: Additional Details (Optional) --}}
        @include('components.app.reservation.add_details', [
            'services' => $services,
            'quantity' => $quantity,
        ])

        {{-- Step 5: Payment --}}
        @include('components.app.reservation.payment')

        {{-- Reservation Breakdown --}}
        <div class="p-5 space-y-5 bg-white border rounded-md border-slate-200">
            <hgroup>
                <h2 class='font-semibold'>Reservation Breakdown</h2>
                <p class='text-xs'>Check the summary of your reservation here</p>
            </hgroup>
            
            @if ($selected_rooms->count() > 0 || $selected_services->count() > 0)
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
            @endif
        </div>

        <div class="flex justify-end">
            <x-primary-button type="button" wire:click='submit'>Create Reservation</x-primary-button>
        </div>
    </section>
</div>

{{-- Modal for confirming reservation --}}
<x-modal.full name="show-reservation-confirmation" maxWidth="sm">
    <form wire:submit='store' x-data="{ checked: false }" x-on:reservation-created.window="show = false">
        <section class="p-5 space-y-5">
            <hgroup>
                <h2 class="text-lg font-semibold">Reservation Confirmation</h2>
                <p class="text-xs">Confirm that the reservation details entered are correct</p>
            </hgroup>

            <x-form.input-error field="selected_rooms" />

            <div class="px-3 py-2 bg-white border rounded-md border-slate-200">
                <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
            </div>
            
            <x-form.input-group>
                <x-form.input-label id="note">Add a note &lpar;optional&rpar;</x-form.input-label>
                <x-form.textarea id="note" wire:model.live="note" maxlength="255" class="w-full" rows="3" />
                <x-form.input-error field="note" />
            </x-form.input-group>

            <x-loading wire:loading wire:target='store'>Processing reservation, please wait...</x-loading>
            
            <div class="flex items-center justify-end gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="submit" x-bind:disabled="!checked">Submit Reservation</x-primary-button>
            </div>
        </section>
    </form>
</x-modal.full> 

<x-modal.full name='search-guest-modal' maxWidth='sm'>
    <livewire:app.guest.search-guest />
</x-modal.full>

<x-modal.full name='reset-reservation-modal' maxWidth='sm'>
    <div class="p-5 space-y-5">
        <hgroup>
            <h2 class="text-lg font-semibold">Reset Reservation</h2>
            <p class="text-sm">Are you sure you want to reset your reservation?</p>
        </hgroup>

        <div class="flex justify-end gap-1 mt-5">
            <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
            <x-danger-button type="button" x-on:click="show = false; $wire.resetReservation()">Reset</x-danger-button>
        </div>
    </div>
</x-modal.full>

</div>

