<div class="max-w-screen-lg mx-auto space-y-5">
    <livewire:app.amenity.amenity-cards />

    <div class="p-5 space-y-5 bg-white border rounded-lg border-slate-200">
        <hgroup>
            <h2 class="font-semibold">Amenities</h2>
            <p class="text-xs">View your amenities here</p>
        </hgroup>

        @if ($amenity_count > 0)
            <div class="space-y-5">
                <livewire:tables.amenity-table />

                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <x-icon>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb-icon lucide-lightbulb"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                        </x-icon>
                        
                        <hgroup>
                            <h2 class='font-semibold'>Legends</h2>
                            <p class='text-xs'>Light indicators meaning</p>
                        </hgroup>
                    </div>

                    @php
                        $labels = [
                            'Stocked' => [
                                'ping' => 'bg-green-400',
                                'color' => 'border-green-500',
                            ],
                            'Low stock' => [
                                'ping' => 'bg-amber-400',
                                'color' => 'border-amber-500',
                            ],
                            'Very low stock' => [
                                'ping' => 'bg-red-400',
                                'color' => 'border-red-500',
                            ],
                        ]
                    @endphp

                    <div>
                        @foreach ($labels as $label => $style)
                            <div class="flex items-center gap-3 font-semibold">
                                <span class="relative flex size-2">
                                    <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping {{ $style['ping'] }}"></span>
                                    <span class="relative inline-flex border rounded-full size-2 bg-green-50 {{ $style['color'] }}"></span>
                                </span>

                                <p class="text-sm font-normal">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>    
        @else
            <div class="font-semibold text-center border rounded-md border-slate-200s">
                <x-table-no-data.amenity />
            </div>
        @endif
        
    </div>
</div>