<div class="py-10 space-y-3 text-center text-blue-800 border border-blue-500 border-dashed rounded-md bg-blue-50">
    <svg class="mx-auto text-blue-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-alert-icon lucide-badge-alert"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>

    <p class="text-sm font-semibold">No Promos!</p>

    @can('create promo')
        <x-primary-button x-on:click="$dispatch('open-modal', 'add-promo-modal')">
            Create Promo
        </x-primary-button>
    @endcan
</div>