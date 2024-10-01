<?php

namespace App\Http\Controllers;

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
                ->addPoint('Sep', 30)
                ->addPoint('Oct', 20)
                ->addPoint('Nov', 15)
                ->addPoint('Dec', 10);

            $column_chart = (new ColumnChartModel())
                ->withoutLegend()
                ->addColumn('Occupied', 25, '#2563EB')
                ->addColumn('Available', 50, '#16A34A')
                ->addColumn('Reserved', 15, '#F59E0B')
                ->addColumn('Unvailable', 10, '#E11D48');

            $data = [
                'area_chart' => $area_chart,
                'column_chart' => $column_chart,
                'available_rooms' => $available_rooms,
                'pending_reservations' => $pending_reservations,
            ];
        }

        return view('dashboard', $data);
    }
}
