@props(['districts'])

<div>
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select District</option>
            @forelse ($districts as $district)
                <option value="{{ $district['name'] }}">{{ $district['name'] }}</option>
            @empty
                {{-- No Districts --}}
            @endforelse
        </x-form.select>
    </div>
</div>