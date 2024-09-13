<div x-data="{
        min: new Date(),
        date_in: $wire.entangle('date_in'),
        date_out: $wire.entangle('date_out'),
        adult_count: $wire.entangle('adult_count'),
        children_count: $wire.entangle('children_count'),
        capacity: $wire.entangle('capacity'),
        can_select_a_room: $wire.entangle('can_select_a_room'),
        available_rooms: [],

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
        <form wire:submit="submit" class="md:col-span-2">
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
        </form>
    
        {{-- Summary --}}
        <x-web.reservation.summary 
            :selectedRooms="$selected_rooms"
            :selectedAmenities="$selected_amenities"
        />
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