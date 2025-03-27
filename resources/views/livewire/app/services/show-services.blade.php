<div class="max-w-screen-lg mx-auto space-y-5">
    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Services</h2>
            <p class="text-xs">View your services here</p>
        </hgroup>

        @if ($services > 0)
            <livewire:services-table />
        @else
            <div class="font-semibold text-center">
                <x-table-no-data.services />
            </div>
        @endif
    </div>

    <x-modal.full name='add-service-modal' maxWidth='sm'>
        <livewire:app.services.create-service />
    </x-modal.full>
</div>
