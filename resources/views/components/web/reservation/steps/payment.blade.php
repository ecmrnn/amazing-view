<x-form.form-section>
    <x-form.form-header step="1" title="Reservation Summary" />

    <div class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <p class="text-sm">Kindly check if the information you provided is <strong class="text-blue-500">correct</strong>.</p>

                {{-- Summary --}}
                <div class="grid border rounded-lg md:grid-cols-2">
                    {{-- Guest Details --}}
                    <div class="px-3 py-2 space-y-2 border-b border-dashed md:border-b-0 md:border-r">
                        <h4 class="font-semibold">Guest Details</h4>
                        <div class="space-y-1 text-xs">
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">ar_on_you</span><span>Juan Dela Cruz</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">cottage</span><span>410 Manila East Rd., Hulo, Pililla, Rizal</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">call</span><span>+63 958 5575 678</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">mail</span><span>delacruz.juan@gmail.com</span></p>
                        </div>
                    </div>

                    {{-- Reservation Details --}}
                    <div class="px-3 py-2 space-y-2">
                        <h4 class="font-semibold">Reservation Details</h4>
                        <div class="space-y-1 text-xs">
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">airline_seat_flat</span><span>Overnight</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">acute</span><span>2:00 PM - 12:00 PM</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">face</span><span>2 Adults, 1 Children</span></p>
                            <p class="flex items-center gap-3"><span class="material-symbols-outlined">door_front</span><span>Room 1, Room 2</span></p>
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
                        <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check in:</strong> January 10, 2024</span></p>
                        <p class="flex items-center gap-3"><span class="material-symbols-outlined">calendar_month</span><span><strong>Check out:</strong> January 11, 2024</span></p>
                    </div>
                </div>

                <div class="px-3 py-2 space-y-3 border rounded-lg">
                    <div class="flex justify-between">
                        <p class="font-semibold">Description</p>
                        <p class="font-semibold">Amount</p>
                    </div>

                    {{-- Bills to Pay --}}
                    <div class="pt-3 border-t border-dashed">
                        <div class="flex justify-between px-3 py-1 text-sm rounded-lg hover:bg-slate-100">
                            <p>Room 1</p>
                            <p>1000.00</p>
                        </div>
                        <div class="flex justify-between px-3 py-1 text-sm rounded-lg hover:bg-slate-100">
                            <p>Room 1</p>
                            <p>1000.00</p>
                        </div>
                        <div class="flex justify-between px-3 py-1 text-sm rounded-lg hover:bg-slate-100">
                            <p>Room 1</p>
                            <p>1000.00</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-5 px-6">
                    <div class="text-sm text-right">
                        <p class="font-semibold">Sub-Total</p>
                        <p class="">12% VAT</p>
                        <p class="font-semibold text-blue-500">Net Total</p>
                    </div>
                    <div class="text-sm text-right">
                        <p class="font-semibold">1000.00</p>
                        <p class="">500.00</p>
                        <p class="font-semibold text-blue-500">1500.00</p>
                    </div>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<x-form.form-section>
    <x-form.form-header step="2" title="Payment" />

    <div class="lg:grid-cols-2 lg:col-span-2">
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

                <x-filepond::upload
                    wire:model="proof_image_path"
                    placeholder="Drag & drop your image or <span class='filepond--label-action'> Browse </span>"
                />

                <x-form.input-error field="proof_image_path" />
                
                <p class="max-w-sm text-xs">Please upload an image &lpar;<strong class="text-blue-500">JPG, JPEG, PNG</strong>&rpar; of the payment slip for your down payment. Max image size &lpar;<strong class="text-blue-500">1MB</strong>&rpar;</p>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<div class="flex gap-3">
    <x-secondary-button>Guest Details</x-secondary-button>
    <x-primary-button type="submit">Submit</x-primary-button>
</div>