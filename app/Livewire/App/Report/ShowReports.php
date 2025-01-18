<?php

namespace App\Livewire\App\Report;

use App\Events\Report\ReportDeleted;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ShowReports extends Component
{
    public $reports_count;
    #[Validate()] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }
    
    public function getListeners() {
        return [
            "echo:report,ReportGenerated" => 'getReportsCount',
            "report-created" => 'getReportsCount',
            "report-deleted" => 'getReportsCount',
        ];
    }

    public function getReportsCount() {
        $this->reports_count = Report::count();
    }

    public function bulkDelete() {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        if (Hash::check($this->password, Auth::user()->password)) {
            $this->dispatch('bulkDelete.ReportsTable');
        } else {
            $this->addError('password', 'Password mismatch, try again.');
        }
        $this->reset('password');
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

            <x-modal.full name='bulk-delete-reports' maxWidth='sm'>
                <div>
                    <form class="p-5 space-y-5 bg-white" x-on:submit.prevent="$wire.bulkDelete()" x-on:report-deleted.window="show = false">
                        <hgroup>
                            <h2 class="font-semibold text-red-500 capitalize">Bulk Delete Reports</h2>
                            <p class="max-w-sm text-sm">You are about to delete these reports, this action cannot be undone</p>
                        </hgroup>
        
                        <div class="space-y-2">
                            <x-form.input-label for="password">Enter your password to delete these reports</x-form.input-label>
                            <x-form.input-text wire:model="password" type="password" label="Password" id="password" />
                            <x-form.input-error field="password" />
                        </div>
                        
                        <div class="flex items-center justify-end gap-1">
                            <x-secondary-button type="button" x-on:click="show = false">No, Cancel</x-secondary-button>
                            <x-danger-button type="submit">Yes, Delete</x-danger-button>
                        </div>
                    </form>
                </div>
            </x-modal.full>
        </div>
        HTML;
    }
}
