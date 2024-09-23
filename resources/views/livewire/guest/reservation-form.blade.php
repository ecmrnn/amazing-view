<div x-data="{
        min: new Date(),
        date_in: $wire.entangle('date_in'),
        date_out: $wire.entangle('date_out'),
        adult_count: $wire.entangle('adult_count'),
        children_count: $wire.entangle('children_count'),
        capacity: $wire.entangle('capacity'),
        can_select_a_room: $wire.entangle('can_select_a_room'),

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

        formatDate(date) {
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('en-US', options)
        },
    }"
    class="max-w-screen-xl py-5 mx-auto space-y-5">

    {{-- Reservation steps --}}
    <div x-ref="form" class="flex flex-col justify-between gap-2 md:flex-row md:items-center md:gap-5 ">
        <x-web.reservation.steps :step="1" icon="bed" name="Reservation Details" />
        <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
        <x-web.reservation.steps :step="2" icon="face" name="Guest Details" />
        <div class="h-[1px] hidden md:block border-b border-dashed w-full"></div>
        <x-web.reservation.steps :step="3" icon="receipt" name="Payment" />
    </div>
    
    {{-- Reservation form --}}
    <article class="relative grid gap-5 md:grid-cols-3">
        {{-- Form --}}
        <form wire:submit="submit" x-bind:class="$store.step.count == 3 ? 'md:col-span-3' : 'md:col-span-2'" class="">
            {{-- Step 1: Reservation Details --}}
            <template x-if="$store.step.count == 1">
                <div>
                    <x-web.reservation.steps.reservation-details
                        :roomTypes="$room_types"
                        :suggestedRooms="$suggested_rooms"
                        :selectedRooms="$selected_rooms"
                        :availableRooms="$available_rooms"
                        :reservableAmenities="$reservable_amenities"
                        :roomTypeName="$room_type_name"
                    />
                </div>
            </template>

            {{-- Step 2: Guest Details --}}
            <template x-if="$store.step.count == 2">
                <div>
                    <x-web.reservation.steps.guest-details
                        :region="$region"
                        :regions="$regions"
                        :province="$province"
                        :provinces="$provinces"
                        :city="$city"
                        :cities="$cities"
                        :district="$district"
                        :districts="$districts"
                        :baranggay="$baranggay"
                        :baranggays="$baranggays"
                        :address="$address"
                    />
                </div>
            </template>

            {{-- Step 3: Payment --}}
            <template x-if="$store.step.count == 3">
                <div>
                    <x-web.reservation.steps.payment
                    />
                </div>
            </template>
        </form>
    
        {{-- Summary --}}
        <div x-show="$store.step.count < 3">
            <x-web.reservation.summary 
                :selectedRooms="$selected_rooms"
                :selectedAmenities="$selected_amenities"
            />
        </div>
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