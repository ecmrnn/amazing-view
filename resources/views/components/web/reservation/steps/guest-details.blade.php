{{-- Loader --}}
<div class="fixed top-0 left-0 z-50 w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
    <div class="grid h-screen place-items-center">
        <div>
            <p class="text-2xl font-bold text-center">Loading, please wait</p>
            <p class="mb-4 text-xs font-semibold text-center">Sending email about your reservation...</p>
            <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
        </div>
    </div>
</div>

<div x-data="{
        can_select_address: @entangle('can_select_address')
    }">
    {{-- Personal & Contact Information --}}
    <x-form.form-section>
        <div class="relative lg:col-span-2">
            <x-form.form-header step="1" title="Personal &amp; Contact Information" class="lg:col-span-2" />
    
            <button type="button"
                :class="can_select_address ? 'scale-100' : 'scale-0'"
                class="absolute right-0 px-5 py-2 transition-all duration-200 ease-in-out -translate-y-1/2 top-1/2"
                x-on:click="can_select_address = false">
                <p class="text-xs font-semibold">Edit</p>
            </button>
        </div>
    
        <div x-show="!can_select_address" x-collapse.duration.1000ms>
            {{-- Personal Information --}}
            <x-form.form-body>
                <div class="p-5 pt-0 space-y-5">
                    <div class="space-y-3">
                        <hgroup>
                            <h3 class="text-sm font-semibold">First &amp; Last Name</h3>
                            <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">First Name</strong> and <strong class="text-blue-500">Last Name</strong> on their respective input fields below.</p>
                        </hgroup>
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
                        <hgroup>
                            <h3 class="text-sm font-semibold">Email &amp; Contact Number</h3>
                            <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">Email</strong> and <strong class="text-blue-500">Contact Number</strong> on their respective input fields below.</p>
                        </hgroup>
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
    
                    {{-- Vehicle --}}
                    {{-- <div class="space-y-3">
                        <hgroup>
                            <h3 class="text-sm font-semibold">Vehicle</h3>
                            <p class="text-sm">If you have a private vehicle, enter your vehicle details below</p>
                        </hgroup>
                        <x-note>
                            <p class="max-w-sm">These details will be used by our security personnels to identify you upon arrival to the resort.</p>
                        </x-note>
                        <div>
    
                        </div>
                    </div> --}}
    
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
                <div class="p-5 pt-0 space-y-3">
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
    
                </div>
            </x-form.form-body>
        </div>
    </x-form.form-section>

    <x-line-vertical />

    <div class="flex gap-1">
        <x-secondary-button wire:click="submit(true)">Reservation Details</x-secondary-button>
        <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Payment</x-primary-button>
    </div>
</div>