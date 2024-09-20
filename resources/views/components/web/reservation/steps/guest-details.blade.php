@props([
    'region',
    'province',
    'city',
    'district',
])

<x-form.form-section>
    <x-form.form-header step="1" title="Personal &amp; Contact Information" />

    <div class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="grid grid-cols-2">
                {{-- Personal Information --}}
                <div class="p-5 space-y-3 border-r border-dashed">
                    <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">First Name</strong> and <strong class="text-blue-500">Last Name</strong> on their respective input fields below.</p>

                    <div>
                        <x-form.input-text label="First Name" id="first_name" />
                    </div>
                    <div>
                        <x-form.input-text label="Last Name" id="last_name" />
                    </div>
                </div>
                {{-- Contact Information --}}
                <div class="p-5 space-y-3">
                    <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">Email</strong> and <strong class="text-blue-500">Contact Number</strong> on their respective input fields below.</p>

                    <div>
                        <x-form.input-text label="Email" id="email" type="email" />
                    </div>
                    <div>
                        <x-form.input-text label="Contact Number" id="phone" x-mask="9999 999 9999" />
                    </div>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<x-form.form-section>
    <x-form.form-header step="2" title="Address" />

    <div>
        <x-form.form-body>
            <div class="p-5 space-y-3 overflow-auto" 
                x-init="
                    $watch('region', value => {province = ''; city = ''; baranggay = '';})
                    $watch('province', value => {city = ''; baranggay = '';})
                    $watch('city', value => {baranggay = '';})
                ">
                
                <div class="flex gap-3">
                    <div class="w-full">
                        <x-address.region
                            wire:model.live="region"
                            x-model="region" />
                    </div>

                    @if ($region != 'National Capital Region (NCR)')
                        <div class="w-full">
                            <x-address.province :region="$region"
                                wire:model.live="province"
                                x-model="province" />
                        </div>
                    @endif
                </div>

                <div class="flex gap-3">
                    @if ($region == 'National Capital Region (NCR)')
                        <div class="w-full">
                            <x-address.ncr.city
                                wire:model.live="city"
                                x-model="city" />
                        </div>

                        @if ($city == "City of Manila")
                            <div class="w-full">
                                <x-address.ncr.district
                                    wire:model.live="district"
                                    x-model="district" />
                            </div>

                            <div class="w-full">
                                <x-address.ncr.baranggay :district="$district"
                                    wire:model.live="baranggay"
                                    x-model="baranggay" />
                            </div>
                        @else
                            <div class="w-full">
                                <x-address.baranggay :city="$city"
                                    wire:model.live="baranggay"
                                    x-model="baranggay" />
                            </div>
                        @endif

                    @else
                        <div class="w-full">
                            <x-address.city :province="$province"
                                wire:model.live="city"
                                x-model="city" />
                        </div>
                        
                        <div class="w-full">
                            <x-address.baranggay :city="$city"
                                wire:model.live="baranggay"
                                x-model="baranggay" />
                        </div>
                    @endif
                </div>

                <x-form.input-text label="Street (Optional)" id="street" />
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>