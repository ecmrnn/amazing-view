<div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
    <div>
        <h2 class="font-semibold">Guest Details</h2>
        <p class="text-xs">Enter the guest&quot;s name, contact info., and address.</p>
    </div>

    <x-note>Email will be used to send and receive reservation notification and confirmation</x-note>

    <div class="grid gap-5 lg:grid-cols-2">
        <div class="p-5 space-y-5 border rounded-md border-slate-200">
            <hgroup>
                <h3 class="text-sm font-semibold">First &amp; Last Name</h3>
                <p class="text-xs">Enter customer&apos;s name</p>
            </hgroup>
            {{-- First & Last name --}}
            <div class="grid items-start gap-5 sm:grid-cols-2">
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
            <div class="space-y-5">
                <hgroup>
                    <h3 class="text-sm font-semibold">Email &amp; Contact Number</h3>
                    <p class="max-w-xs text-xs">Enter a valid email and contact number.</p>
                </hgroup>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-1">
                        <x-form.input-text wire:model.live='email' id="email" label="Email" />
                        <x-form.input-error field="email" />
                    </div>
                    <div class="space-y-1">
                        <x-form.input-text wire:model.live='phone' maxlength="11" id="phone" label="Contact Number" />
                        <x-form.input-error field="phone" />
                    </div>
                </div>
            </div>
        </div>
        {{-- Address --}}
        <div class="p-5 space-y-5 border rounded-md border-slate-200">
            <hgroup>
                <h3 class="text-sm font-semibold">Home Address</h3>
                <p class="text-xs">Select customer&apos;s home address</p>
            </hgroup>

            @if ($guest_found)
                <x-form.input-text id="address" name="address" label="Address" wire:model.live='address' />
            @else
                <div>
                    <div class="space-y-5" x-init="$watch('region', value => {
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
                        <div class="flex gap-5">
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
                        <div class="flex gap-5">
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
                                    x-model="baranggay" />
                            </div>
                        </div>
                        {{-- Street --}}
                        <x-form.input-text wire:model.live="street" x-model="street"
                            label="Street (Optional)" id="street" class="capitalize" />
                        <x-form.input-error field="address" />
                        {{-- Loaders --}}
                        <x-loading wire:loading wire:target="getProvinces">Loading <span x-text="region == 'National Capital Region (NCR)' ? 'Cities...' : 'Provinces...'"></span></x-loading>
                        <x-loading wire:loading wire:target="getCities">Loading Cities &amp; Municipalities...</x-loading>
                        <x-loading wire:loading wire:target="getBaranggays">Loading Baranggay...</x-loading>
                        <x-loading wire:loading wire:target="getDistrictBaranggays">Loading Baranggay...</x-loading>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>