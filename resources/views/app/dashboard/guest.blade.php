<x-app-layout>
    <x-slot:header>
        <hgroup>
            <h1 class="text-xl font-bold leading-tight text-gray-800">Dashboard</h1>
            <p class="text-xs capitalize">Welcome, {{ Auth::user()->first_name }}!</p>
        </hgroup>
    </x-slot:header>

    <div class="max-w-screen-lg mx-auto space-y-5">
        {{-- Announcements --}}
        <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
            <hgroup>
                <h2 class='font-semibold'>Announcements</h2>
                <p class='text-xs'>Stay up to date with the news!</p>
            </hgroup>

            @if ($announcement)
                <div class="space-y-5">
                    <x-img src="{{ $announcement->image }}" />

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <h3 class="font-semibold md:text-2xl">{{ $announcement->title ?? '' }}</h3>
                            <p class="text-xs">{{ date_format(date_create($announcement->created_at), 'F j, Y') }}</p>
                        </div>

                        <p class="p-5 text-sm border rounded-md border-slate-200 bg-slate-50">
                            {!! nl2br(e($announcement->description ?? '')) !!}
                        </p>
                    </div>
                </div>
            @else
                <div class="py-10 space-y-5 font-semibold text-center border border-dashed rounded-md bg-slate-50 border-slate-200">
                    <svg class="mx-auto text-slate-200" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cloud-sun-icon lucide-cloud-sun"><path d="M12 2v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="M20 12h2"/><path d="m19.07 4.93-1.41 1.41"/><path d="M15.947 12.65a4 4 0 0 0-5.925-4.128"/><path d="M13 22H7a5 5 0 1 1 4.9-6H13a3 3 0 0 1 0 6Z"/></svg>

                    <p class="text-slate-400">{!! $quote !!}</p>
                </div>
            @endif
        </div>

        {{-- Widgets / Quick Actions --}}
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
           <x-quick-action href="{{ route('guest.reservation') }}">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-door-open-icon lucide-door-open"><path d="M13 4h3a2 2 0 0 1 2 2v14"/><path d="M2 20h3"/><path d="M13 20h9"/><path d="M10 12v.01"/><path d="M13 4.562v16.157a1 1 0 0 1-1.242.97L5 20V5.562a2 2 0 0 1 1.515-1.94l4-1A2 2 0 0 1 13 4.561Z"/></svg>
                    
                    <div>
                        <h3 class="font-semibold">Create a Reservation</h3>
                        <p class="text-xs">Book a room here</p>
                    </div>
                </div>
           </x-quick-action>

           <x-quick-action href="{{ route('guest.reservation') }}">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook-text-icon lucide-notebook-text"><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9.5 8h5"/><path d="M9.5 12H16"/><path d="M9.5 16H14"/></svg>
                    
                    <div>
                        <h3 class="font-semibold">Reservation History</h3>
                        <p class="text-xs">View your past reservations</p>
                    </div>
                </div>
           </x-quick-action>

           <x-quick-action href="{{ route('guest.reservation') }}">
                <div class="flex items-center gap-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-text-icon lucide-receipt-text"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/></svg>
                    
                    <div>
                        <h3 class="font-semibold">Check Bills</h3>
                        <p class="text-xs">View your bills here</p>
                    </div>
                </div>
           </x-quick-action>
        </div>
    </div>
</x-app-layout>
