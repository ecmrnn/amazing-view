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
                    <x-icon-button x-ref='content' x-on:click="$dispatch('generate-report', { type: '' })">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14" /><path d="M12 5v14" /></svg>
                    </x-icon-button>
                </x-tooltip>

                <div class="w-[1px] h-1/2 bg-gray-300"></div>
            </div>
        </div>
    </x-slot:header>

    {{-- Report Cards --}}
    <livewire:app.cards.report-cards />

    {{-- Report  Table --}}
    <livewire:app.report.show-reports />

    @push('modals')
        {{-- Generate Report Modal --}}
        <x-modal.drawer name="generate-report" maxWidth="lg">
            <div class="p-5" x-on:report-created.window="show = false">
                @livewire('app.report.create-report')
            </div>
        </x-modal.drawer>
    @endpush
</x-app-layout>
