<?php

namespace App\Livewire\App\Report;

use App\Models\Report;
use Livewire\Component;

class ShowReports extends Component
{
    public $reports_count;
    
    public function getListeners() {
        return [
            "echo:report,ReportGenerated" => 'getReportsCount',
            "report-created" => 'getReportsCount',
        ];
    }

    public function getReportsCount() {
        $this->reports_count = Report::count();
    }

    public function render()
    {
        $this->getReportsCount();
        
        return <<<'HTML'
        <div class="p-5 bg-white border rounded-lg border-slate-200">
            @if ($reports_count > 0)
                <livewire:tables.reports-table />
            @else
                <div class="font-semibold text-center border rounded-md border-slate-200">
                    <x-table-no-data.reports />
                </div>
            @endif
        </div>
        HTML;
    }
}
