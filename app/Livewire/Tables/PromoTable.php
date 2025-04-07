<?php

namespace App\Livewire\Tables;

use App\Models\Promo;
use App\Services\AuthService;
use App\Services\PromoService;
use App\Traits\DispatchesToast;
use Fidry\CpuCoreCounter\NumberOfCpuCoreNotFound;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
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

final class PromoTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'PromoTable';

    public $name;
    public $code;
    public $amount;
    public $start_date;
    public $end_date;
    #[Validate] public $password;

    public function rules() {
        return [
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function setUp(): array
    {
        return [
            Header::make()
                ->withoutLoading()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Promo::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name_formatted', function ($promo) {
                return Blade::render('<p class="line-clamp-1 hover:line-clamp-none w-max">' . $promo->name . '</p>');
            })
            ->add('amount_formatted', function ($promo) {
                return Blade::render('<x-currency />' . number_format($promo->amount, 2));
            })
            ->add('status_formatted', function ($promo) {
                return Blade::render('<x-status type="promo" :status="' . $promo->status . '" />');
            })
            ->add('start_date_formatted', function ($promo) {
                return Carbon::parse($promo->start_date)->format('F j, Y');
            })
            ->add('end_date_formatted', function ($promo) {
                return Carbon::parse($promo->end_date)->format('F j, Y');
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            // Column::make('promo name', 'name_formatted', 'name')
            //     ->searchable(),
            Column::make('code', 'code', 'code')
                ->searchable(),
            Column::make('discount amount', 'amount_formatted', 'amount')
                ->searchable()
                ->sortable(),
            Column::make('promo starts', 'start_date_formatted', 'start_date'),
            Column::make('promo ends', 'end_date_formatted', 'end_date'),
            Column::make('status', 'status_formatted', 'status')
                ->searchable()
                ->sortable(),

            Column::action('')
        ];
    }

    public function actionsFromView($row)
    {
        $min_date = now()->format('Y-m-d');
        $can_activate = Carbon::now()->between($row->start_date, $row->end_date);
        
        return view('components.table-actions.promo', [
            'row' => $row,
            'can_activate' => $can_activate,
            'min_date' => $min_date,
        ]);
    }

    public function updatePromo($data) {
        $this->name = $data['name'];
        $this->amount = $data['amount'];
        $this->start_date = $data['start_date'];
        $this->end_date = $data['end_date'];
        
        $validated = $this->validate([
            'name' => 'required|string|max:255|regex:/^[A-Za-z0-9\- ]+$/',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $promo = Promo::find($data['id']);

        if ($promo) {
            $service = new PromoService;
            $service->update($promo, $validated);
            $this->toast('Success', description: 'Promo updated successfully!');
            $this->dispatch('pg:eventRefresh-PromoTable');
            $this->dispatch('promo-updated');
            return;
        } 
        
        $this->toast('Error', description: 'Promo not found!', type: 'error');
    }

    public function toggleStatus(Promo $promo) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new PromoService;
            $_promo = $service->toggleStatus($promo);

            if ($_promo) {
                $this->reset('password');
                $this->toast('Success!', description: strtoupper($promo->code) . '\'s status updated!');
                $this->dispatch('pg:eventRefresh-PromoTable');
                $this->dispatch('promo-status-changed');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');   
    }

    public function deletePromo(Promo $promo) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new PromoService;
            $_promo = $service->delete($promo);
            
            if ($_promo) {
                $this->toast('Promo Deleted', description: 'Promo deleted successfully!');
                $this->dispatch('promo-deleted');
                $this->dispatch('pg:eventRefresh-PromoTable');
                $this->reset('password');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');
    }
}
