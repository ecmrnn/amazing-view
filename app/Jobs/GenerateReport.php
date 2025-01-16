<?php

namespace App\Jobs;

use App\Events\ReportGenerated;
use App\Exports\DailyReservationExports;
use App\Exports\ReservationSummaryExports;
use App\Models\Invoice;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
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
    public $headerView = 'report.pdf.header';
    public $footerView = 'report.pdf.footer';
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
            case 'reservation summary':
                $this->generateReservationSummmary($this->report, $this->size);
                break;
            case 'daily reservations':
                $this->generateDailyReservations($this->report, $this->size);
                break;
            case 'occupancy report':
                $this->generateOccupancyReport($this->report, $this->size);
                break;
            case 'revenue performance':
                $this->generateRevenuePerformance($this->report, $this->size);
                break;
        }
        broadcast(new ReportGenerated($this->report));
    }

    public function generateReservationSummmary(Report $report, $size) {
        $reservations = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->get();

        if ($report->format == 'pdf') {
            Pdf::view('report.pdf.reservation_summary', [
                'reservations' => $reservations,
                'report' => $report,
            ])
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
            return (new ReservationSummaryExports($this->report))->store('public/csv/report/' . $this->filename);
        }
    }

    public function generateDailyReservations(Report $report, $size) {
        $reservations = Reservation::whereDate('date_in', $report->start_date)
            ->whereStatus(Reservation::STATUS_CONFIRMED)
            ->get();
        $guest_count = Reservation::selectRaw('sum(adult_count) as total_adults, sum(children_count) as total_children')
            ->whereDate('date_in', $report->start_date)
            ->whereStatus(Reservation::STATUS_CONFIRMED)
            ->first();
        
        if ($report->format == 'pdf') {
            Pdf::view('report.pdf.daily_reservations', [
                'reservations' => $reservations,
                'report' => $report,
                'guest_count' => $guest_count,
            ])
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
            return (new DailyReservationExports($this->report))->store('public/csv/report/' . $this->filename);
        }
    }

    public function generateOccupancyReport(Report $report, $size) {
        $reservations = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->whereHas('rooms', function ($query) use ($report) {
                $query->where('room_type_id', $report->room_type_id);
            })
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereStatus(Invoice::STATUS_PAID)
            ->sum('total_amount');
        $total_room_nights_occupied = Reservation::whereBetween('date_in', [$report->start_date, $report->end_date])
            ->orWhereBetween('date_out', [$report->start_date, $report->end_date])
            ->get()
            ->reduce(function ($carry, $reservation) use ($report) {
                $check_in = Carbon::parse($reservation->date_in)->max($report->start_date);
                $check_out = Carbon::parse($reservation->date_out)->min($report->end_date);
                $nights_occupied = $check_in->diffInDays($check_out);
                
                return $carry + $nights_occupied;
            }, 0);
        $days = Carbon::parse($report->start_date)->diffInDays(Carbon::parse($report->end_date)) + 1;
        $room_count = count($report->roomType->rooms);
        $total_room_nights_available = $days * $room_count;
            
        if ($report->format == 'pdf') {
            Pdf::view('report.pdf.occupancy_report', [
                'report' => $report,
                'reservations' => $reservations,
                'room_type' => $report->roomType,
                'revenue' => $revenue,
                'occupancy_rate' => abs($total_room_nights_occupied / $total_room_nights_available * 100)
            ])
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
            ->get();
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereStatus(Invoice::STATUS_PAID)
            ->sum('total_amount');
        $revenue = Invoice::whereIn('reservation_id', $reservations->pluck('id'))
            ->whereStatus(Invoice::STATUS_PAID)
            ->sum('total_amount');
        $revenue_per_room_type = Invoice::whereIn('invoices.reservation_id', $reservations->pluck('id'))
            ->join('room_reservations', 'invoices.reservation_id', '=', 'room_reservations.reservation_id')
            ->join('rooms', 'rooms.id', '=', 'room_reservations.room_id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->selectRaw('
                room_types.name as room_type,
                count(room_reservations.id) as reservation_count,
                sum(invoices.total_amount) as total_revenue,
                avg(invoices.total_amount) as average_revenue
            ')
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
            Pdf::view('report.pdf.revenue_performance', [
                'report' => $report,
                'reservations' => $reservations,
                'revenue' => $revenue,
                'room_type_count' => $room_type_count,
                'revenue_per_room_type' => $revenue_per_room_type,
                'grand_total' => $grand_total,
            ])
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
}
