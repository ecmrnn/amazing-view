@props([
    'region',
    'regions',
    'province',
    'provinces',
    'city',
    'cities',
    'district',
    'districts',
    'baranggay',
    'baranggays',
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
                            x-model="baranggay" />
                    </div>
                </div>

                <x-form.input-text label="Street (Optional)" id="street" />

                {{-- Loaders --}}
                <div wire:loading wire:target="getProvinces" class="text-xs font-semibold">Loading 
                    <span x-text="region == 'National Capital Region (NCR)' ? 'Cities...' : 'Provinces...'"></span>
                </div>
                <div wire:loading wire:target="getCities" class="text-xs font-semibold">Loading Cities &amp; Municipalities...</div>
                <div wire:loading wire:target="getBaranggays" class="text-xs font-semibold">Loading Baranggay...</div>
                <div wire:loading wire:target="getDistrictBaranggays" class="text-xs font-semibold">Loading Baranggay...</div>
            </div>
        </x-form.form-body>
    </div>
</x-form.form-section>

<x-line-vertical />

<div class="flex gap-3">
    <x-secondary-button>Reservation Details</x-secondary-button>
    <x-primary-button>Payment</x-primary-button>
</div>