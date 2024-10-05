<x-form.form-section>
    <x-form.form-header step="1" title="Reservation Summary" />

    <div x-show="!can_submit_payment" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <p class="text-sm">Kindly check if the information you provided is <strong class="text-blue-500">correct</strong>.</p>

                {{-- Summary --}}
                <div class="grid border rounded-lg md:grid-cols-2">
                    {{-- Guest Details --}}
                    <div class="px-3 py-2 space-y-2 border-b border-dashed md:border-b-0 md:border-r">
                        <h4 class="font-semibold">Guest Details</h4>
                        <div class="space-y-1 text-xs">
                            <p class="flex items-center gap-3 capitalize"><span class="material-symbols-outlined">ar_on_you</span><span>{{ $first_name . " " . $last_name }}</span></p>
                            <p class="flex items-center gap-3 capitalize"><span class="material-symbols-outlined">cottage</span><span>{{ trim(implode($address), ', ') }}</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">call</span><span>{{ $phone }}</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">mail</span><span>{{ $email }}</span></p>
                        </div>
                    </div>

                    {{-- Reservation Details --}}
                    <div class="px-3 py-2 space-y-2">
                        <h4 class="font-semibold">Reservation Details</h4>
                        <div class="space-y-1 text-xs">
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">airline_seat_flat</span>
                                @if ($date_in == $date_out)
                                    <span>Day Tour</span>
                                @else
                                    <span>Overnight</span>
                                @endif
                            </p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">acute</span><span>2:00 PM - 12:00 PM</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">face</span><span>{{ $adult_count }} Adults, {{ $children_count }} Children</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">door_front</span>
                                <span class="space-x-1">
                                    @foreach ($selected_rooms as $room)
                                        <span key="{{ $room->id }}" class="inline-block px-2 py-1 font-semibold capitalize rounded-md bg-slate-200">
                                            {{ $room->roomType->name . " " . $room->room_number }}
                                        </span>
                                    @endforeach
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Reservation Breakdown --}}
                <div class="px-3 my-5 space-y-3">
                    <div class="flex items-center gap-5">
                        <h3 class="font-semibold">Reservation Breakdown</h3>
                        <x-line class="bg-zinc-800/50" />
                    </div>

                    <div class="gap-5 text-xs md:flex">
                        <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check in: </strong>{{ date_format(date_create($date_in),"F j, Y") }}</span></p>
                        <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check out: </strong>{{ date_format(date_create($date_out),"F j, Y") }}</span></p>
                    </div>
                </div>

                <div class="px-3 py-2 space-y-3 border rounded-lg">
                    <div class="flex justify-between">
                        <p class="font-semibold">Description</p>
                        <p class="font-semibold">Amount</p>
                    </div>

                    {{-- Bills to Pay --}}
                    <div class="pt-3 border-t border-dashed">
                        @foreach ($selected_rooms as $room)
                            <div key="{{ $room->id }}" class="flex justify-between px-3 py-1 text-sm transition-all duration-200 ease-in-out rounded-lg hover:bg-slate-100">
                                <p class="capitalize">{{ $room->roomType->name . " " . $room->room_number }}</p>
                                <p>{{ $room->rate }}
                                    @if ($night_count > 1)
                                        {{ " x " . $night_count }}
                                    @endif
                                </p>
                            </div>
                        @endforeach

                        <div class="flex items-center px-3 py-1">
                            <x-line class="bg-zinc-800/50" />
                        </div>

                        @forelse ($selected_amenities as $amenity)
                            <div key="{{ $amenity->id }}" class="flex justify-between px-3 py-1 text-sm transition-all duration-200 ease-in-out rounded-lg hover:bg-slate-100">
                                <p class="capitalize">{{ $amenity->name }}</p>
                                <p>{{ $amenity->price }}</p>
                            </div>
                        @empty
                            <div class="py-1 text-sm text-zinc-800/50">
                                <p>No selected amenities</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end gap-5 px-6">
                    <div class="text-sm text-right">
                        <p class="font-semibold">Sub-Total</p>
                        <p class="">12% VAT</p>
                        <p class="font-semibold text-blue-500">Net Total</p>
                    </div>
                    <div class="text-sm text-right">
                        <p class="font-semibold">{{ number_format($vatable_sales, 2) }}</p>
                        <p class="">{{ number_format($vat, 2) }}</p>
                        <p class="font-semibold text-blue-500">{{ number_format($net_total, 2) }}</p>
                    </div>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<x-secondary-button x-show="!can_submit_payment" x-on:click="$wire.set('can_submit_payment', true)">Send Payment</x-secondary-button>
<x-secondary-button x-show="can_submit_payment" x-on:click="$wire.set('can_submit_payment', false)">Check Reservation Summary</x-secondary-button>

<x-line-vertical />

<x-form.form-section>
    <x-form.form-header step="2" title="Payment" />

    <div x-show="can_submit_payment" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <p class="max-w-sm text-sm">Upload your proof of payment here.</p>

                {{-- Payment Methods --}}
                <div class="grid gap-3 md:grid-cols-2">
                    <div class="flex items-center gap-3 p-3 border rounded-lg">
                        <div class="max-w-[80px] aspect-square w-full rounded-lg"
                            style="background-image: url('https://placehold.co/80');
                                background-size: cover;">
                        </div>
                        <div>
                            <h3 class="font-semibold">GCash</h3>
                            <p class="">+63 917 139 9334</p>
                            <p class="text-xs">Fabio Basbaño</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 border rounded-lg">
                        <div class="max-w-[80px] aspect-square w-full rounded-lg"
                            style="background-image: url('https://placehold.co/80');
                                background-size: cover;">
                        </div>
                        <div>
                            <h3 class="font-semibold">Philippine National Bank</h3>
                            <p class="">0000-0000-0000</p>
                            <p class="text-xs">Amazing View Mountain Resort</p>
                        </div>
                    </div>
                </div>

                <div>
                    <x-filepond::upload
                        wire:model="proof_image_path"
                        placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                    />
                    <x-form.input-error field="proof_image_path" />
                    
                    <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<div class="flex gap-3">
    <x-secondary-button wire:click="submit(true)">Guest Details</x-secondary-button>
    <x-primary-button type="submit">Submit</x-primary-button>
</div>