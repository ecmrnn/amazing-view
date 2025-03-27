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
        return Amenity::query();
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
                if ($amenity->quantity <= 5) {
                    return Blade::render('
                        <div class="flex items-center gap-5">
                            <x-tooltip text="Critical">
                                <div x-ref="content" class="p-2 text-red-800 border border-red-500 rounded-md bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-octagon-x-icon lucide-octagon-x"><path d="m15 9-6 6"/><path d="M2.586 16.726A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2h6.624a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586z"/><path d="m9 9 6 6"/></svg>
                                </div>
                            </x-tooltip>
                            <p>' . $amenity->name . '</p>
                        </div>
                    ');
                } elseif ($amenity->quantity <= 10) {
                    return Blade::render('
                        <div class="flex items-center gap-5">
                            <x-tooltip text="Danger">
                                <div x-ref="content" class="p-2 text-yellow-800 border border-yellow-500 rounded-md bg-yellow-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                </div>
                            </x-tooltip>
                            <p>' . $amenity->name . '</p>
                        </div>
                    ');
                } else {
                    return Blade::render('
                        <div class="flex items-center gap-5">
                            <x-tooltip text="Stocked!">
                                <div x-ref="content" class="p-2 text-green-800 border border-green-500 rounded-md bg-green-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-thumbs-up-icon lucide-thumbs-up"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"/></svg>
                                </div>
                            </x-tooltip>
                            <p>' . $amenity->name . '</p>
                        </div>
                    ');
                }
            })

            ->add('quantity_formatted', function($amenity) {
                if ($amenity->quantity <= 10) {
                    return Blade::render('<span class="font-semibold text-red-500">' . $amenity->quantity . ' Left</span>');
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
