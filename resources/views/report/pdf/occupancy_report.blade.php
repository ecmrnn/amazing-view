<x-pdf-layout>
    <x-slot:title>{{ $report->name . ' - ' . $report->rid }}</x-slot:title>

    <h1 class="text-xl font-bold text-blue-500">{{ $room_type->name }} Occupancy Report</h1>
    <p class="text-sm">Report ID: {{ $report->rid }}</p>

    <div class="mt-5 space-y-5">
        {{-- Summary --}}
        <div class="grid grid-cols-2">
            <div class="space-y-5">
                <h2 class="font-semibold text-md">Summary</h2>
            
                <ul class="list-disc list-inside">
                    <li>Total number of {{ $room_type->name }} rooms: {{ count($room_type->rooms) }}</li>
                    <li>Total reservations: {{ count($reservations) }}</li>
                    <li>Revenue generated: <x-currency /> {{ number_format($revenue, 2) }}</li>
                </ul>
            </div>

            <div class="p-5 space-y-5 border border-blue-200 rounded-lg bg-blue-50">
                <h2 class="font-semibold text-center text-md">Occupancy Percentage</h2>
                <p class="text-4xl font-bold text-center text-blue-500">{{ number_format($occupancy_rate, 2) . '%' }}</p>
            </div>
        </div>

        {{-- Occupancy Rate --}}
    </div>


</x-pdf-layout>