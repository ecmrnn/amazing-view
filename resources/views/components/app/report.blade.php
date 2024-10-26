@props([
    'title' => 'Lorem ipsum',
    'description' => 'dolor sit amet.',
])

<x-secondary-button {{ $attributes->merge(['class' => 'overflow-hidden bg-white rounded-lg text-left flex items-start p-3 sm:p-5 border-transparent']) }}>
    <div class="space-y-3">
        <div class="p-3 text-white grid place-items-center w-full max-w-[50px] rounded-lg aspect-square  bg-gradient-to-r from-blue-500 to-blue-600">
            {{ $icon }}
        </div>
        
        <div>
            <p class="font-semibold text-md">{{ $title }}</p>
            <p class="text-xs font-normal">{{ $description }}</p>
        </div>
    </div>
</x-secondary-button>