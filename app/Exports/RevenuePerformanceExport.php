<?php

namespace App\Exports;

use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\Report;
use App\Models\RoomReservation;
use Vitorccs\LaravelCsv\Concerns\Exportables\Exportable;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;

class RevenuePerformanceExport implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * Create a new class instance.
     */
    public function __construct(public Report $report)
    {
        //
    }

    public function headings(): array {
        return [
            'Room Type',
            'Reservation Count',
            'Total Revenue',
            'Average Revenue',
        ];
    }

    public function query() {
        return Invoice::
        selectRaw('
            room_types.name as room_type,
            count(room_reservations.id) as reservation_count,
            sum(invoices.total_amount) as total_revenue,
            avg(invoices.total_amount) as average_revenue
        ')
        ->whereBetween('reservations.date_in', [$this->report->start_date, $this->report->end_date])
        ->where('room_reservations.status', ReservationStatus::CHECKED_OUT)
        ->join('room_reservations', 'invoices.reservation_id', '=', 'room_reservations.reservation_id')
        ->join('rooms', 'rooms.id', '=', 'room_reservations.room_id')
        ->join('reservations', 'reservations.id', '=', 'invoices.reservation_id')
        ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        ->groupBy('room_types.name');
    }
}
