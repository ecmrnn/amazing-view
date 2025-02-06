<form x-data="{
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
    address: $persist($wire.entangle('address')).using(sessionStorage),
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
    can_enter_guest_details: $wire.entangle('can_enter_guest_details'),
    can_add_amenity: $wire.entangle('can_add_amenity'),
    can_select_room: $wire.entangle('can_select_room'),
    can_submit_payment: $wire.entangle('can_submit_payment'),
}">
@csrf

<div class="relative w-full max-w-screen-lg mx-auto space-y-5 rounded-lg">
    <div class="flex items-center gap-5 p-5 bg-white border rounded-lg border-slate-200">
        <x-tooltip text="Back" dir="bottom">
            <a x-ref="content" href="{{ route('app.reservations.index')}}" wire:navigate>
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
        ])

        {{-- Step 5: Payment --}}
        @include('components.app.reservation.payment')

        <x-primary-button wire:click='submit'>Create Reservation</x-primary-button>
    </section>
</div>

{{-- Modal for confirming reservation --}}
<x-modal.full name="show-reservation-confirmation" maxWidth="sm">
    <div x-data="{ checked: false }">
        <section class="p-5 space-y-5 bg-white">
            <hgroup>
                <h2 class="font-semibold text-center capitalize">Reservation Confirmation</h2>
                <p class="max-w-sm text-sm text-center">Confirm that the reservation details entered are correct.</p>
            </hgroup>

            <div class="px-3 py-2 border border-gray-300 rounded-md">
                <x-form.input-checkbox x-model="checked" id="checked" label="The information I have provided is true and correct." />
            </div>

            <div class="grid gap-2 px-3 py-2 border border-gray-300 rounded-md">
                <x-form.input-radio value="walk-in-reservation" id="walk-in-reservation" name="reservation_type" label="Walk-in Reservation" wire:model.live='reservation_type' />
                <x-form.input-radio value="online-reservation" id="online-reservation" name="reservation_type" label="Online Reservation" wire:model.live='reservation_type' />
            </div>

            <div class="space-y-1">
                <x-form.input-label id="note">Add a note &lpar;optional&rpar;</x-form.input-label>
                <x-form.textarea id="note" wire:model.live="note" maxlength="255" class="w-full" rows="3" />
                <x-form.input-error field="note" />
            </div>
            
            <div class="flex items-center justify-center gap-1">
                <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                <x-primary-button type="button" x-bind:disabled="!checked" x-on:click="$wire.store(); show = false; toast('Success', { type: 'success', description: 'Yay, Reservation Created!' })">Submit Reservation</x-primary-button>
            </div>
        </section>
    </div>
</x-modal.full> 
</form>

