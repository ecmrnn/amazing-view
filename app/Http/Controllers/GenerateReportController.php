<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateReportController extends Controller
{
    public static $margin = [
        'top' => 112,
        'bottom' => 112,
        'right' => 48,
        'left' => 48,
    ];
    public static $headerView = 'report.pdf.header';
    public static $footerView = 'report.pdf.footer';
    public static $path = 'storage/pdf/report/';

    public static function generate(
            Report $report,
            $room = null,
            $type,
            $format,
            $name,
            $start_date,
            $end_date = null,
            $size = 'letter',
        ) {
        switch ($type) {
            case 'reservation summary':
                self::generateReservationSummmary($report, $format, $name, $start_date, $end_date, $size);
                break;
            case 'daily reservations':
                self::generateDailyReservations($report, $format, $name, $start_date, $size);
                break;
            case 'occupancy report':
                $room = RoomType::find($room);
                self::generateOccupancyReport($report, $room, $format, $name, $start_date, $end_date, $size);
                break;
            case 'revenue performance':
                self::generateRevenuePerformance($report,$format, $name, $start_date, $end_date, $size);
                break;
            default:
                # code...
                break;
        }    
    }

    public static function generateReservationSummmary(Report $report, $format, $name, $start_date, $end_date, $size) {
        $reservations = Reservation::whereBetween('date_in', [$start_date, $end_date])
            ->whereStatus(Reservation::STATUS_COMPLETED)
            ->get();

        if ($format == 'pdf') {
            Pdf::view('report.pdf.reservation_summary', [
                'reservations' => $reservations,
                'report' => $report,
            ])
            ->format($size)
            ->margins(
                self::$margin['top'],
                self::$margin['right'],
                self::$margin['bottom'],
                self::$margin['left'],
                Unit::Pixel)
            ->headerView(self::$headerView, [
                'report' => $report
            ])
            ->footerView(self::$footerView, [
                'report' => $report
            ])
            ->save(self::$path . $name . ' - ' . $report->rid . '.' . $format);
        }
    }

    public static function generateDailyReservations(Report $report, $format, $name, $start_date, $size) {
        $reservations = Reservation::whereDate('date_in', $start_date)
            ->whereStatus(Reservation::STATUS_CONFIRMED)
            ->get();
        $guest_count = Reservation::selectRaw('sum(adult_count) as total_adults, sum(children_count) as total_children')
            ->whereDate('date_in', $start_date)
            ->whereStatus(Reservation::STATUS_CONFIRMED)
            ->first();
        
        if ($format == 'pdf') {
            Pdf::view('report.pdf.daily_reservations', [
                'reservations' => $reservations,
                'report' => $report,
                'guest_count' => $guest_count,
            ])
            ->format($size)
            ->margins(
                self::$margin['top'],
                self::$margin['right'],
                self::$margin['bottom'],
                self::$margin['left'],
                Unit::Pixel)
            ->headerView(self::$headerView, [
                'report' => $report
            ])
            ->footerView(self::$footerView, [
                'report' => $report
            ])
            ->save(self::$path . $name . ' - ' . $report->rid . '.' . $format);
        }
    }

    public static function generateOccupancyReport(Report $report, RoomType $room, $format, $name, $start_date, $end_date, $size) {
        $reservations = Reservation::whereBetween('date_in', [$start_date, $end_date])
            ->whereHas('rooms', function ($query) use ($room) {
                $query->where('room_type_id', $room->id);
            })
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereStatus(Invoice::STATUS_PAID)
            ->sum('total_amount');
        $days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date)) + 1;
        $room_count = count($room->rooms);
        $total_room_nights_available = $days * $room_count;
        $total_room_nights_occupied = Reservation::whereBetween('date_in', [$start_date, $end_date])
            ->orWhereBetween('date_out', [$start_date, $end_date])
            ->get()
            ->reduce(function ($carry, $reservation) use ($start_date, $end_date) {
                $check_in = Carbon::parse($reservation->date_in)->max($start_date);
                $check_out = Carbon::parse($reservation->date_out)->min($end_date);
                $nights_occupied = $check_in->diffInDays($check_out);

                return $carry + $nights_occupied;
            }, 0);
            
        if ($format == 'pdf') {
            Pdf::view('report.pdf.occupancy_report', [
                'report' => $report,
                'reservations' => $reservations,
                'room_type' => $room,
                'revenue' => $revenue,
                'occupancy_rate' => abs($total_room_nights_occupied / $total_room_nights_available * 100)
            ])
            ->format($size)
            ->margins(
                self::$margin['top'],
                self::$margin['right'],
                self::$margin['bottom'],
                self::$margin['left'],
                Unit::Pixel)
            ->headerView(self::$headerView, [
                'report' => $report
            ])
            ->footerView(self::$footerView, [
                'report' => $report
            ])
            ->save(self::$path . $name . ' - ' . $report->rid . '.' . $format);
        }
    }

    public static function generateRevenuePerformance(Report $report, $format, $name, $start_date, $end_date, $size) {
        $reservations = Reservation::whereBetween('date_in', [$start_date, $end_date])
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereStatus(Invoice::STATUS_PAID)
            ->sum('total_amount');
            
        if ($format == 'pdf') {
            Pdf::view('report.pdf.revenue_performance', [
                'report' => $report,
                'reservations' => $reservations,
                'revenue' => $revenue,
            ])
            ->format($size)
            ->margins(
                self::$margin['top'],
                self::$margin['right'],
                self::$margin['bottom'],
                self::$margin['left'],
                Unit::Pixel)
            ->headerView(self::$headerView, [
                'report' => $report
            ])
            ->footerView(self::$footerView, [
                'report' => $report
            ])
            ->save(self::$path . $name . ' - ' . $report->rid . '.' . $format);
        }
    }
}
