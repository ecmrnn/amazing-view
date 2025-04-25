<div class="flex flex-col gap-5 md:flex-row">
    <aside class="space-y-5 shrink-0 min-w-52">
        <p class="text-xs font-semibold">Filter Reservation Status</p>
    
        <ul>
            <li>
                <x-side-nav-link :status="null" href="{{ route('app.guests.index') }}">
                    <div class="flex items-center gap-1">
                        <span>All Reservations</span>
                        @if ($reservation_by_status['all'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['all'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rectangle-ellipsis"><rect width="20" height="12" x="2" y="6" rx="2"/><path d="M12 12h.01"/><path d="M17 12h.01"/><path d="M7 12h.01"/></svg>
                <span>In Progress</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::AWAITING_PAYMENT->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::AWAITING_PAYMENT->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Awaiting Payment</span>
                        @if ($reservation_by_status['awaiting_payment'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['awaiting_payment'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::PENDING->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::PENDING->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Pending</span>
                        @if ($reservation_by_status['pending'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['pending'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::CONFIRMED->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::CONFIRMED->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Confirmed</span>
                        @if ($reservation_by_status['confirmed'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['confirmed'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::CHECKED_IN->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::CHECKED_IN->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Checked-in</span>
                        @if ($reservation_by_status['checked-in'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['checked-in'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                <span>Finalized</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::CHECKED_OUT->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::CHECKED_OUT->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Checked-out</span>
                        @if ($reservation_by_status['checked-out'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['checked-out'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::RESCHEDULED->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::RESCHEDULED->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Rescheduled</span>
                        @if ($reservation_by_status['rescheduled'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['rescheduled'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span>Problematic</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::CANCELED->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::CANCELED->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Canceled</span>
                        @if ($reservation_by_status['canceled'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['canceled'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::EXPIRED->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::EXPIRED->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>Expired</span>
                        @if ($reservation_by_status['expired'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['expired'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Enums\ReservationStatus::NO_SHOW->value" href="{{ route('app.guests.index', ['status' => \App\Enums\ReservationStatus::NO_SHOW->value]) }}">
                    <div class="flex items-center gap-1">
                        <span>No-Show</span>
                        @if ($reservation_by_status['no-show'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['no-show'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
        </ul>
    </aside>

    <div class="self-start w-full p-5 overflow-x-hidden bg-white border rounded-lg border-slate-200">
        {{-- Guest Table --}}
        @if (!empty($reservation_count))
            <div class="space-y-5">
                <livewire:tables.guest-table />

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
                            'For check-in' => [
                                'ping' => 'bg-green-400',
                                'color' => 'border-green-500',
                            ],
                            'For check-out' => [
                                'ping' => 'bg-amber-400',
                                'color' => 'border-amber-500',
                            ],
                            'Late check-out' => [
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
            <div class="font-semibold text-center rounded-md">
                <x-table-no-data.guest />
            </div>
        @endif
    </div>
</div>