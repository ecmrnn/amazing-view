<?php

namespace App\Livewire\Tables;

use App\Events\ReportDeleted;
use App\Jobs\DeleteReport;
use App\Models\Report;
use App\Traits\DispatchesToast;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ReportsTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'ReportsTable';
    #[Validate] public string $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.reports');
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Header::make()
                ->showSearchInput()
                ->withoutLoading(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }
    
    public function header(): array
    {
        return [
            Button::add('bulk-delete')
                ->slot(__('Bulk Delete (<span x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)'))
                ->class('inline-block text-xs px-4 py-2 bg-gradient-to-r cursor-pointer from-red-500 to-red-600 text-white shadow-md hover:translate-y-[2px] hover:shadow-none font-semibold rounded-lg border border-transparent hover:border-red-700 focus:outline-none focus:ring-0 focus:border-red-600 transition-all ease-in-out duration-200')
                ->dispatch('confirmBulkDelete', []),
            Button::add('bulk-download')
                ->slot('Bulk Download')
                ->class('inline-block px-4 py-2 text-xs bg-white text-blue-800 rounded-md font-semibold hover:shadow-none focus:outline-none focus:ring-0 focus:border-blue-600 disabled:opacity-25 transition ease-in-out duration-150 disabled:shadow-none')
                ->dispatch('bulkDownload', []),
        ];
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function datasource(): Builder
    {
        return Report::query()->with('user')
            ->whereNotNull('user_id');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('rid')
            ->add('name_formatted', function ($report) {
                return '<span class="capitalize">' . $report->name . '</span>';
            })
            
            ->add('type', fn($report) => e(ucwords($report->type)))
            ->add('description')
            ->add('description_formatted', function ($report) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . html_entity_decode($report->description, ENT_QUOTES, 'UTF-8')  . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . html_entity_decode($report->description) . '</div>
                    </x-tooltip>'
                );
            })
            ->add('user_id')
            ->add('user_id_formatted', function ($report) {
                return '<span class="capitalize">' . $report->user->name() . '</span>';
            })
            ->add('note')
            ->add('note_formatted', function ($report) {
                return Blade::render(
                    '<x-tooltip :textWrap="false" text="' . html_entity_decode($report->note, ENT_QUOTES, 'UTF-8')  . '" dir="top">
                        <div x-ref="content" class="max-w-[250px] line-clamp-1">' . html_entity_decode($report->note) . '</div>
                    </x-tooltip>'
                );
            })
            ->add('created_at_formatted', function ($report) {
                return Carbon::parse($report->created_at)->format('F j, Y');
            })
            ->add('format_formatted', function ($report) {
                if ($report->format == 'pdf') {
                    return "<span class='px-2 py-1 text-xs font-semibold text-white border border-red-500 rounded bg-red-500/75'>PDF</span>";
                } else {
                    return "<span class='px-2 py-1 text-xs font-semibold text-white border border-green-500 rounded bg-green-500/75'>CSV</span>";
                }
                
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Report ID', 'rid')
                ->sortable()
                ->searchable(),

            Column::make('File Name', 'name_formatted', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Type', 'type')
                ->sortable(),

            Column::make('Generated By', 'user_id_formatted', 'user_id')
                ->sortable(),

            Column::make('Date Generated', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Format', 'format_formatted', 'format')
                ->sortable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.report', [
            'row' => $row,
            'edit_link' => 'app.reports.edit',
            'view_link' => 'app.reports.show',
        ]);
    }

    public function viewReport(Report $report) {
        $filename = $report->name . ' - ' . $report->rid . '.' . $report->format;
        return Storage::response('public/' . $report->format . '/report/' . $filename, headers: [
            'Content-Type' => 'application/pdf'
        ]);
    }

    #[On('delete-report')]
    public function deleteReport($id) {
        $this->validate(['password' => $this->rules()['password']]);

        if (Hash::check($this->password, Auth::user()->password)) {
            $report = Report::find($id);

            if (!$report) {
                $this->toast('Missing Report', 'info', 'Report cannot be found.');
            } else {
                if (Storage::exists($report->path ?? 'null')) {
                    Storage::disk('public')->delete($report->path);
                }
        
                $report->delete();
        
                $this->toast('Success', description: 'Report successfully deleted');
            }
        } else {
            $this->addError('password', 'Password mismatch, try again.');
        }
        $this->reset('password');
    }

    #[On('bulkDelete.ReportsTable')]
    public function bulkDelete() {
        if (empty($this->checkboxValues)) {
            $this->toast('Select a Report', 'info', 'Select one or more reports to delete');
            $this->dispatch('report-deleted');
            return;
        }

        $reports = Report::whereIn('id', $this->checkboxValues)->get();

        foreach ($reports as $report) {
            if (Storage::exists($report->path ?? 'null')) {
                Storage::disk('public')->delete($report->path);
            }
    
            $report->delete();
        }

        $this->dispatch('report-deleted');
        $this->toast('Success', description: 'Reports deleted successfully');
    }

    #[On('confirmBulkDelete')]
    public function confirmBulkDelete() {
        $this->dispatch('open-modal', 'bulk-delete-reports');
    }

    #[On('bulkDownload')]
    public function bulkDownload() {
        if (empty($this->checkboxValues)) {
            $this->toast('Select a Report', 'info', 'Select one or more reports to download');
            return;
        }

        $report_paths = Report::find($this->checkboxValues)->pluck('path');
        $zip = new \ZipArchive();
        $zipFileName = 'Amazing View Mountain Resort - Reports.zip';

        if ($zip->open(storage_path($zipFileName), \ZipArchive::CREATE) === TRUE) {
            foreach ($report_paths as $path) {
                $filePath = Storage::path('public/' . $path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        }

        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }
}
