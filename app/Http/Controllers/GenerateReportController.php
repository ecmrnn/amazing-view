<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;

class GenerateReportController extends Controller
{
    public static $margin = [
        'top' => 112,
        'bottom' => 112,
        'right' => 48,
        'left' => 48,
    ];

    public static function generate(
            Report $report,
            $type,
            $format,
            $name,
            $start_date,
            $end_date = null,
            $size = 'letter',
            $room_type = null,
        ) {
        switch ($type) {
            case 'reservation summary':
                self::generateReservationSummmary($report, $format, $name, $start_date, $end_date, $size);
                break;
            case 'daily reservations':
                self::generateDailyReservations($report, $format, $name, $start_date, $size);
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
        $path = 'storage/report/pdf/' . $name . ' - ' . $report->rid . '.' . $format;

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
            ->headerView('report.pdf.header')
            ->footerView('report.pdf.footer', [
                'report' => $report
            ])
            ->save($path);
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
        
        $path = 'storage/report/pdf/' . $name . ' - ' . $report->rid . '.' . $format;

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
            ->headerView('report.pdf.header')
            ->footerView('report.pdf.footer', [
                'report' => $report
            ])
            ->save($path);
        }
    }
}
