<x-app-layout>
    <x-slot:header>
        <div class="flex items-center justify-between gap-3 p-5 py-3 bg-white rounded-lg">
            <hgroup>
                <h1 class="text-xl font-bold leading-tight text-gray-800">
                    {{ __('Reservations') }}
                </h1>
                <p class="text-xs">Manage your reservations here</p>
            </hgroup>

            @can('create room')
                <x-primary-button class="text-xs">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span>Add Room</span>
                    </div>
                </x-primary-button>
            @endcan
        </div>
    </x-slot:header>

    <div class="p-3 space-y-5 bg-white rounded-lg sm:p-5">
        <div class="flex items-center justify-between gap-3 sm:items-start">
            <div class="flex items-center gap-3 sm:gap-5">
                <x-tooltip text="Back" dir="bottom">
                    <a x-ref="content" href="{{ route('app.reservations.index')}}" wire:navigate>
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        </x-icon-button>
                    </a>
                </x-tooltip>
            
                <div>
                    <h2 class="text-lg font-semibold">{{ $reservation->rid }}</h2>
                    <p class="max-w-sm text-xs">Confirm and view reservation.</p>
                </div>
            </div>
            {{-- Actions --}}
            <div class="flex items-start gap-1">
                <x-secondary-button class="hidden text-xs sm:block" x-on:click="alert('Downloading PDF... soon...')">Download PDF</x-secondary-button>
                <x-icon-button class="sm:hidden" x-on:click="alert('Downloading PDF... soon...')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                </x-icon-button>

                
                <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                    <x-secondary-button class="hidden text-xs sm:block">Create Invoice</x-secondary-button>
                </a>
                <a href="{{ route('app.billings.create', ['rid' => $reservation->rid]) }}" wire:navigate>
                    <x-icon-button class="sm:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    </x-icon-button>
                </a>
            </div>
        </div>

        <section class="grid grid-cols-1 gap-3 lg:grid-cols-3 sm:gap-5">
            <article class="p-3 border rounded-lg lg:col-span-2 sm:p-5">

            </article>

            {{-- Activities --}}
            <aside class="p-3 space-y-3 rounded-lg sm:p-5 bg-slate-50/50 sm:space-y-5">
                <hgroup>
                    <h3 class="font-semibold">Activity of this reservation</h3>
                    <p class="max-w-sm text-xs">Track the activities made on this reservation here</p>
                </hgroup>

                <div class="space-y-1">
                    <div class="flex items-center gap-5 py-3 pl-5 bg-white rounded-md shadow-sm sm:gap-5">
                        <time datetime="2024-07-07" class="text-xs">12:00 AM</time>

                        <x-line class="hidden bg-zinc-800/50 sm:block" />

                        <hgroup>
                            <h4 class="text-sm font-semibold line-clamp-1">Reservation updated</h4>
                            <p class="text-xs">Juan Dela Cruz</p>
                        </hgroup>
                    </div>
                    <div class="flex items-center gap-5 py-3 pl-5 bg-white rounded-md shadow-sm sm:gap-5">
                        <time datetime="2024-07-07" class="text-xs">12:00 AM</time>

                        <x-line class="hidden bg-zinc-800/50 sm:block" />

                        <hgroup>
                            <h4 class="text-sm font-semibold line-clamp-1">Reservation updated</h4>
                            <p class="text-xs">Juan Dela Cruz</p>
                        </hgroup>
                    </div>
                    <div class="flex items-center gap-5 py-3 pl-5 bg-white rounded-md shadow-sm sm:gap-5">
                        <time datetime="2024-07-07" class="text-xs">12:00 AM</time>

                        <x-line class="hidden bg-zinc-800/50 sm:block" />

                        <hgroup>
                            <h4 class="text-sm font-semibold line-clamp-1">Reservation updated</h4>
                            <p class="text-xs">Juan Dela Cruz</p>
                        </hgroup>
                    </div>
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>  