@props(['city' => ''])

<div x-data="{ baranggays: [] }"
        @if (!empty($city))
            x-init="baranggays = await (await fetch('https://psgc.cloud/api/cities-municipalities/{{ $city }}/barangays')).json()"
        @endif
    >
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Baranggay</option>
            <template x-for="baranggay in baranggays" :key="baranggay.id">
                <option :value="baranggay.name" x-text="baranggay.name"></option>
            </template> 
        </x-form.select>
    </div>
</div>