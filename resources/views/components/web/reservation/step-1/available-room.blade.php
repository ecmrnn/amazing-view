<div class="grid items-start gap-3 md:grid-cols-4">
    <div class="w-full md:max-w-[250px]">
        <x-img-gallery />
    </div>

    <div class="flex-shrink-0 space-y-2">
        <h3 class="font-semibold">LT 101</h3>
        <p class="text-xs text-zinc-800/50">Here is everything you need to know about this room.</p>

        <div>
            <p class="text-xs"><strong>Capacity</strong>: 2 to 4 person</p>
            <p class="text-xs"><strong>Rate</strong>: Php2,500.00 / night</p>
        </div>
    </div>

    <div class="w-full space-y-2">
        <h3 class="font-semibold">Amenities</h3>
        <p class="text-xs text-zinc-800/50">This room includes the following amenities.</p>
        <ul class="grid grid-cols-2 text-xs">
            <li class="flex items-center gap-2"><x-line class="bg-zinc-800/50" /> Lorem, ipsum.</li>
            <li class="flex items-center gap-2"><x-line class="bg-zinc-800/50" /> Lorem, ipsum.</li>
            <li class="flex items-center gap-2"><x-line class="bg-zinc-800/50" /> Lorem, ipsum.</li>
        </ul>
    </div>

    <div class="flex md:justify-end">
        <x-secondary-button type="button" x-on:click="$wire.addRoom(1);console.log(selected_rooms)" class="flex-shrink-0">Book this Room</x-secondary-button>
    </div>
</div>
