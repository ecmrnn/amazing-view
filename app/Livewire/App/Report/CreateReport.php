<?php

namespace App\Livewire\App\Report;

use App\Http\Controllers\GenerateReportController as GenerateReport;
use App\Models\Report;
use App\Models\RoomType;
use App\Traits\DispatchesToast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;

class CreateReport extends Component
{
    use DispatchesToast;

    public $min_date;
    public $size = 'letter';

    #[Validate] public $name;
    #[Validate] public $description;
    #[Validate] public $type;
    #[Validate] public $format;
    #[Validate] public $note;
    #[Validate] public $start_date;
    #[Validate] public $end_date;
    #[Validate] public $room_id;

    public function rules() {
        return [
            'name' => 'required|string|alpha_dash:ascii',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'format' => 'required|string',
            'note' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Enter the name of your report.',
            'type.required' => 'Select a type of report.',
            'format.required' => 'Select the format of your report.',
            'start_date.required' => 'Select a start date.',
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

        $this->type = $type;
        $this->dispatch('open-modal', 'generate-report');
    }

    public function resetReportType() {
        $this->reset();
    }

    public function store() {
        $validated = $this->validate([
            'name' => $this->rules()['name'],
            'description' => $this->rules()['description'],
            'type' => $this->rules()['type'],
            'format' => $this->rules()['format'],
            'note' => $this->rules()['note'],
            'start_date' => $this->rules()['start_date'],
            'end_date' => $this->rules()['end_date'],
        ]);

        $validated['user_id'] = Auth::user()->id;

        // Store report to database
        $report = Report::create($validated);
        
        // Generate report
        GenerateReport::generate($report, $this->type, $this->format, $this->name, $this->start_date, $this->end_date, $this->size, $this->room_id);
        
        
        $this->toast('Success!', description: 'Report created');
        $this->dispatch('pg:eventRefresh-ReportsTable');
        $this->dispatch('report-created');
        $this->reset(); 
    }

    public function render()
    {
        $room_types = RoomType::select('id', 'name')->get();

    return view('livewire.app.report.create-report', [
            'room_types' => $room_types,
        ]);
    }
}
