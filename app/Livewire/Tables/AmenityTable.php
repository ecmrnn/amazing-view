<?php

namespace App\Livewire\Tables;

use App\Models\Amenity;
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
    
    public function editAmenity(Amenity $amenity) {
        $this->price = intval($amenity->price);
        $this->quantity = intval($amenity->quantity);
        $this->name = $amenity->name;
        
        $this->dispatch('open-modal', 'edit-amenity-' . $amenity->id);
    }

    public function updateAmenity(Amenity $amenity) {
        $amenity->price = $this->price;
        $amenity->name = $this->name;
        $amenity->quantity = $this->quantity;
        $amenity->save();

        $this->toast('Success!', 'success', 'Amenity updated!');
        $this->dispatch('amenity-updated');
    }

    public function deleteAmenity(Amenity $amenity) {
        $this->validate([
            'password' => $this->rules()['password']
        ]);

        $admin = Auth::user();

        if (Hash::check($this->password, $admin->password)) {            
            // delete room
            $amenity->delete();
            
            $this->toast('Amenity Deleted', 'success', 'Amenity deleted successfully!');
            $this->dispatch('amenity-deleted');
            $this->dispatch('pg:eventRefresh-AmenityTable');

            // reset
            $this->reset('password');
        } else {
            $this->toast('Deletion Failed', 'info', 'Incorrect password entered');
        }
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.reservations');
    }

    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'outside']);
    }

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Header::make()
                ->showToggleColumns()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(),
        ];
    }

    public function datasource(): Builder
    {
        return Amenity::query()->whereIsAddons(0);
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
            ->add('quantity_formatted', function($amenity) {
                if ($amenity->quantity <= 10) {
                    return Blade::render('<span class="font-semibold text-red-500">' . $amenity->quantity . ' Left</span>');
                }
                return Blade::render($amenity->quantity . ' Left');
            })
            ->add('price_formatted', function($amenity) {
                return Blade::render('<x-currency />' . ' ' .  number_format($amenity->price, 2));
            })
            ->add('created_at')
            ->add('created_at_formatted', function($amenity) {
                return date_format(date_create($amenity->created_at), 'F j, Y');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Amenity', 'name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Quantity', 'quantity_formatted', 'quantity')
                ->sortable()
                ->searchable(),

            Column::make('Price', 'price_formatted', 'price')
                ->sortable()
                ->searchable(),

            Column::make('Added on', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')
                ->placeholder('Name'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
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
