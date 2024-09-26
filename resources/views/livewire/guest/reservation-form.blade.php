<div x-data="{
        {{-- Reservation Details --}}
        min: new Date(),
        date_in: $wire.entangle('date_in'),
        date_out: $wire.entangle('date_out'),
        adult_count: $wire.entangle('adult_count'),
        children_count: $wire.entangle('children_count'),
        capacity: $wire.entangle('capacity'),

        {{-- Guest Details --}}
        first_name: $wire.entangle('first_name'),
        last_name: $wire.entangle('last_name'),
        email: $wire.entangle('email'),
        phone: $wire.entangle('phone'),
        region: $wire.entangle('region'),
        province: $wire.entangle('province'),
        city: $wire.entangle('city'),
        district: $wire.entangle('district'),
        baranggay: $wire.entangle('baranggay'),
        street: $wire.entangle('street'),

        {{-- Operations --}}
        can_select_a_room: $wire.entangle('can_select_a_room'),
        can_submit_payment: $wire.entangle('can_submit_payment'),
        can_select_address: $wire.entangle('can_select_address'),

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },
    }"
    class="max-w-screen-xl py-5 mx-auto space-y-5">

    {{-- Reservation steps --}}
    <div x-ref="form" class="flex flex-col justify-between gap-2 md:flex-row md:items-center md:gap-5 ">
        <x-web.reservation.steps step="1" currentStep="{{ $step }}" icon="bed" name="Reservation Details" />
        <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
        <x-web.reservation.steps step="2" currentStep="{{ $step }}" icon="face" name="Guest Details" />
        <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
        <x-web.reservation.steps step="3" currentStep="{{ $step }}" icon="receipt" name="Payment" />
    </div>
    
    {{-- Reservation form --}}
    <article class="relative grid gap-5 md:grid-cols-3">
        {{-- Form --}}
        <form
            wire:submit="submit"
            @if ($step >= 3)
                class="md:col-span-3"
            @else
                class="md:col-span-2"
            @endif>
            {{-- Step 1: Reservation Details --}}
            @if ($step == 1)
                <div>
                    @include('components.web.reservation.steps.reservation-details', [
                        'suggested_rooms' => $suggested_rooms,
                        'room_types' => $room_types,
                        'selected_rooms' => $selected_rooms,
                        'available_rooms' => $available_rooms,
                        'reservable_amenities' => $reservable_amenities,
                        'selected_amenities' => $selected_amenities,
                        'room_type_name' => $room_type_name,
                    ])
                </div>
            @endif

            {{-- Step 2: Guest Details --}}
            @if ($step == 2)
                <div>
                    @include('components.web.reservation.steps.guest-details', [
                        'region' => $region,
                        'regions' => $regions,
                        'province' => $province,
                        'provinces' => $provinces,
                        'city' => $city,
                        'cities' => $cities,
                        'district' => $district,
                        'districts' => $districts,
                        'baranggay' => $baranggay,
                        'baranggays' => $baranggays,
                        'address' => $address,
                    ])
                </div>
            @endif

            {{-- Step 3: Payment --}}
            @if ($step == 3)
                <div>
                    @include('components.web.reservation.steps.payment', [
                        'date_in' => $date_in,
                        'date_out' => $date_out,
                        'adult_count' => $adult_count,
                        'children_count' => $children_count,
                        'selected_rooms' => $selected_rooms,
                        'selected_amenities' => $selected_amenities,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'phone' => $phone,
                        'address' => $address,
                        'sub_total' => $sub_total,
                        'vat' => $vat,
                        'net_total' => $net_total,
                        'night_count' => $night_count,
                    ])
                </div>                
            @endif

            {{-- Success Message --}}
            @if ($step == 4)
                <div>
                    @include('components.web.reservation.steps.success', [
                        'reservation_id' => $reservation_rid
                    ]);
                </div>
            @endif
        </form>
    
        {{-- Summary --}}
        @if ($step < 3)
            <x-web.reservation.summary 
                :selectedRooms="$selected_rooms"
                :selectedAmenities="$selected_amenities"
            />
        @endif
    </article>

    {{-- Global step counter --}}
    @script
        <script>
            $wire.on('next-step', step => {
                Alpine.store('step').count = step[0];
            });
        </script>
    @endscript
</div>