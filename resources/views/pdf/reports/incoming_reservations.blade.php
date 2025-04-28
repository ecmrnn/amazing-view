<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-xl font-bold text-blue-500">Incoming Reservations</h1>
    <p class="text-sm">Date: {{ date_format(date_create($report->start_date), 'F j, Y') }}</p>
    
    @if (count($reservations) > 0)
        <div class="p-5 mt-5 border rounded-lg border-slate-200 bg-slate-50">
            <div>
                <p><span class="font-semibold">Total Reservations</span>: {{ $reservations->count() }}</p>
                <p><span class="font-semibold">Total Guests</span>: {{ $guest_count->total_adults }} Adults, {{ $guest_count->total_children }} Children</p>
            </div>
        </div>

        <div class="mt-5">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-1 text-xs text-left text-blue-500 uppercase border-l border-slate-200 bg-blue-50 border-y">Reservation ID</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Name</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Check-in</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Check-out</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Phone Number</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-r border-slate-200 bg-blue-50 border-y">Rooms Reserved</th>
                        <th class="px-2 py-1 text-xs text-left text-blue-500 uppercase border-r border-slate-200 bg-blue-50 border-y">Vehicles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="border-b border-slate-200 last:border-b-0 odd:bg-slate-100">
                            <td class="px-4 py-1 text-xs font-semibold border-x border-slate-200">{{ $reservation->rid }}</td>
                            <td class="px-2 py-1 text-xs capitalize border-r border-slate-200">{{ $reservation->user->first_name . ' ' . $reservation->user->last_name }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_in), 'F j, Y') . ' - ' . date_format(date_create($reservation->time_in),'g:i A') }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_out), 'F j, Y') . ' - ' . date_format(date_create($reservation->time_out),'g:i A') }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">{{ substr($reservation->user->phone, 0, 4) . ' ' . substr($reservation->user->phone, 4, 3) . ' ' . substr($reservation->user->phone, 7) }}</td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">
                                @foreach ($reservation->rooms as $room)
                                    <span class="inline-block">
                                        {{ $room->room_number }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-2 py-1 text-xs border-r border-slate-200">
                                @foreach ($reservation->cars as $car)
                                    <span class="inline-block">
                                        {{ $car->plate_number }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    <td class="px-2 py-1 pt-5 text-xs text-center" colspan="6">Nothing follows...</td>
                </tbody>
            </table>
        </div>
    @else
        <div class="p-10 mt-5 border rounded-lg border-slate-200">
            <p class="text-6xl font-bold text-center opacity-15">&#59;&#40;</p>
            <p class="mt-10 text-lg font-bold text-center">{{ date_format(date_create($report->start_date), 'F j, Y') }}</p>
            <p class="text-sm text-center">No reservations for this day</p>
        </div>
    @endif
</x-pdf-layout>