<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index() {
        $data = [];
        $view = '';

        $status_labels = [
            0 => 'Available',
            1 => 'Unavailable',
            2 => 'Occupied',
            3 => 'Reserved'
        ];

        $status_colors = [
            0 => '#2563EB', /* Blue */
            1 => '#EF4444', /* Red */
            2 => '#F59E0B', /* Amber */
            3 => '#22C55E', /* Green */
        ];

        // Dashboard content for frontdesks
        if (Auth::user()->role == User::ROLE_FRONTDESK) {
            $available_rooms = Room::where('status', Room::STATUS_AVAILABLE)->count();
            $pending_reservations = Reservation::where('status', Reservation::STATUS_PENDING)->count();

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
                'available_rooms' => $available_rooms,
                'pending_reservations' => $pending_reservations,
            ];

            $view = 'app.dashboard.frontdesk';
        }

        return view($view, $data);
    }
}
