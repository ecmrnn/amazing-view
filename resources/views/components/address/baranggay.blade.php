@props(['baranggays' => []])

<div>
    <div>
        <x-form.select {{ $attributes->merge(['class' => '']) }}>
            <option value="">Select Baranggay</option>
            @forelse ($baranggays as $baranggay)
                <option value="{{ $baranggay['name'] }}">{{ $baranggay['name'] }}</option>
            @empty
                {{-- No Baranggay --}}
            @endforelse
        </x-form.select>
    </div>
</div>