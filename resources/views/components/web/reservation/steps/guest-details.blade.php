<div x-data="{ can_select_address: $wire.entangle('can_select_address') }">
    {{-- Loader --}}
    <div class="fixed top-0 left-0 z-[9999] w-screen h-screen bg-white place-items-center" wire:loading.delay.long wire:target='submit'>
        <div class="grid h-screen place-items-center">
            <div>
                <p class="text-2xl font-bold text-center">Loading, please wait</p>
                <p class="mb-4 text-xs font-semibold text-center">Gathering details about your reservation...</p>
                <svg class="mx-auto animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-circle"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            </div>
        </div>
    </div>
    
    <div class="p-5 space-y-5 bg-white rounded-lg">
        {{-- Step Header --}}
        <div class="flex items-start justify-between">
            <div class="flex flex-col items-start gap-3 sm:gap-5 sm:flex-row">
                <div class="grid w-full text-white bg-blue-500 rounded-md aspect-square max-w-20 place-items-center">
                    <p class="text-5xl font-bold">2</p>
                </div>
                <div>
                    <p class="text-lg font-bold">Guest Details</p>
                    <p class="max-w-sm text-sm leading-tight">Enter your personal and contact information, your address, and vehicles to ride on the way to our resort!</p>
                </div>
            </div>

            <button :class="reservation_type != null ? 'scale-100' : 'scale-0'" type="button" x-on:click="$dispatch('open-modal', 'reset-reservation-modal')" class="flex items-center gap-2 text-xs font-semibold text-red-500 transition-all duration-200 ease-in-out w-max">
                <p>Reset</p>
                <svg class="text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            </button>
        </div>

        {{-- Personal & Contact Information --}}
        <x-form.form-section>
            <div class="relative lg:col-span-2">
                <x-form.form-header title="Personal &amp; Contact Information" subtitle="Enter your name and contact details" class="lg:col-span-2" />
    
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
                        <div class="space-y-5">
                            <hgroup>
                                <h3 class="text-sm font-semibold">First &amp; Last Name</h3>
                                <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">First Name</strong> and <strong class="text-blue-500">Last Name</strong> on their respective input fields below.</p>
                            </hgroup>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-form.input-group>
                                    <x-form.input-text
                                        wire:model.live="first_name"
                                        x-model="first_name"
                                        label="First Name"
                                        id="first_name"
                                        minlength="2"
                                        class="capitalize"
                                    />
                                    <x-form.input-error field="first_name" />
                                </x-form.input-group>
                                <x-form.input-group>
                                    <x-form.input-text
                                        wire:model.live="last_name"
                                        x-model="last_name"
                                        label="Last Name"
                                        id="last_name"
                                        minlength="2"
                                        class="capitalize"
                                    />
                                    <x-form.input-error field="last_name" />
                                </x-form.input-group>
                            </div>
                        </div>
    
                        {{-- Contact Information --}}
                        <div class="space-y-5">
                            <hgroup>
                                <h3 class="text-sm font-semibold">Email &amp; Contact Number</h3>
                                <p class="max-w-sm text-xs">Kindly enter your <strong class="text-blue-500">Email</strong> and <strong class="text-blue-500">Contact Number</strong> on their respective input fields below.</p>
                            </hgroup>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-form.input-group>
                                    <x-form.input-text
                                        wire:model.live="email"
                                        x-model="email"
                                        label="Email"
                                        id="email"
                                        type="email"
                                    />
                                    <x-form.input-error field="email" />
                                </x-form.input-group>
                                <x-form.input-group>
                                    <x-form.input-text
                                        wire:model.live="phone"
                                        x-model="phone"
                                        label="Contact Number"
                                        id="phone"
                                        minlength="11"
                                        maxlength="11"
                                    />
                                    <x-form.input-error field="phone" />
                                </x-form.input-group>
                            </div>
                        </div>

                        <div class="space-y-5 overflow-auto"
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
    
    
                            @if (!empty($regions) && $guest_found == false)
                                <hgroup>
                                    <h2 class="font-semibold">Home Address</h2>
                                    <p class="max-w-sm text-xs">Kindly select your home address using the dropdown options below starting with your region.</p>
                                </hgroup>
    
                                {{-- Regions & Provinces --}}
                                <div class="flex flex-col gap-5 sm:flex-row">
                                    <div class="w-full space-y-2">
                                        <x-address.region
                                            :regions="$regions"
                                            wire:model.live="region"
                                            x-on:change="$wire.getProvinces(region)"
                                            x-model="region" />
                                    </div>
                                    @if ($region != 'National Capital Region (NCR)')
                                        <div class="w-full space-y-2">
                                            <x-address.province
                                                x-bind:disabled="region == '' || region == null"
                                                :provinces="$provinces"
                                                wire:model.live="province"
                                                x-on:change="$wire.getCities(province)"
                                                x-model="province" />
                                            <x-form.input-error field="province" />
                                        </div>
                                    @endif
                                </div>
                                {{-- Cities & Manila Districts & Baranggays --}}
                                <div class="flex flex-col gap-5 sm:flex-row">
                                    @if ($region == 'National Capital Region (NCR)')
                                        <div class="w-full space-y-2">
                                            <x-address.ncr.city
                                                x-bind:disabled="region == '' || region == null"
                                                wire:model.live="city"
                                                x-on:change="$wire.getBaranggays(city)"
                                                x-model="city" />
                                            <x-form.input-error field="city" />
                                        </div>
                                        @if ($city == "City of Manila")
                                            <div class="w-full space-y-2">
                                                <x-address.ncr.district
                                                    x-bind:disabled="city == '' || city == null"
                                                    :districts="$districts"
                                                    wire:model.live="district"
                                                    x-on:change="$wire.getDistrictBaranggays(district)"
                                                    x-model="district" />
                                                <x-form.input-error field="district" />
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full space-y-2">
                                            <x-address.city
                                                :cities="$cities"
                                                x-bind:disabled="province == '' || province == null"
                                                wire:model.live="city"
                                                x-on:change="$wire.getBaranggays(city)"
                                                x-model="city" />
                                            <x-form.input-error field="city" />
                                        </div>
                                    @endif
                                    <div class="w-full space-y-2">
                                        <x-address.baranggay
                                            x-bind:disabled="city == '' || city == null || (city == 'City of Manila' && (district == '' || district == null))"
                                            :baranggays="$baranggays"
                                            wire:model.live="baranggay"
                                            x-model="baranggay"
                                        />
                                        <x-form.input-error field="baranggay" />
                                    </div>
                                </div>
                                {{-- Street --}}
                                <x-form.input-text
                                    class="capitalize"
                                    wire:model.live="street"
                                    x-model="street"
                                    label="Street (Optional)"
                                    id="street"
                                />
                                <x-form.input-error field="street" />
                            @else
                                <x-form.input-group>
                                    <div>
                                        <h2 class="font-semibold">Home Address</h2>
                                        <p class="max-w-sm text-xs">Kindly enter your home address in the input field below.</p>
                                    </div>

                                    <x-form.input-text wire:model.live='address' x-model="address" id="address" name="address" label="Address" />
                                </x-form.input-group>
                            @endif
                            <x-form.input-error field="address" />
    
                            {{-- Loaders --}}
                            <x-loading wire:loading wire:target="getProvinces">Loading
                                <span x-text="region == 'National Capital Region (NCR)' ? 'Cities...' : 'Provinces...'"></span>
                            </x-loading>
                            <x-loading wire:loading wire:target="getCities">Loading Cities &amp; Municipalities...</x-loading>
                            <x-loading wire:loading wire:target="getBaranggays">Loading Baranggay...</x-loading>
                            <x-loading wire:loading wire:target="getDistrictBaranggays">Loading Baranggay...</x-loading>
                        </div>
    
                        <div class="flex items-center gap-5">
                            <x-primary-button type="button" wire:click="additionalDetails()">Additional Details</x-primary-button>
                            <x-loading wire:loading.block wire:target='additionalDetails'>Please wait while we load the next form.</x-loading>
                        </div>
                    </div>
                </x-form.form-body>
            </div>
        </x-form.form-section>
    
        <x-form.form-section>
            <x-form.form-header title="Additional Details" subtitle="Enter your vehicle for parking" />
    
            <div x-show="can_select_address" x-collapse.duration.1000ms>
                <x-form.form-body>
                    <div class="p-5 pt-0 space-y-5">
                        <div class="flex items-start justify-between">
                            <hgroup>
                                <h3 class="text-sm font-semibold">Vehicle</h3>
                                <p class="max-w-sm text-sm">Add your vehicle here for parking reservations</p>
                            </hgroup>
    
                            <x-primary-button class="flex-shrink-0" type="button" x-on:click="$dispatch('open-modal', 'add-vehicle-modal')">Add Vehicle</x-primary-button>
                        </div>
    
                        <!-- Form -->
                        <div class="space-y-5">
                            @forelse ($cars as $car)
                                <div class="flex items-start justify-between p-3 bg-white border rounded-lg">
                                    <div>
                                        <p class="font-semibold">{{ $car['plate_number'] }}</p>
                                        <p class="text-sm">{{ $car['color'] . " / " . $car['make'] . " " . $car['model'] }}</p>
                                    </div>
    
                                    {{-- Remove Room button --}}
                                    <button
                                        type="button"
                                        class="text-xs font-semibold text-red-500"
                                        wire:click="removeVehicle('{{ $car['plate_number'] }}')">
                                        <span wire:loading.remove wire:target="removeVehicle('{{ $car['plate_number'] }}')">Remove</span>
                                        <span wire:loading wire:target="removeVehicle('{{ $car['plate_number'] }}')">Removing</span>
                                    </button>
                                </div>
                            @empty
                                <div class="py-10 space-y-3 bg-white border rounded-lg">
                                    <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car-front"><path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"/><path d="M7 14h.01"/><path d="M17 14h.01"/><rect width="18" height="8" x="3" y="10" rx="2"/><path d="M5 18v2"/><path d="M19 18v2"/></svg>
    
                                    <p class="text-sm font-semibold text-center">No Cars Yet</p>
    
                                    <p class="max-w-xs mx-auto text-xs font-bold text-zinc-800/50">Add your vehicle here to be used to go to our resort</p>
                                </div>
                            @endforelse
                        </div>
    
                        <x-modal.full name="add-vehicle-modal" maxWidth="sm">
                            <div class="p-5 space-y-3" x-on:car-added.window="show = false">
                                <hgroup>
                                    <h3 class="font-semibold">Add Vehicle</h3>
                                    <p class="text-sm">Enter the details of your vehicle below</p>
                                </hgroup>
    
                                <x-form.input-group>
                                    <x-form.input-text id="plate_number" class="uppercase" name="plate_number" label="Plate Number" wire:model="plate_number" />
                                    <x-form.input-error field="plate_number" />
                                </x-form.input-group>
    
                                <x-form.input-group>
                                    <x-form.input-text id="make" name="make" class="capitalize" label="Brand / Make" wire:model="make" />
                                    <x-form.input-error field="make" />
                                </x-form.input-group>
    
                                <x-form.input-group>
                                    <x-form.input-text id="model" name="model" class="capitalize" label="Model" wire:model="model" />
                                    <x-form.input-error field="model" />
                                </x-form.input-group>
    
                                <x-form.input-group>
                                    <x-form.input-text id="color" name="color" class="capitalize" label="Color" wire:model="color" />
                                    <x-form.input-error field="color" />
                                </x-form.input-group>
    
                                <div class="flex justify-between">
                                    <x-secondary-button type="button" x-on:click="show = false">Cancel</x-secondary-button>
                                    <x-primary-button type="button" wire:click='addVehicle'>Add Vehicle</x-primary-button>
                                </div>
                            </div>
                        </x-modal.full>
                    </div>
                </x-form.form-body>
            </div>
        </x-form.form-section>
    
        <div class="flex justify-end gap-1">
            <x-secondary-button wire:click="submit(true)">Back</x-secondary-button>
            <x-primary-button x-on:click="() => { $nextTick(() => { $refs.form.scrollIntoView({ behavior: 'smooth' }); }); }" type="submit">Continue</x-primary-button>
        </div>
    </div>

    <x-modal.full name='show-guest-confirmation' maxWidth='sm'>
        <div class="p-5 space-y-5" x-on:guest-found.window="show = false">
            <hgroup>
                <h2 class="font-semibold">Welcome back!</h2>
                <p class="text-xs">We found that your email is already used in a previous reservation, do you wish to use your old account or proceed with a new one?</p>
            </hgroup>

            <x-note>Creating a new account will override your old account</x-note>

            <x-loading wire:loading wire:target='guestFound'>Using old account, please wait</x-loading>

            <div class="flex justify-end gap-1">
                <x-secondary-button type="button" wire:loading.attr='disabled' wire:click='guestFound'>Use Old Account</x-secondary-button>
                <x-primary-button type="button" wire:loading.attr='disabled' x-on:click="can_select_address = true; show = false">Create New Account</x-primary-button>
            </div>
        </div>
    </x-modal.full>
</div>