@props(['provinces' => []])

<div>
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Province</option>
            @forelse ($provinces as $province)
                <option value="{{ $province['name'] }}">{{ $province['name'] }}</option>
            @empty
                {{-- No Province --}}
            @endforelse
        </x-form.select>
    </div>
</div>