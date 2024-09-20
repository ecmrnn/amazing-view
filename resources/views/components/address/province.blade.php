@props(['region' => ''])

<div x-data="{ provinces: [] }"
        @if (!empty($region))
            x-init="provinces = await (await fetch('https://psgc.cloud/api/regions/{{ $region }}/provinces')).json()"
        @endif
    >
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Province</option>
            <template x-for="province in provinces" :key="province.id">
                <option :value="province.name" x-text="province.name"></option>
            </template> 
        </x-form.select>
    </div>
</div>