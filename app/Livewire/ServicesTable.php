<?php

namespace App\Livewire;

use App\Models\AdditionalServices;
use App\Services\AdditionalServiceHandler;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Illuminate\Contracts\View\View;
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

final class ServicesTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'ServicesTable';
    
    #[Validate] public $name;
    #[Validate] public $description;
    #[Validate] public $price;
    #[Validate] public $password;

    public function rules() {
        return [
            'name' => 'required',
            'description' => 'required|max:200',
            'price' => 'required|min:1|integer',
            'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function updateService($data) {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];

        $this->validate([
            'name' => $this->rules()['name'],
            'description' => $this->rules()['description'],
            'price' => $this->rules()['price'],
        ]);

        $service = AdditionalServices::find($data['id']);

        if ($service) {
            $service->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
            ]);

            $this->dispatch('service-updated');
            $this->dispatch('pg:eventRefresh-ServicesTable');
            $this->toast('Success!', description: ucwords(strtolower($service->name)) . ' updated successfully!');
            return;
        }
    }

    public function toggleStatus(AdditionalServices $service) {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $handler = new AdditionalServiceHandler;
            $handler->toggleStatus($service);

            $this->reset('password');
            $this->dispatch('service-status-changed');
            $this->dispatch('pg:eventRefresh-ServicesTable');
            $this->toast('Success!', description: ucwords(strtolower($service->name)) . ' status changed!');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function deleteService(AdditionalServices $service) {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $handler = new AdditionalServiceHandler;
            $handler->delete($service);

            $this->reset('password');
            $this->dispatch('service-deleted');
            $this->dispatch('pg:eventRefresh-ServicesTable');
            $this->toast('Success!', description: 'Service deleted successfully!');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');   
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.services');
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
        return AdditionalServices::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('name_formatted', function ($service) {
                return Blade::render('<div class="capitalize w-max">' . $service->name .  '</div>');
            })
            ->add('description')
            ->add('description_formatted', function ($service) {
                if ($service->description) {
                    return Blade::render('<div class="line-clamp-1">' . $service->description . '</div>');
                }

                return Blade::render('<span class="text-xs text-zinc-800/50">---</span>');
            })
            ->add('price')
            ->add('price_formatted', function ($service) {
                return Blade::render('<x-currency />' . number_format($service->price, 2));
            })
            ->add('status_formatted', function ($service) {
                return Blade::render('<x-status type="service" :status="' . $service->status . '" />');
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name_formatted', 'name')
                ->searchable(),
            Column::make('description', 'description_formatted', 'description'),
            Column::make('price', 'price_formatted', 'price')
                ->sortable(),
            Column::make('status', 'status_formatted', 'status'),
            Column::action('')
        ];
    }

    public function actionsFromView(AdditionalServices $row)
    {
        return view('components.table-actions.services', [
            'row' => $row,
        ]);
    }
}
