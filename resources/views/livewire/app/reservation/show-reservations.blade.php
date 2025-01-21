<div class="flex flex-col gap-5 md:flex-row">
    <aside class="space-y-5 shrink-0 min-w-52">
        <p class="text-xs font-semibold">Filter Reservation Status</p>

        <ul>
            <li>
                <x-side-nav-link :status="null" href="{{ route('app.reservations.index') }}">
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
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_AWAITING_PAYMENT" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_AWAITING_PAYMENT]) }}">
                    <div class="flex items-center gap-1">
                        <span>Awaiting Payment</span>
                        @if ($reservation_by_status['awaiting_payment'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['awaiting_payment'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_PENDING" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_PENDING]) }}">
                    <div class="flex items-center gap-1">
                        <span>Pending</span>
                        @if ($reservation_by_status['pending'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['pending'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_CONFIRMED" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_CONFIRMED]) }}">
                    <div class="flex items-center gap-1">
                        <span>Confirmed</span>
                        @if ($reservation_by_status['confirmed'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['confirmed'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_CHECKED_IN" href="{{ route('app.guests.index') }}">
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
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_CHECKED_OUT" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_CHECKED_OUT]) }}">
                    <div class="flex items-center gap-1">
                        <span>Checked-out</span>
                        @if ($reservation_by_status['checked-out'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['checked-out'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_COMPLETED" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_COMPLETED]) }}">
                    <div class="flex items-center gap-1">
                        <span>Completed</span>
                        @if ($reservation_by_status['completed'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['completed'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li class="flex items-center gap-2 py-3 text-xs font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span>Problematic</span>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_CANCELED" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_CANCELED]) }}">
                    <div class="flex items-center gap-1">
                        <span>Canceled</span>
                        @if ($reservation_by_status['canceled'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['canceled'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
            <li>
                <x-side-nav-link :status="\App\Models\Reservation::STATUS_EXPIRED" href="{{ route('app.reservations.index', ['status' => \App\Models\Reservation::STATUS_EXPIRED]) }}">
                    <div class="flex items-center gap-1">
                        <span>Expired</span>
                        @if ($reservation_by_status['expired'] > 0)
                            <div class="text-xs">( {{ $reservation_by_status['expired'] }} )</div>
                        @endif
                    </div>
                </x-side-nav-link>
            </li>
        </ul>
    </aside>

    {{-- Cards --}}
    <div class="w-full space-y-5">
        <livewire:app.cards.reservation-cards />
        {{-- Room  Table --}}
        <div class="p-5 bg-white border rounded-lg border-slate-200">
            <livewire:tables.reservation-table />
        </div>
    </div>
</div>