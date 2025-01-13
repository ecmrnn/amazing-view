<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-xl font-bold text-blue-500">Daily Reservations</h1>
    <p class="text-sm">Date: {{ date_format(date_create($report->start_date), 'F j, Y') }}</p>
    

    @if (count($reservations) > 0)
        <div class="mt-5">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-xs text-center text-blue-500 uppercase border-l border-slate-200 bg-blue-50 border-y">Reservation ID</th>
                        <th class="px-2 py-3 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">First Name</th>
                        <th class="px-2 py-3 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Last Name</th>
                        <th class="px-2 py-3 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Date in</th>
                        <th class="px-2 py-3 text-xs text-left text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Date out</th>
                        <th class="px-2 py-3 text-xs text-blue-500 uppercase border-r border-slate-200 bg-blue-50 border-y">Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="border-b border-slate-200 last:border-b-0 odd:bg-slate-100">
                            <td class="px-4 py-3 text-xs font-semibold text-center border-x border-slate-200">{{ $reservation->rid }}</td>
                            <td class="px-2 py-3 text-xs capitalize border-r border-slate-200">{{ $reservation->first_name }}</td>
                            <td class="px-2 py-3 text-xs capitalize border-r border-slate-200">{{ $reservation->last_name }}</td>
                            <td class="px-2 py-3 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_in), 'F j, Y') }}</td>
                            <td class="px-2 py-3 text-xs border-r border-slate-200">{{ date_format(date_create($reservation->date_our), 'F j, Y') }}</td>
                            <td class="px-2 py-3 text-xs border-r border-slate-200">{{ substr($reservation->phone, 0, 4) . ' ' . substr($reservation->phone, 4, 3) . ' ' . substr($reservation->phone, 7) }}</td>
                        </tr>
                    @endforeach
                    <td class="px-2 py-3 text-xs text-center" colspan="6">Nothing follows...</td>
                </tbody>
            </table>
        </div>

        <div class="mt-5 space-y-5">
            <h2 class="font-semibold text-md">Summary</h2>

            <ul class="list-disc list-inside">
                <li>Total Reservations: {{ count($reservations) }}</li>
                <li>Total Guests: </li>
                <ul class="ml-8 list-inside">
                    <li>Adult: {{ $guest_count->total_adults }}</li>
                    <li>Children: {{ $guest_count->total_children }}</li>
                </ul>
            </ul>
        </div>
    @else
        <div class="p-10 mt-5 border rounded-lg border-slate-200">
            <p class="text-6xl font-bold text-center opacity-15">&#59;&#40;</p>
            <p class="mt-10 text-lg font-bold text-center">{{ date_format(date_create($report->start_date), 'F j, Y') }}</p>
            <p class="text-sm text-center">No reservations for this day</p>
        </div>
    @endif
</x-pdf-layout>