<?php

namespace App\Exports;

use App\Enums\ReservationStatus;
use App\Models\Report;
use App\Models\Reservation;
use Vitorccs\LaravelCsv\Concerns\Exportables\Exportable;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;

class IncomingReservationExports implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(public Report $report)
    {
        // 
    }

    public function headings(): array
    {
        return [
            'Reservation ID',
            'Date in',
            'Date out',
            'First Name',
            'Last Name',
            'Senior Count',
            'PWD Count',
            'Adult Count',
            'Children Count', 
            'Phone',
            'Address',
            'Email',
        ];
    }

    
    public function query()
    {
        return Reservation::selectRaw('rid, date_in, date_out, first_name, last_name, senior_count, pwd_count, adult_count, children_count, phone, address, email')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->whereDate('date_in', $this->report->start_date)
            ->where('reservations.status', ReservationStatus::CONFIRMED->value);
    }
}