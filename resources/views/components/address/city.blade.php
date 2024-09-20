@props(['province' => ''])

<div x-data="{ cities: [] }"
        @if (!empty($province))
            x-init="cities = await (await fetch('https://psgc.cloud/api/provinces/{{ $province }}/cities-municipalities')).json()"
        @endif
    >
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select City</option>
            <template x-for="city in cities" :key="city.id">
                <option :value="city.name" x-text="city.name"></option>
            </template> 
        </x-form.select>
    </div>
</div>