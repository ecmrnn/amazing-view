<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-lg font-bold text-blue-500">Reservation Summary</h1>
    <p class="text-sm">From: {{ date_format(date_create($report->start_date), 'F j, Y') }} To: {{ date_format(date_create($report->end_date), 'F j, Y') }}</p>

    <div class="p-5 mt-5 space-y-5 border rounded-lg bg-slate-50 border-slate-200">
        <p><span class="font-semibold">Total Reservations</span>: {{ number_format($reservations->count()) }}</p>
    </div>

    @if ($reservations->count() > 0)
        <div class="mt-5">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-1 text-xs text-left uppercase border-l border-slate-200 bg-blue-50 border-y">Reservation ID</th>
                        <th class="px-2 py-1 text-xs text-left uppercase border-slate-200 bg-blue-50 border-y">Name</th>
                        <th class="px-2 py-1 text-xs text-left uppercase border-slate-200 bg-blue-50 border-y">Email</th>
                        <th class="px-2 py-1 text-xs text-left uppercase border-slate-200 bg-blue-50 border-y">Check-in</th>
                        <th class="px-2 py-1 text-xs text-left uppercase border-slate-200 bg-blue-50 border-y">Check-out</th>
                        <th class="px-2 py-1 text-xs uppercase border-r border-slate-200 bg-blue-50 border-y">Rooms Reserved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="border-b border-slate-200 last:border-b-0 odd:bg-slate-50">
                            <td class="px-4 py-1 text-xs font-semibold border-x border-slate-200">{{ $reservation->rid }}</td>
                            <td class="px-2 py-1 text-xs capitalize border-r border-slate-200">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ $reservation->user->email }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_in), 'F j, Y') }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_our), 'F j, Y') }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">
                                @foreach ($reservation->rooms as $room)
                                    <span class="inline-block">
                                        {{ $room->room_number }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    <td class="px-2 py-3 text-xs text-center" colspan="6">Nothing follows...</td>
                </tbody>
            </table>
        </div>
    @else
        <div class="p-10 mt-5 border rounded-lg border-slate-200">
            <p class="text-6xl font-bold text-center opacity-15">&#59;&#40;</p>
            <p class="mt-10 text-lg font-bold text-center">{{ date_format(date_create($report->start_date), 'F j, Y') }}</p>
            <p class="text-sm text-center">No reservations today</p>
        </div>
    @endif
</x-pdf-layout>