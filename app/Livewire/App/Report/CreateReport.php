<?php

namespace App\Livewire\App\Report;

use App\Enums\ReportType;
use App\Enums\ReservationStatus;
use App\Http\Controllers\DateController;
use App\Jobs\GenerateReport;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Models\User;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateReport extends Component
{
    use DispatchesToast;

    public $min_date;
    public $max_date;
    
    #[Validate] public $name;
    #[Validate] public $description;
    #[Validate] public $type;
    #[Validate] public $format = 'pdf';
    #[Validate] public $note;
    #[Validate] public $start_date;
    #[Validate] public $end_date;
    #[Validate] public $room_type_id;
    #[Validate] public $size = 'letter';

    public function getListeners() {
        return [
            "echo-private:reports." . Auth::user()->id . ",ReportGenerated" => 'automaticDownloadReport',
            "echo:report,ReportGenerated" => 'refreshTable',
            "echo:report,ReportDeleted" => 'refreshTable',
        ];
    }

    public function rules() {
        return [
            'name' => 'required|string|regex:/^[\pL\s\-0-9]+$/u',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'format' => 'required|string',
            'note' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required_unless:type,"incoming reservations"|date|nullable',
            'size' => 'required_if:format,PDF',
            'room_type_id' => 'nullable|required_if:type,"occupancy report"'
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter the name of your report.',
            'name.regex' => 'File name   must include only letters, numbers, dashes, and spaces.',
            'type.required' => 'Select a type of report.',
            'format.required' => 'Select the format of your report.',
            'start_date.required' => 'Select a start date.',
            'end_date.required_unless' => 'Select an end date.',
        ];
    }

    public function setMinDate($date) {
        $this->min_date = Carbon::parse($date)->addDay()->format('Y-m-d');
    }

    #[On('generate-report')]
    public function setReportType($type) {
        if ($this->type != $type) {
            $this->reset();
        }

        $this->max_date = DateController::tomorrow();

        $this->start_date = $type == ReportType::INCOMING_RESERVATIONS->value
            ? DateController::tomorrow()
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $end_of_month = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->end_date = $this->max_date < $end_of_month
            ? DateController::today()
            : $end_of_month;

        // Generate file name
        switch ($type) {
            case ReportType::RESERVATION_SUMMARY->value:
                $this->name = 'Reservation Summary for the month of ' . Carbon::parse(DateController::today())->format('F');
                break;
            case ReportType::INCOMING_RESERVATIONS->value:
                $this->name = 'Incoming Reservations for ' . Carbon::parse(DateController::tomorrow())->format('F j, Y');
                break;
            case ReportType::OCCUPANCY_REPORT->value:
                $this->name = 'Occupancy Report for the month of ' . Carbon::parse(DateController::today())->format('F');
                break;
            case ReportType::REVENUE_PERFORMANCE->value:
                $this->name = 'Revenue Perforamnce for the month of ' . Carbon::parse(DateController::today())->format('F');
                break;
            default:
                $this->name = 'Amazing Filename';
                break;
        }
        
        $this->type = $type;
        $this->dispatch('open-modal', 'generate-report');
    }

    public function resetReportType() {
        $this->reset();
    }

    public function store() {
        $validated = $this->validate([
            'name' => $this->rules()['name'],
            'room_type_id' => $this->rules()['room_type_id'],
            'description' => $this->rules()['description'],
            'type' => $this->rules()['type'],
            'format' => $this->rules()['format'],
            'note' => $this->rules()['note'],
            'start_date' => $this->rules()['start_date'],
            'end_date' => $this->rules()['end_date'],
        ]);

        $validated['user_id'] = Auth::user()->id;
        $reservations = collect();

        // Check 
        switch ($this->type) {
            case ReportType::RESERVATION_SUMMARY->value:
                $reservations = Reservation::whereBetween('date_in', [$validated['start_date'], $validated['end_date']])
                    ->get();
                break;
            case ReportType::INCOMING_RESERVATIONS->value:
                $reservations = Reservation::whereDate('date_in', $validated['start_date'])
                    ->whereStatus(ReservationStatus::CONFIRMED->value)
                    ->get();
                break;
            case ReportType::OCCUPANCY_REPORT->value:
                $room_type_id = $validated['room_type_id'];
                $reservations = Reservation::whereBetween('date_in', [$validated['start_date'], $validated['end_date']])
                    ->whereHas('rooms', function ($query) use ($room_type_id) {
                        $query->where('room_type_id', $room_type_id);
                    })
                    ->whereIn('status', [ReservationStatus::CHECKED_IN, ReservationStatus::CHECKED_OUT])
                    ->get();
                break;
            case ReportType::REVENUE_PERFORMANCE->value:
                $reservations = Reservation::whereBetween('date_in', [$validated['start_date'], $validated['end_date']])
                    ->whereStatus(ReservationStatus::CHECKED_OUT)
                    ->get();
                break;
        }

        if ($reservations->count() == 0) {
            $this->toast('Insufficient Data', 'info', 'Not enough data for this report to generate');
            return;
        }

        // Store report to database
        $report = Report::create($validated);

        // Generate report
        GenerateReport::dispatch($report, $this->size);

        // Update path of the report
        $report->path = $report->format . '/report/' . $report->name . ' - ' . $report->rid . '.' . $report->format;
        $report->save();

        $this->toast('Success!', description: 'Report created');
        $this->dispatch('pg:eventRefresh-ReportsTable');
        $this->dispatch('report-created');
        $this->reset();
    }

    public function automaticDownloadReport($event) {
        $this->toast('Success!', description: 'Your file is ready to download. Stay online!');
        return response()->download(Storage::path('public/' . $event['report']['path']));
    }

    public function refreshTable($event) {
        if ($event['report']['user_id'] != Auth::user()->id) {
            $user = User::find($event['report']['user_id']);
            $this->dispatch('pg:eventRefresh-ReportsTable');
            $this->toast('Success!', description: 'A new report is generated by ' . $user->name());
        }
    }

    public function render()
    {
        $room_types = RoomType::select('id', 'name')->get();

        return view('livewire.app.report.create-report', [
            'room_types' => $room_types,
        ]);
    }
}
