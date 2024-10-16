@props([
    'width' => '16',
    'height' => '16',
    'edit_link' => '',
    'view_link' => '',
])

<div class="flex gap-1">
    @can('update reservation')
        <x-tooltip text="Edit" dir="top">
            <a x-ref="content" href="{{ route($edit_link, ['reservation' => $row->rid]) }}" wire:navigate
                class="block p-2 transition-all duration-200 ease-in-out bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-600 hover:text-white hover:border-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-pencil">
                    <path
                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                    <path d="m15 5 4 4" />
                </svg>
            </a>
        </x-tooltip>
    @endcan

    @can('delete reservation')
        <x-tooltip text="Delete" dir="top">
            <button x-ref="content"
                class="block p-2 transition-all duration-200 ease-in-out bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-600 hover:text-white hover:border-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-trash-2">
                    <path d="M3 6h18" />
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                    <line x1="10" x2="10" y1="11" y2="17" />
                    <line x1="14" x2="14" y1="11" y2="17" />
                </svg>
            </button>
        </x-tooltip>
    @endcan

    <x-tooltip text="View" dir="top">
        <a x-ref="content" href="{{ route($view_link, ['reservation' => $row->rid]) }}" wire:navigate
            class="block p-2 transition-all duration-200 ease-in-out bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-600 hover:text-white hover:border-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-eye">
                <path
                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </a>
    </x-tooltip>
</div>
