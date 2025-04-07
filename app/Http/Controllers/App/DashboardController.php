<?php

namespace App\Http\Controllers\App;

use App\Enums\AnnouncementStatus;
use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Enums\ReservationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Livewire\tables\ReservationTable;
use App\Models\Announcement;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index() {
        $data = [];
        $view = '';

        $status_labels = [
            RoomStatus::AVAILABLE->value => 'Available',
            RoomStatus::UNAVAILABLE->value => 'Unavailable',
            RoomStatus::OCCUPIED->value=> 'Occupied',
            RoomStatus::RESERVED->value => 'Reserved',
            RoomStatus::DISABLED->value => 'Disabled',
        ];

        $status_colors = [
            RoomStatus::AVAILABLE->value => '#2563EB', /* Blue */
            RoomStatus::UNAVAILABLE->value => '#EF4444', /* Red */
            RoomStatus::OCCUPIED->value => '#F59E0B', /* Amber */
            RoomStatus::RESERVED->value => '#22C55E', /* Green */
            RoomStatus::DISABLED->value => '#a6a09b', /* Stone */
        ];

        // Dashboard content for frontdesks
        $user = Auth::user();

        if ($user->hasRole('receptionist')) {
            $guest_in = Reservation::where('status', ReservationStatus::CHECKED_IN)->count();
            $available_rooms = Room::where('status', RoomStatus::AVAILABLE)
                ->count();
            $pending_reservations = Reservation::where('status', ReservationStatus::PENDING)->count();
            $due_invoices = Invoice::where('status', InvoiceStatus::DUE)->count();
            $area_chart = (new areaChartModel())
                ->setColor('#2563EB')
                ->addPoint('Jan', 10)
                ->addPoint('Feb', 20)
                ->addPoint('Mar', 15)
                ->addPoint('Apr', 25)
                ->addPoint('May', 30)
                ->addPoint('Jun', 28)
                ->addPoint('Jul', 20)
                ->addPoint('Aug', 25)
                ->addPoint('Sep', 28)
                ->addPoint('Oct', 20)
                ->addPoint('Nov', 15)
                ->addPoint('Dec', 10);

            $room_statuses = Room::select('status', Room::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $column_chart = (new ColumnChartModel())
                ->withoutLegend();

            foreach ($room_statuses as $room) {
                $label = $status_labels[$room->status]; 
                $color = $status_colors[$room->status];

                $column_chart->addColumn($label, $room->count, $color);
            }

            $data = [
                'area_chart' => $area_chart,
                'column_chart' => $column_chart,
                'guest_in' => $guest_in,
                'available_rooms' => $available_rooms,
                'pending_reservations' => $pending_reservations,
                'due_invoices' => $due_invoices,
            ];

            $view = 'app.dashboard.receptionist';
        } elseif ($user->hasRole('admin')) {
            $view = 'app.dashboard.admin';            
            $months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
            $reservation_count = Reservation::whereStatus(ReservationStatus::PENDING)->count();

            // Monthly Revenue
            $monthly_revenue = InvoicePayment::select(DB::raw('sum(amount) as revenue'))
                ->whereMonth('payment_date', Carbon::now()->format('m'))
                ->whereIn('invoices.status', [InvoiceStatus::PARTIAL, InvoiceStatus::DUE])
                ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->first();
            
            // Outstanding Balances
            $outstanding_balance = Invoice::select(DB::raw('sum(balance) as balance'))
                ->whereIn('status', [InvoiceStatus::PARTIAL, InvoiceStatus::DUE])
                ->where('balance', '>', 0)
                ->first();

            $monthly_reservations = Reservation::select(DB::raw('count(*) as reservation_count'))
                ->whereMonth('date_in', Carbon::now()->format('m'))
                ->first();

            // Monthly Reservations (Chart)
            $monthly_reservations_chart = Reservation::select(DB::raw('MONTH(date_in) as month, count(*) as reservation_count'))
                ->groupByRaw('MONTH(date_in)')
                ->get();

            // Monthly New Guests
            $monthly_new_guests = User::select(DB::raw('count(email) as new_guests'))
                ->whereMonth('created_at', Carbon::now()->format('m'))
                ->whereRole(UserRole::GUEST)
                ->distinct()
                ->first();

            $area_chart_reservation = (new areaChartModel())
                ->setColor('#2563EB');
            foreach ($monthly_reservations_chart as $reservation) {
                $area_chart_reservation->addPoint($months[$reservation->month - 1], $reservation->reservation_count);
            };

            // Monthly Sales
            $monthly_sales = InvoicePayment::select(DB::raw('MONTH(payment_date) as month, sum(amount) as total_sales'))
                ->groupByRaw('MONTH(payment_date)')
                ->get();

            $area_chart_sales = (new areaChartModel())
                ->setColor('#2563EB');
            foreach ($monthly_sales as $sale) {
                $area_chart_sales->addPoint($months[$sale->month - 1], $sale->total_sales);
            };

            $data = [
                'area_chart_reservation' => $area_chart_reservation,
                'area_chart_sales' => $area_chart_sales,
                'monthly_revenue' => $monthly_revenue,
                'outstanding_balance' => $outstanding_balance,
                'monthly_new_guests' => $monthly_new_guests,
                'monthly_reservations' => $monthly_reservations,
                'reservation_count' => $reservation_count,
            ];
        } else {
            $announcement = Announcement::whereStatus(AnnouncementStatus::ACTIVE)->first();
            $quote = Inspiring::quote();

            $data = [
                'announcement' => $announcement,
                'quote' => $quote,
            ];
            
            $view = 'app.dashboard.guest';            
        }

        return view($view, $data);
    }
}
