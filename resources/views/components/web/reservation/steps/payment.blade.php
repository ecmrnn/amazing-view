{{-- Loader --}}
<div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Processing your amazing reservation~</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div class="grid gap-5 lg:grid-cols-3">
    <div class="p-5 space-y-5 overflow-hidden bg-white rounded-lg shadow-sm lg:col-span-2">
        {{-- Step Header --}}
        <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
            <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                <p class="text-5xl font-bold">3</p>
            </div>
    
            <div>
                <p class="text-lg font-bold">Payment</p>
                <p class="max-w-sm text-sm leading-tight">Review your reservation details or if you can, you may upload an image of your downpayment</p>
            </div>
        </div>
    
        <div class="space-y-5">
            <hgroup>
                <h2 class="font-semibold">Reservation Summary</h2>
                <p class="text-sm">Kindly check if the information you provided is <strong class="text-blue-500">correct</strong>.</p>
            </hgroup>

            {{-- Guest and Reservation Details --}}
            <section class="grid border rounded-lg md:grid-cols-2 ">
                {{-- Guest Details --}}
                <div class="p-3 space-y-2 bg-white border-b border-dashed rounded-l-lg sm:p-5 md:border-b-0 md:border-r">
                    <h4 class="font-semibold">Guest Details</h4>
                    <div class="space-y-1 text-sm">
                        <p class="flex items-center gap-3 capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smile"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/></svg>
                            <span>{{ $first_name . " " . $last_name }}</span>
                        </p>
                        <p class="flex items-center gap-3 capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            <span>{{ is_array($address) ? trim(implode(', ', $address), ',') : $address }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 7.94"/><path d="M14.05 6A5 5 0 0 1 18 10"/></svg>
                            <span>{{ $phone }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <span>{{ $email }}</span>
                        </p>
                    </div>
                </div>
                {{-- Reservation Details --}}
                <div class="p-3 space-y-2 bg-white rounded-r-lg sm:p-5">
                    <div class="flex justify-between">
                        <h4 class="font-semibold">Reservation Details</h4>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-single"><path d="M3 20v-8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8"/><path d="M5 10V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4"/><path d="M3 18h18"/></svg>
                            <span>
                                @if ($date_in == $date_out)
                                    {{ __('Day Tour') }}
                                @else
                                    {{ __('Overnight') }}
                                @endif
                            </span></p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>2:00 PM - 12:00 PM</span></p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
                            <span>
                                <span x-text="adult_count"></span> <span>Adult<span x-show="adult_count > 1">s</span></span>
                                <span x-show="children_count > 0">&amp; <span x-text="children_count"></span> <span>Child<span x-show="children_count > 1">ren</span></span></span>
                            </span>
                        </p>
                        <p class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-closed"><path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/><path d="M2 20h20"/><path d="M14 12v.01"/></svg>
                            <span class="space-x-1">
                                @foreach ($selected_rooms as $room)
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
                    <span><strong>Check in: </strong>{{ date_format(date_create($date_in), 'F j, Y') }}</span></p>
                <p class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-arrow-down"><path d="m14 18 4 4 4-4"/><path d="M16 2v4"/><path d="M18 14v8"/><path d="M21 11.354V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7.343"/><path d="M3 10h18"/><path d="M8 2v4"/></svg>
                    <span><strong>Check out: </strong>{{ date_format(date_create($date_out), 'F j, Y') }}</span></p>
            </div>

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
                                    <p>{{ $room->building->prefix . ' ' . $room->room_number}}</p>
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
        </div>
    </div>
    
    <aside class="self-start p-5 space-y-3 bg-white rounded-lg shadow-sm">
        <hgroup>
            <h3 class="text-lg font-semibold">Submit Payment</h3>
            <p class="text-sm">A minimum of <strong><x-currency />500.00</strong> must be paid to process your reservation. Kindly send an image of your receipt to the email we have sent to your email<strong>{{ ' ' . $email }}</strong> or <strong>upload</strong> an image on the dropbox below. </p>
        </hgroup>

        {{-- Payment Methods --}}
        <div class="grid">
            <div class="flex items-center gap-3 p-3 bg-white border border-gray-300 rounded-lg">
                <div class="max-w-[80px] aspect-square w-full rounded-lg"
                    style="background-image: url({{ asset('storage/global/gcash-qr.png') }});
                        background-size: cover;">
                </div>
                <div>
                    <h3 class="font-semibold">GCash</h3>
                    <p class="">+63 917 139 9334</p>
                    <p class="text-xs">Fabio Basbaño</p>
                </div>
            </div>
        </div>

        <div class="w-full">
            <x-filepond::upload
                wire:model="proof_image_path"
                placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
            />
            <x-form.input-error field="proof_image_path" />
            <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Maximum image size &lpar;<strong class="text-blue-500">1MB or 1024KB</strong>&rpar;</p>
        </div>
        
        <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Submit</x-primary-button>
    </aside>
</div>