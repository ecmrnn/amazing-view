@props(['field' => ''])

@error($field)
    <p {{ $attributes->merge(['class' => 'text-xs font-semibold text-red-500']) }}>{{ $message }}</p>
@enderror