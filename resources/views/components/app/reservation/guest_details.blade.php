<x-form.form-section>
    {{-- Guest Details --}}
    <x-form.form-header step="2" title="Guest Details" />

    <div x-show="can_enter_guest_details && !can_select_room" x-collapse.duration.1000ms>
        <x-form.form-body>
            <div class="p-5 space-y-3">
                <hgroup>
                    <h3 class="text-sm font-semibold">First &amp; Last Name</h3>
                    <p class="text-xs">Enter customer&apos;s name</p>
                </hgroup>
                {{-- First & Last name --}}
                <div class="grid items-start gap-3 sm:grid-cols-2">
                    <div class="space-y-1">
                        <x-form.input-text class="capitalize" wire:model.live='first_name' x-model="first_name"
                            id="first_name" label="First Name" />
                        <x-form.input-error field="first_name" />
                    </div>
                    <div class="space-y-1">
                        <x-form.input-text class="capitalize" wire:model.live='last_name' x-model="last_name"
                            id="last_name" label="Last Name" />
                        <x-form.input-error field="last_name" />
                    </div>
                </div>
                {{-- Contact Number & Email --}}
                <div class="space-y-3">
                    <hgroup>
                        <h3 class="text-sm font-semibold">Contact Number &amp; Email</h3>
                        <p class="max-w-xs text-xs">Note: Email will be used to send and receive reservation notification
                            and confirmation</p>
                    </hgroup>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <x-form.input-text wire:model.live='phone' id="phone" label="Contact Number" />
                            <x-form.input-error field="phone" />
                        </div>
                        <div class="space-y-1">
                            <x-form.input-text wire:model.live='email' id="email" label="Email" />
                            <x-form.input-error field="email" />
                        </div>
                    </div>
                </div>
                {{-- Address --}}
                <div class="space-y-3">
                    <hgroup>
                        <h3 class="text-sm font-semibold">Home Address</h3>
                        <p class="text-xs">Select customer&apos;s home address</p>
                    </hgroup>
                    <div>
                        <div class="space-y-3" x-init="$watch('region', value => {
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
                        $watch('city', value => { baranggay = ''; })">
                            {{-- Regions & Provinces --}}
                            <div class="flex gap-3">
                                <div class="w-full">
                                    <x-address.region :regions="$regions" wire:model.live="region"
                                        x-on:change="$wire.getProvinces(region)" x-model="region" />
                                </div>
                                @if ($region != 'National Capital Region (NCR)')
                                    <div class="w-full">
                                        <x-address.province :provinces="$provinces" wire:model.live="province"
                                            x-on:change="$wire.getCities(province)" x-model="province" />
                                    </div>
                                @endif
                            </div>
                            {{-- Cities & Manila Districts & Baranggays --}}
                            <div class="flex gap-3">
                                @if ($region == 'National Capital Region (NCR)')
                                    <div class="w-full">
                                        <x-address.ncr.city wire:model.live="city"
                                            x-on:change="$wire.getBaranggays(city)" x-model="city" />
                                    </div>
                                    @if ($city == 'City of Manila')
                                        <div class="w-full">
                                            <x-address.ncr.district :districts="$districts" wire:model.live="district"
                                                x-on:change="$wire.getDistrictBaranggays(district)"
                                                x-model="district" />
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full">
                                        <x-address.city :cities="$cities" wire:model.live="city"
                                            x-on:change="$wire.getBaranggays(city)" x-model="city" />
                                    </div>
                                @endif
                                <div class="w-full">
                                    <x-address.baranggay :baranggays="$baranggays" wire:model.live="baranggay"
                                        x-model="baranggay" x-on:change="$wire.setAddress()" />
                                </div>
                            </div>
                            {{-- Street --}}
                            <x-form.input-text wire:model.live="street" x-model="street"
                                x-on:keyup="$wire.setAddress()" label="Street (Optional)" id="street" class="capitalize" />
                            <x-form.input-error field="address" />
                            {{-- Loaders --}}
                            <div wire:loading wire:target="getProvinces" class="text-xs font-semibold">Loading
                                <span
                                    x-text="region == 'National Capital Region (NCR)' ? 'Cities...' : 'Provinces...'"></span>
                            </div>
                            <div wire:loading wire:target="getCities" class="text-xs font-semibold">Loading
                                Cities &amp; Municipalities...</div>
                            <div wire:loading wire:target="getBaranggays" class="text-xs font-semibold">
                                Loading
                                Baranggay...</div>
                            <div wire:loading wire:target="getDistrictBaranggays"
                                class="text-xs font-semibold">
                                Loading Baranggay...</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <x-secondary-button type="button" x-on:click="can_enter_guest_details = false">Edit Reservation Details</x-secondary-button>
                    <x-primary-button type="button" x-on:click="$wire.selectRoom()">Select a Room</x-primary-button>
                    <p class="max-w-xs text-xs font-semibold" wire:loading.delay wire:target="selectRoom()">Please wait while we load the next form.</p>
                </div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>