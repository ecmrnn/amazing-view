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
        @if ($reports_count > 0)
            <livewire:tables.reports-table />
        @else
            <div class="py-10 space-y-3 text-center border rounded-md border-slate-200">
                <svg class="mx-auto text-zinc-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook"><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M16 2v20"/></svg>
            
                <p class="text-sm font-semibold">No reports found!</p>
            
                @can('create report')
                    <div class="inline-block text-xs">
                        <x-primary-button x-on:click="$dispatch('generate-report', { type: '' })">
                            Click here to create one
                        </x-primary-button>
                    </div>
                @endcan
            </div>        
        @endif
    </div>

    @push('modals')
        {{-- Generate Report Modal --}}
        <x-modal.drawer name="generate-report" maxWidth="lg">
            <div class="p-5" x-on:report-created.window="show = false">
                @livewire('app.report.create-report')
            </div>
        </x-modal.drawer>
    @endpush
</x-app-layout>
