<?php

namespace App\Livewire\App\Reports\CreateReport;

use App\Models\Report;
use App\Traits\DispatchesToast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FinancialReport extends Component
{
    use DispatchesToast;

    #[Validate] public $name;
    #[Validate] public $description;
    #[Validate] public $start_date;
    #[Validate] public $end_date;
    #[Validate] public $note;
    #[Validate] public $type;
    #[Validate] public $format = 'pdf';

    public function mount($type) {
        $this->type = $type;
    }

    public function rules() {
        return Report::rules();
    }

    public function messages() {
        return Report::messages();
    }

    public function validationAttributes() {
        return Report::validationAttributes();
    }

    public function store() {
        $validated = $this->validate([
            'name' => $this->rules()['name'],
            'type' => $this->rules()['type'],
            'description' => $this->rules()['description'],
            'start_date' => $this->rules()['start_date'],
            'end_date' => $this->rules()['end_date'],
            'note' => $this->rules()['note'],
            'format' => $this->rules()['format'],
        ]);

        Report::create(
            array_merge($validated, [
                'user_id' => Auth::user()->id
            ])
        );

        $this->reset();
        $this->format = 'pdf';
        $this->toast('Success!', 'success', 'Report created!');
        $this->dispatch('pg:eventRefresh-ReportsTable');
        $this->dispatch('report-generated');
    }
    
    public function render()
    {
        return <<<'HTML'
        <section x-on:report-generated.window="show = false" class="p-5 space-y-5 bg-white">
            <div class="flex items-center gap-3">
                <x-tooltip text="Back" dir="bottom">
                    <x-icon-button x-ref="content" x-on:click="show = false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    </x-icon-button>
                </x-tooltip>

                <hgroup>
                    <h2 class="font-semibold capitalize">Financial Report</h2>
                    <p class="text-xs">Creating a financial report.</p>
                </hgroup>
            </div>

            <x-note>
                <p class="text-xs">Submitting this form will automatically download the generated report. Stay online!</p>
            </x-note>

            <div class="space-y-3">
                <div class="space-y-2">
                    <div>
                        <x-form.input-label for="name-financial">Name &amp; Description</x-form.input-label>
                        <p class="text-xs">Write a short name and a brief description for your report.</p>
                    </div>
                    {{-- Name --}}
                    <div class="space-y-2">
                        <x-form.input-text id="name-financial" label="Name" wire:model.live='name' />
                        <x-form.input-error field="name" />
                    </div>
                    {{-- Description --}}
                    <div class="space-y-2">
                        <x-form.input-text id="description-financial" label="Description" wire:model.live='description' />
                        <x-form.input-error field="description-financial" />
                    </div>
                </div>

                {{-- Start and End Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="w-full space-y-2">
                        <x-form.input-label for="start_date-financial">From</x-form.input-label>
                        <x-form.input-date id="start_date-financial" wire:model.live='start_date' class="w-full" />
                        <x-form.input-error field="start_date" />
                    </div>
                    <div class="w-full space-y-2">
                        <x-form.input-label for="end_date-financial">To</x-form.input-label>
                        <x-form.input-date id="end_date-financial" wire:model.live='end_date' min="{{ $start_date }}" class="w-full" />
                        <x-form.input-error field="end_date" />
                    </div>
                </div>

                {{-- Note --}}
                <div class="space-y-2">
                    <x-form.input-label for="note-financial">Additional Note &lpar;Optional&rpar;</x-form.input-label>
                    <x-form.input-text id="note-financial" name="note" label="Note" wire:model.live='note' />
                    <x-form.input-error field="note" />
                </div>

                {{-- Format --}}
                <div class="space-y-2">
                    <x-form.input-label for="format-financial">Select Format</x-form.input-label>
                    <x-form.select id="format-financial" wire:model.live='format' name="format">
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </x-form.select>
                    <x-form.input-error field="format" />
                </div>

                <x-primary-button class="text-xs" wire:click="store">
                    Generate Report
                </x-primary-button>
            </div>
        </section>
        HTML;
    }
}
