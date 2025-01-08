<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between w-full">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reports') }}
                </h1>
                <p class="text-xs">Manage your reports here</p>
            </hgroup>

            <div class="flex items-center">
                <x-tooltip text="Create Report" dir="bottom">
                    <button x-ref='content' x-on:click="$dispatch('generate-report', { type: '' })" class="grid w-10 my-1 rounded-md aspect-square place-items-center hover:bg-slate-50 focus:text-zinc-800 focus:border-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                    </button>
                </x-tooltip>

                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    {{-- Report Cards --}}
    <livewire:app.cards.report-cards />

    {{-- Report  Table --}}
    <div class="p-5 bg-white border rounded-lg border-slate-200">
        <livewire:tables.reports-table />
    </div>

    @push('modals')
        {{-- Generate Report Modal --}}
        <x-modal.drawer name="generate-report" maxWidth="lg">
            <div class="p-5 space-y-5">
                <hgroup>
                    <h3 class="font-semibold">Generate Report</h3>
                    <p class="text-xs">Choose a report type you want to generate.</p>
                </hgroup>

                @livewire('app.report.create-report')
            </div>
        </x-modal.drawer>
    @endpush
</x-app-layout>
