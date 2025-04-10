<?php

namespace App\Jobs;

use App\Enums\InvoiceStatus;
use App\Enums\ReportType;
use App\Enums\ReservationStatus;
use App\Events\ReportGenerated;
use App\Exports\IncomingReservationExports;
use App\Exports\ReservationSummaryExports;
use App\Exports\RevenuePerformanceExport;
use App\Models\Invoice;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateReport implements ShouldQueue
{
    use Queueable;

    public $margin = [
        'top' => 112,
        'bottom' => 112,
        'right' => 48,
        'left' => 48,
    ];
    public $headerView = 'pdf.reports.header';
    public $footerView = 'pdf.reports.footer';
    public $path = '';
    public $filename = '';

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Report $report,
        public $size =  'letter',
    )
    {
        $this->filename = $report->name . ' - ' . $report->rid . '.' . $report->format;
        $this->path = 'storage/app/public/' . $report->format . '/report/' . $this->filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->report->type) {
            case ReportType::RESERVATION_SUMMARY->value:
                $this->generateReservationSummmary($this->report, $this->size);
                break;
            case ReportType::INCOMING_RESERVATIONS->value:
                $this->generateIncomingReservations($this->report, $this->size);
                break;
            case ReportType::OCCUPANCY_REPORT->value:
                $this->generateOccupancyReport($this->report, $this->size);
                break;
            case ReportType::REVENUE_PERFORMANCE->value:
                $this->generateRevenuePerformance($this->report, $this->size);
                break;
        }
        broadcast(new ReportGenerated($this->report, $this->report->user_id));
    }

    public function generateReservationSummmary(Report $report, $size) {
        $reservations = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->get();

        if ($report->format == 'pdf') {
            Pdf::view('pdf.reports.reservation_summary', [
                'reservations' => $reservations,
                'report' => $report,
            ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                // prevents puppeteer errors with navigation
                $browsershot->noSandbox();
            })
            ->format($size)
            ->orientation(Orientation::Landscape)
            ->margins(
                $this->margin['top'],
                $this->margin['right'],
                $this->margin['bottom'],
                $this->margin['left'],
                Unit::Pixel)
            ->headerView($this->headerView, [
                'report' => $report
            ])
            ->footerView($this->footerView, [
                'report' => $report
            ])
            ->save($this->path);
        } else {
            return (new ReservationSummaryExports($this->report))->store('public/csv/report/' . $this->filename);
        }
    }

    public function generateIncomingReservations(Report $report, $size) {
        $reservations = Reservation::whereDate('date_in', $report->start_date)
            ->whereStatus(ReservationStatus::CONFIRMED->value)
            ->get();
        $guest_count = Reservation::selectRaw('sum(adult_count) as total_adults, sum(children_count) as total_children')
            ->whereDate('date_in', $report->start_date)
            ->whereStatus(ReservationStatus::CONFIRMED->value)
            ->first();
        
        if ($report->format == 'pdf') {
            Pdf::view('pdf.reports.incoming_reservations', [
                'reservations' => $reservations,
                'report' => $report,
                'guest_count' => $guest_count,
            ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                // prevents puppeteer errors with navigation
                $browsershot->noSandbox();
            })
            ->format($size)
            ->orientation(Orientation::Landscape)
            ->margins(
                $this->margin['top'],
                $this->margin['right'],
                $this->margin['bottom'],
                $this->margin['left'],
                Unit::Pixel)
            ->headerView($this->headerView, [
                'report' => $report
            ])
            ->footerView($this->footerView, [
                'report' => $report
            ])
            ->save($this->path);
        } else {
            return (new IncomingReservationExports($this->report))->store('public/csv/report/' . $this->filename);
        }
    }

    public function generateOccupancyReport(Report $report, $size) {
        $reservations = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->whereHas('rooms', function ($query) use ($report) {
                $query->where('room_type_id', $report->room_type_id);
            })
            ->whereIn('status', [ReservationStatus::CHECKED_IN, ReservationStatus::CHECKED_OUT])
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereIn('status', [InvoiceStatus::PAID, InvoiceStatus::ISSUED])
            ->sum('total_amount');
        $total_room_nights_occupied = Reservation::
            where(function ($query) use ($report) {
                $query->whereBetween('date_in', [$report->start_date, $report->end_date])
                    ->orWhereBetween('date_out', [$report->start_date, $report->end_date]);
            })
            ->whereIn('id', $reservations->pluck('id')->toArray())
            ->get()
            ->reduce(function ($carry, $reservation) use ($report) {
                $check_in = Carbon::parse($reservation->date_in)->max($report->start_date);
                $check_out = Carbon::parse($reservation->date_out)->min($report->end_date);
                $nights_occupied = $check_in->diffInDays($check_out);                
                $nights_occupied = $nights_occupied == 0 ? 1 : $nights_occupied;

                return $carry + $nights_occupied;
            }, 0);
        $days = Carbon::parse($report->start_date)->diffInDays(Carbon::parse($report->end_date)) + 1;
        $room_count = count($report->roomType->rooms);
        $total_room_nights_available = $days * $room_count;
            
        if ($report->format == 'pdf') {
            Pdf::view('pdf.reports.occupancy_report', [
                'report' => $report,
                'reservations' => $reservations,
                'room_type' => $report->roomType,
                'revenue' => $revenue,
                'occupancy_rate' => abs($total_room_nights_occupied / $total_room_nights_available * 100)
            ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                // prevents puppeteer errors with navigation
                $browsershot->noSandbox();
            })
            ->format($size)
            ->margins(
                $this->margin['top'],
                $this->margin['right'],
                $this->margin['bottom'],
                $this->margin['left'],
                Unit::Pixel)
            ->headerView($this->headerView, [
                'report' => $report
            ])
            ->footerView($this->footerView, [
                'report' => $report
            ])
            ->save($this->path);
        }
    }

    public function generateRevenuePerformance(Report $report, $size) {
        $reservations = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->whereStatus(ReservationStatus::CHECKED_OUT)
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereIn('status', [InvoiceStatus::PARTIAL, InvoiceStatus::PAID, InvoiceStatus::ISSUED])
            ->sum('total_amount');
        $revenue_per_room_type = Invoice::whereIn('invoices.reservation_id', $reservations->pluck('id')->toArray())
            ->selectRaw('
                room_types.name as room_type,
                count(room_reservations.id) as reservation_count,
                sum(invoices.total_amount) as total_revenue,
                avg(invoices.total_amount) as average_revenue
            ')
            ->join('room_reservations', 'invoices.reservation_id', '=', 'room_reservations.reservation_id')
            ->join('rooms', 'rooms.id', '=', 'room_reservations.room_id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->groupBy('room_types.name')
            ->get();
        $room_type_count = RoomType::count();   
        $grand_total = [
            'reservation_count' => 0,
            'total_revenue' => 0,
            'average_revenue' => 0,
        ];

        foreach ($revenue_per_room_type as $reservation) {
            $grand_total['reservation_count'] += $reservation->reservation_count;
            $grand_total['total_revenue'] += $reservation->total_revenue;
            $grand_total['average_revenue'] += $reservation->average_revenue;
        }
            
        if ($report->format == 'pdf') {
            Pdf::view('pdf.reports.revenue_performance', [
                'report' => $report,
                'reservations' => $reservations,
                'revenue' => $revenue,
                'room_type_count' => $room_type_count,
                'revenue_per_room_type' => $revenue_per_room_type,
                'grand_total' => $grand_total,
            ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                // prevents puppeteer errors with navigation
                $browsershot->noSandbox();
            })
            ->format($size)
            ->margins(
                $this->margin['top'],
                $this->margin['right'],
                $this->margin['bottom'],
                $this->margin['left'],
                Unit::Pixel)
            ->headerView($this->headerView, [
                'report' => $report
            ])
            ->footerView($this->footerView, [
                'report' => $report
            ])
            ->save($this->path);
        } else {
            return (new RevenuePerformanceExport($this->report))->store('public/csv/report/' . $this->filename);
        }
    }
}
