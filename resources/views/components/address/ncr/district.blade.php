<div x-data="{ districts: [] }"
        x-init="districts = await (await fetch('https://psgc.cloud/api/sub-municipalities')).json()"
    >
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select District</option>
            <template x-for="dist in districts" :key="dist.id">
                <option :value="dist.code" x-text="dist.name"></option>
            </template> 
        </x-form.select>
    </div>
</div>