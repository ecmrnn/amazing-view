<div class="py-10 space-y-3">
    <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M16 2v20"/></svg>

    <p class="text-sm font-semibold text-center">No rooms found!</p>

    @can('create room')
        <a class="inline-block text-xs" href="{{ route('app.room.create', ['type' => $room_type_id]) }}" wire:navigate>
            <x-primary-button>
                Click here to create one
            </x-primary-button>
        </a>
    @endcan

    @cannot('create room')
        <p class="max-w-xs mx-auto text-xs font-bold text-zinc-800/50">Ask the administrator to add a room first to proceed</p>
    @endcannot
</div>
