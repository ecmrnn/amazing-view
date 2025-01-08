<?php

namespace App\Livewire\App\Report;

use App\Models\RoomType;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateReport extends Component
{
    public $reservation_type;
    public $min_date;

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
            'name' => 'required|string',
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
        if ($this->reservation_type != $type) {
            $this->reset();
        }

        $this->reservation_type = $type;
        $this->dispatch('open-modal', 'generate-report');
    }

    public function resetReportType() {
        $this->reset();
    }

    public function store() {
        $this->validate();
    }

    public function render()
    {
        $room_types = RoomType::select('id', 'name')->get();

        return view('livewire.app.report.create-report', [
            'room_types' => $room_types,
        ]);
    }
}
