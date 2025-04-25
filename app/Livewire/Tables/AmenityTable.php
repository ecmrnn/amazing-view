<?php

namespace App\Livewire\Tables;

use App\Models\Amenity;
use App\Services\AmenityService;
use App\Services\AuthService;
use App\Traits\DispatchesToast;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use NumberFormatter;
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

final class AmenityTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    #[Validate] public $password;
    #[Validate] public $price;
    #[Validate] public $quantity;
    #[Validate] public $name;

    public string $tableName = 'AmenityTable';

    public function rules() {
        return [
            'password' => 'required',
            'price' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'name' => 'required',
        ];
    }

    public function messages() {
        return [
            'password.required' => 'Enter your password',
        ];
    }

    public function updateAmenity($data) {
        $this->name = $data['name'];
        $this->quantity = $data['quantity'];
        $this->price = $data['price'];

        $validated = $this->validate([
            'name' => $this->rules()['name'],
            'quantity' => $this->rules()['quantity'],
            'price' => $this->rules()['price'],
        ]);

        $amenity = Amenity::find($data['id']);

        $service = new AmenityService;
        $amenity = $service->update($amenity, $validated);

        if ($amenity) {
            $this->toast('Success!', 'success', 'Amenity updated!');
            $this->dispatch('amenity-updated');
            return;
        }
    }

    public function deleteAmenity(Amenity $amenity) {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $amenity->delete();
            
            $this->toast('Amenity Deleted', description: 'Amenity deleted successfully!');
            $this->dispatch('pg:eventRefresh-AmenityTable');
            $this->dispatch('amenity-deleted');
            $this->reset('password');
            return;
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function toggleStatus(Amenity $amenity) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new AmenityService;
            $_amenity = $service->toggleStatus($amenity);

            if ($_amenity) {
                $this->reset('password');
                $this->toast('Success!', description: ucwords(strtolower($amenity->name)) . '\'s status updated!');
                $this->dispatch('pg:eventRefresh-AmenityTable');
                $this->dispatch('amenity-status-changed');
                return;
            }
        }

        $this->addError('password', 'Password mismatched, try again!');
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.amenity');
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        return [
            Header::make()
                ->withoutLoading()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return Amenity::query()->with('rooms');
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
            ->add('name_formatted', function ($amenity) {
                $ping = 'bg-green-400';
                $style = 'bg-green-50 border border-green-500';
                
                if ($amenity->quantity <= 5) {
                    $ping = 'bg-red-400';
                    $style = 'bg-red-50 border border-red-500';
                } elseif ($amenity->quantity <= 10) {
                    $ping = 'bg-amber-400';
                    $style = 'bg-amber-50 border border-amber-500';
                }

                return Blade::render('
                        <div class="flex items-center gap-3">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping ' . $ping . '"></span>
                            <span class="relative inline-flex rounded-full size-2 ' . $style . '"></span>
                        </span>' . 
                        $amenity->name .
                    '</div>');
            })

            ->add('quantity_formatted', function($amenity) {
                if ($amenity->quantity <= 5) {
                    return Blade::render('<span class="text-red-500">' . $amenity->quantity . ' Left</span>');
                }
                
                if ($amenity->quantity <= 10) {
                    return Blade::render('<span class="text-amber-500">' . $amenity->quantity . ' Left</span>');
                }
                
                return Blade::render($amenity->quantity . ' Left');
            })

            ->add('price_formatted', function($amenity) {
                return Blade::render('<x-currency />'.  number_format($amenity->price, 2));
            })

            ->add('status_formatted', function ($amenity) {
                return Blade::render('<x-status type="amenity" :status="' . $amenity->status . '" />');
            })

            ->add('created_at')
            ->add('created_at_formatted', function($amenity) {
                return date_format(date_create($amenity->created_at), 'F j, Y');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Amenity', 'name_formatted', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Quantity', 'quantity_formatted', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Price', 'price_formatted', 'price')
                ->sortable()
                ->searchable(),

            Column::make('Added on', 'created_at_formatted', 'created_at'),
            
            Column::make('Status', 'status_formatted', 'status'),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::number('price', 'price')
                ->thousands('.')
                ->placeholder('Min', 'Max')
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.amenity', [
            'row' => $row,
            'edit_link' => 'app.reservations.edit',
            'view_link' => 'app.reservations.show',
        ]);
    }
}
