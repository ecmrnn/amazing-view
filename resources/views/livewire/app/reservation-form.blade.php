<form x-data="{
        {{-- Reservation Details --}}
        min: new Date(),
        date_in: $persist($wire.entangle('date_in')).using(sessionStorage),
        date_out: $persist($wire.entangle('date_out')).using(sessionStorage),
        adult_count: $persist($wire.entangle('adult_count')).using(sessionStorage),
        children_count: $persist($wire.entangle('children_count')).using(sessionStorage),
        capacity: $persist($wire.entangle('capacity')).using(sessionStorage),

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

        {{-- Operations --}}
        is_map_view: $wire.entangle('is_map_view'),
        can_select_room: $wire.entangle('can_select_room'),
        can_submit_payment: $wire.entangle('can_submit_payment'),

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },
    }" wire:submit="submit()">
    @csrf

    <div class="relative flex flex-col gap-5 lg:flex-row">
        <section class="w-full">
            {{-- Step 1: Reservation Details --}}
            @include('components.app.reservation.reservation_details')
            <x-line-vertical />

            {{-- Step 2: Guest Details --}}
            @include('components.app.reservation.guest_details')

            <x-line-vertical />
            <x-primary-button type="button" x-show="!can_select_room" x-on:click="$wire.selectRoom()">Select a Room</x-primary-button>
            <x-secondary-button type="button" x-show="can_select_room" x-on:click="can_select_room = !can_select_room">Edit Reservation &amp; Guest Details</x-secondary-button>
            <x-line-vertical />

            {{-- Step 3: Room & Additional Details --}}
            @include('components.app.reservation.room_add_details', [
                'addons' => $addons,
                'available_rooms' => $available_rooms,
                'available_room_types' => $available_room_types,
                'buildings' => $buildings,
                'column_count' => $column_count,
                'selected_building' => $selected_building,
                'selected_rooms' => $selected_rooms,
                'reserved_rooms' => $reserved_rooms,
                'rooms' => $rooms,
            ])
            <x-line-vertical />

            {{-- Toggle payment section --}}
            <x-primary-button x-bind:disabled="!can_select_room" x-show="!can_submit_payment" type="button" x-on:click="$wire.sendPayment()">Send Payment</x-primary-button>
            <x-secondary-button x-show="can_submit_payment" x-on:click="can_submit_payment = false">Edit Rooms</x-secondary-button>

            {{-- Step 4: Payment --}}
            <x-line-vertical />
            @include('components.app.reservation.payment')
        </section>

        <section class="sticky self-start w-full p-5 overflow-auto rounded-lg top-5 bg-slate-50/50">
            @include('components.app.reservation.summary', [
                'selected_amenities' => $selected_amenities,
                'selected_rooms' => $selected_rooms,
            ])
        </section>
    </div>
</form>
