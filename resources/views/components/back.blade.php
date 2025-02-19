<x-tooltip text="Back" dir="bottom">
    <a x-ref="content" href="{{ url()->previous() }}" wire:navigate>
        <x-icon-button>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-arrow-left">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg>
        </x-icon-button>
    </a>
</x-tooltip>
