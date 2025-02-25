<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-xl font-bold text-blue-500">Revenue Performance Report</h1>
    <p class="text-sm">From: {{ date_format(date_create($report->start_date), 'F j, Y') }} To: {{ date_format(date_create($report->end_date), 'F j, Y') }}</p>

    <div class="mt-5 space-y-5">
        {{-- Summary --}}
        <h2 class="font-semibold text-md">Summary</h2>
    
        <ul class="list-disc list-inside">
            <li>Total revenue generated: <x-currency />{{ number_format($revenue, 2) }}</li>
            <li>Number of room types: {{ $room_type_count }}</li>
        </ul>
        
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-xs text-center text-blue-500 uppercase border-l border-slate-200 bg-blue-50 border-y">Room Type</th>
                    <th class="px-2 py-3 text-xs text-center text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Total Reservations</th>
                    <th class="px-2 py-3 text-xs text-center text-blue-500 uppercase border-slate-200 bg-blue-50 border-y">Total Revenue</th>
                    <th class="px-2 py-3 text-xs text-center text-blue-500 uppercase border-r border-slate-200 bg-blue-50 border-y">Ave. Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revenue_per_room_type as $reservation)
                    <tr class="border-b border-slate-200 last:border-b-0 odd:bg-slate-100">
                        <td class="px-4 py-3 text-xs font-semibold text-center border-x border-slate-200">{{ $reservation->room_type }}</td>
                        <td class="px-2 py-3 text-xs text-center border-r border-slate-200">{{ $reservation->reservation_count }}</td>
                        <td class="px-2 py-3 text-xs text-center border-r border-slate-200"><x-currency />{{ number_format($reservation->total_revenue, 2) }}</td>
                        <td class="px-2 py-3 text-xs text-center border-r border-slate-200"><x-currency />{{ number_format($reservation->average_revenue, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="border-b border-slate-200 odd:bg-slate-100">
                    <td class="px-4 py-3 text-xs font-semibold text-center border-x border-slate-200">Grand Total</td>
                    <td class="px-2 py-3 text-xs font-semibold text-center border-r border-slate-200">{{ $grand_total['reservation_count'] }}</td>
                    <td class="px-2 py-3 text-xs font-semibold text-center border-r border-slate-200"><x-currency />{{ number_format($grand_total['total_revenue'], 2) }}</td>
                    <td class="px-2 py-3 text-xs font-semibold text-center border-r border-slate-200"><x-currency />{{ number_format($grand_total['average_revenue'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-pdf-layout>