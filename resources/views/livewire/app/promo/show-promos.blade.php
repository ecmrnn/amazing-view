<div class="max-w-screen-lg p-5 mx-auto space-y-5 bg-white border rounded-lg border-slate-200">
    <hgroup>
        <h2 class='font-semibold'>Promos</h2>
        <p class='text-xs'>Manage your promos here</p>
    </hgroup>

    @if ($promos->count() > 0)
        <livewire:tables.promo-table />
    @else
        <div class="font-semibold text-center lg:col-span-3 sm:col-span-2">
            <x-table-no-data.promo />
        </div>
    @endif
</div>
