<div
    x-data="{
        {{-- Reservation Details --}}
        min: new Date(),
        date_in: $persist($wire.entangle('date_in')).using(sessionStorage),
        date_out: $persist($wire.entangle('date_out')).using(sessionStorage),
        senior_count: $persist($wire.entangle('senior_count')).using(sessionStorage),
        max_senior_count: $persist($wire.entangle('max_senior_count')).using(sessionStorage),
        pwd_count: $persist($wire.entangle('pwd_count')).using(sessionStorage),
        adult_count: $persist($wire.entangle('adult_count')).using(sessionStorage),
        children_count: $persist($wire.entangle('children_count')).using(sessionStorage),
        capacity: $wire.entangle('capacity'),
        address: $persist($wire.entangle('address')).using(sessionStorage),

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

        {{-- Operations --}}
        can_select_a_room: $wire.entangle('can_select_a_room'),
        can_submit_payment: $wire.entangle('can_submit_payment'),
        can_select_address: $wire.entangle('can_select_address'),

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },

        setMaxSeniorCount() {
            if (this.pwd_count > 0) {
                this.max_senior_count = this.adult_count;

                if (this.pwd_count + this.senior_count > this.adult_count + this.children_count) {
                    this.pwd_count--;
                }

            } else {
                this.max_senior_count = this.adult_count - this.pwd_count;
            }
        },

        resetProperties() {
            localStorage.removeItem('_x_date_in');
            this.date_in = null;
            localStorage.removeItem('_x_date_out');
            this.date_out = null;
            localStorage.setItem('_x_senior_count', 0);
            this.senior_count = 0;
            localStorage.setItem('_x_pwd_count', 0);
            this.pwd_count = 0;
            localStorage.setItem('_x_adult_count', 1);
            this.adult_count = 1;
            localStorage.setItem('_x_children_count', 0);
            this.children_count = 0;
            localStorage.setItem('_x_capacity', 0);
            this.capacity = 0;
            localStorage.removeItem('_x_first_name');
            this.first_name = null;
            localStorage.removeItem('_x_last_name');
            this.last_name = null;
            localStorage.removeItem('_x_email');
            this.email = null;
            localStorage.removeItem('_x_phone');
            this.phone = null;
            localStorage.removeItem('_x_street');
            this.street = null;
        }
    }"
    x-init="setMaxSeniorCount()"
    x-on:reservation-created.window="resetProperties()"
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
                        'vatable_sales' => $vatable_sales,
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

    {{-- Modal for confirming reservation --}}
    <x-modal.full name="show-reservation-confirmation" maxWidth="lg">
        <div x-data="{ toc: false }">
            <header class="flex items-center gap-3 p-5 border-b">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>
                <hgroup>
                    <h2 class="text-sm font-semibold capitalize">Reservation Confirmation</h2>
                    <p class="max-w-sm text-xs">Confirm that the reservation details entered are correct.</p>
                </hgroup>
            </header>
            
            <section class="p-5 space-y-3 bg-slate-100/50">
                <x-form.input-checkbox x-model="toc" id="toc" label="I, {{ ucwords(strtolower($first_name)) . ' ' . ucwords(strtolower($last_name)) }}, ensure that the information I provided is true and correct. I also give consent to Amazing View Mountain Resort to collect and manage my data." />
            </section>

            <footer x-show="toc" class="p-5 bg-white border-t">
                <x-primary-button x-on:click="$wire.store(); show = false;">
                    Submit Reservation
                </x-primary-button>
            </footer>
        </div>
    </x-modal.full> 
</div>