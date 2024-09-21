@props(['cities' => []])

<div>
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select City &#47 Municipality</option>
            @forelse ($cities as $city)
                <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
            @empty
                
            @endforelse
        </x-form.select>
    </div>
</div>