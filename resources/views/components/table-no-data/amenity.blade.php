<div class="py-10 space-y-3 text-center text-blue-800 border border-blue-500 border-dashed rounded-md bg-blue-50">
    <svg class="mx-auto text-blue-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4" /><path d="M2 10h4" /><path d="M2 14h4" /><path d="M2 18h4" /><rect width="16" height="20" x="4" y="2" rx="2" /><path d="M16 2v20" /></svg>

    <p class="text-sm font-semibold">No Amenities!</p>

    @can('create amenity')
        <x-primary-button x-on:click="$dispatch('open-modal', 'add-amenity-modal')">
            Create Amenity
        </x-primary-button>
    @endcan
</div>