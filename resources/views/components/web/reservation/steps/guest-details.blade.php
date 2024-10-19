{{-- Personal & Contact Information --}}
<x-form.form-section>
    <x-form.form-header step="1" title="Personal &amp; Contact Information" />

    <div x-show="!can_select_address" x-collapse.duration.1000ms class="lg:grid-cols-2 lg:col-span-2">
        <x-form.form-body>
            <div class="p-5 space-y-3">
                {{-- Personal Information --}}
                <div class="space-y-3">
                    <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">First Name</strong> and <strong class="text-blue-500">Last Name</strong> on their respective input fields below.</p>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            <x-form.input-text
                                wire:model.live="first_name"
                                x-model="first_name"
                                label="First Name"
                                id="first_name"
                                minlength="2"
                                class="capitalize"
                            />
                            <x-form.input-error field="first_name" />
                        </div>
                        <div class="space-y-2">
                            <x-form.input-text
                                wire:model.live="last_name"
                                x-model="last_name"
                                label="Last Name"
                                id="last_name"
                                minlength="2"
                                class="capitalize"
                            />
                            <x-form.input-error field="last_name" />
                        </div>
                    </div>
                </div>
                
                {{-- Contact Information --}}
                <div class="space-y-3">
                    <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">Email</strong> and <strong class="text-blue-500">Contact Number</strong> on their respective input fields below.</p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            <x-form.input-text
                                wire:model.live="email"
                                x-model="email"
                                label="Email"
                                id="email"
                                type="email"
                            />
                            <x-form.input-error field="email" />
                        </div>
                        <div class="space-y-2">
                            <x-form.input-text
                                wire:model.live="phone"
                                x-model="phone"
                                label="Contact Number"
                                id="phone"
                                minlength="11"
                                maxlength="11"
                            />
                            <x-form.input-error field="phone" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <x-primary-button type="button" wire:click="selectAddress()">Select Address</x-primary-button>
                    <p wire:loading wire:target='selectAddress()' class="text-xs font-semibold">Please wait while we load the next form.</p>
                </div>
                
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

{{-- Address --}}
<x-form.form-section>
    <x-form.form-header step="2" title="Address" />

    <div x-show="can_select_address" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <div class="space-y-3 overflow-auto"
                        x-init="
                        $watch('region', value => {
                            province = '';
                            city = '';
                            baranggay = '';
                            $wire.cities = [];
                            $wire.baranggays = [];
                        })
                        $watch('province', value => {
                            city = '';
                            baranggay = '';
                            $wire.cities = [];
                            $wire.baranggays = [];
                        })
                        $watch('city', value => {baranggay = '';})
                    ">

                    
                    @if (!empty($regions))
                        <p class="max-w-sm text-sm">Kindly select your home address using the dropdown options below starting with your region.</p>

                        {{-- Regions & Provinces --}}
                        <div class="flex gap-3">
                            <div class="w-full">
                                <x-address.region
                                    :regions="$regions"
                                    wire:model.live="region"
                                    x-on:change="$wire.getProvinces(region)"
                                    x-model="region" />
                            </div>
                            @if ($region != 'National Capital Region (NCR)')
                                <div class="w-full">
                                    <x-address.province
                                        :provinces="$provinces"
                                        wire:model.live="province"
                                        x-on:change="$wire.getCities(province)"
                                        x-model="province" />
                                </div>
                            @endif
                        </div>
                        {{-- Cities & Manila Districts & Baranggays --}}
                        <div class="flex gap-3">
                            @if ($region == 'National Capital Region (NCR)')
                                <div class="w-full">
                                    <x-address.ncr.city
                                        wire:model.live="city"
                                        x-on:change="$wire.getBaranggays(city)"
                                        x-model="city" />
                                </div>
                                @if ($city == "City of Manila")
                                    <div class="w-full">
                                        <x-address.ncr.district
                                            :districts="$districts"
                                            wire:model.live="district"
                                            x-on:change="$wire.getDistrictBaranggays(district)"
                                            x-model="district" />
                                    </div>
                                @endif
                            @else
                                <div class="w-full">
                                    <x-address.city
                                        :cities="$cities"
                                        wire:model.live="city"
                                        x-on:change="$wire.getBaranggays(city)"
                                        x-model="city" />
                                </div>
                            @endif
                            <div class="w-full">
                                <x-address.baranggay
                                    :baranggays="$baranggays"
                                    wire:model.live="baranggay"
                                    x-model="baranggay"
                                    x-on:change="$wire.setAddress()"
                                />
                            </div>
                        </div>
                        {{-- Street --}}
                        <x-form.input-text
                            wire:model.live="street"
                            x-model="street"
                            x-on:keyup="$wire.setAddress()"
                            label="Street (Optional)"
                            id="street"
                        />
                    @else
                        <div class="space-y-3">
                            <p class="max-w-sm text-sm">Kindly enter your home address in the input field below.</p>
                            <x-form.input-text wire:model.live='street' x-model="street" id="street" name="street" label="Address" />
                        </div>
                    @endif
                    <x-form.input-error field="address" />

                    {{-- Loaders --}}
                    <div wire:loading wire:target="getProvinces" class="text-xs font-semibold">Loading
                        <span x-text="region == 'National Capital Region (NCR)' ? 'Cities...' : 'Provinces...'"></span>
                    </div>
                    <div wire:loading wire:target="getCities" class="text-xs font-semibold">Loading Cities &amp; Municipalities...</div>
                    <div wire:loading wire:target="getBaranggays" class="text-xs font-semibold">Loading Baranggay...</div>
                    <div wire:loading wire:target="getDistrictBaranggays" class="text-xs font-semibold">Loading Baranggay...</div>
                </div>

                <div class="flex gap-1">
                    <x-secondary-button wire:click="submit(true)">Reservation Details</x-secondary-button>
                    <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Payment</x-primary-button>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>