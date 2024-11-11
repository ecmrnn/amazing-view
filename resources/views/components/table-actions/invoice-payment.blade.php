@props([
    'width' => '16',
    'height' => '16',
])

<div class="flex justify-end gap-1">
    <x-tooltip text="View" dir="top">
        <x-icon-button x-ref="content">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-eye">
                <path
                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </x-icon-button>
    </x-tooltip>
</div>
