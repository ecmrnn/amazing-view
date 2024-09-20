<div x-data="{ cities: [] }"
        x-init="cities = await (await fetch('https://psgc.cloud/api/regions/1300000000/cities')).json()"
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