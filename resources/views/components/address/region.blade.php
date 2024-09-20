<div x-data="{ regions: [] }"
x-init="regions = await (await fetch('https://psgc.cloud/api/regions')).json()">
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Region</option>
            <template x-for="region in regions" :key="region.id">
                <option :value="region.name" x-text="region.name"></option>
            </template> 
        </x-form.select>
    </div>
</div>