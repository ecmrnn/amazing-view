<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-xl font-bold text-blue-500">Daily Reservations</h1>
    <p class="text-sm">Report ID: {{ $report->rid }}</p>
    

    @if ($reservations->count() > 0)
        <div class="mt-5 overflow-hidden border rounded-lg border-slate-200">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">Reservation ID</th>
                        <th class="px-2 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">First Name</th>
                        <th class="px-2 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">Last Name</th>
                        <th class="px-2 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">Date in</th>
                        <th class="px-2 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">Date out</th>
                        <th class="px-2 py-3 text-xs text-left text-white uppercase bg-blue-500 border-b border-slate-200">Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="border-b border-slate-200 last:border-b-0 odd:bg-slate-100">
                            <td class="px-4 py-3 text-xs font-semibold border-r border-slate-200">{{ $reservation->rid }}</td>
                            <td class="px-2 py-3 text-xs capitalize border-r border-slate-200">{{ $reservation->first_name }}</td>
                            <td class="px-2 py-3 text-xs capitalize border-r border-slate-200">{{ $reservation->last_name }}</td>
                            <td class="px-2 py-3 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_in), 'F j, Y') }}</td>
                            <td class="px-2 py-3 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_our), 'F j, Y') }}</td>
                            <td class="px-2 py-3 text-xs">{{ substr($reservation->phone, 0, 4) . ' ' . substr($reservation->phone, 4, 3) . ' ' . substr($reservation->phone, 7) }}</td>
                        </tr>
                    @endforeach
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