@props(['regions' => []])

<div>
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Region</option>
            @forelse ($regions as $region)
                <option key="{{ $region['id'] }}" value="{{ $region['name'] }}">{{ $region['name'] }}</option>
            @empty
                {{-- No Regions --}}
            @endforelse
        </x-form.select>
    </div>
</div>