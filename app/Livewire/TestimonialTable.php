<?php

namespace App\Livewire;

use App\Models\Testimonial;
use App\Services\AuthService;
use App\Services\TestimonialService;
use App\Traits\DispatchesToast;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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

final class TestimonialTable extends PowerGridComponent
{
    use WithExport, DispatchesToast;

    public string $tableName = 'TestimonialTable';

    #[Validate] public $name;
    #[Validate] public $testimonial;
    #[Validate] public $rating;
    #[Validate] public $status;
    #[Validate] public $password;

    public function rules() {
        return [
            'name' => 'required',
            'testimonial' => 'required|max:200',
            'rating' => 'required|integer|between:1,5',
            'status' => 'required',
            'password' => 'required',
        ];
    }

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function noDataLabel(): string|View
    { 
        return view('components.table-no-data.testimonial');
    }

    public function datasource(): Builder
    {
        return DB::table('testimonials');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('testimonial')
            ->add('testimonial_formatted', function ($testimonial) {
                return '<div class="line-clamp-1">' . $testimonial->testimonial . '</div>';
            })
            ->add('rating')
            ->add('rating_formatted', function ($testimonial) {
                return $testimonial->rating . ' / 5';
            })
            ->add('status')
            ->add('status_formatted', function ($testimonial) {
                return Blade::render('<x-status type="testimonial" :status="' . $testimonial->status . '" />');
            })
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Testimonial', 'testimonial_formatted', 'testimonial')
                ->sortable()
                ->searchable(),

            Column::make('Rating', 'rating_formatted', 'rating')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status_formatted', 'status')
                ->sortable()
                ->searchable(),
            Column::action(''),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function actionsFromView($row)
    {
        return view('components.table-actions.testimonial', [
            'row' => $row,
        ]);
    }

    public function editTestimonial(Testimonial $testimonial, $data) {
        $this->name = $data['name'];
        $this->testimonial = $data['testimonial'];
        $this->rating = $data['rating'];
        $this->status = $data['status'];

        $validated = $this->validate([
            'name' => $this->rules()['name'],
            'testimonial' => $this->rules()['testimonial'],
            'rating' => $this->rules()['rating'],
            'status' => $this->rules()['status'],
        ]);
        $service = new TestimonialService;
        $service->edit($testimonial, $validated);

        $this->toast('Success!', description: 'Testimonial edited successfully!');
        $this->dispatch('testimonial-edited');
    }

    public function deleteTestimonial(Testimonial $testimonial) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new TestimonialService;
            $service->delete($testimonial);

            $this->reset('password');
            $this->toast('Success!', description: 'Testimonial deleted successfully!');
            $this->dispatch('testimonial-deleted');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    public function toggleStatus(Testimonial $testimonial) {
        $this->validate([
            'password' => $this->rules()['password'],
        ]);

        $auth = new AuthService;

        if ($auth->validatePassword($this->password)) {
            $service = new TestimonialService;
            $service->toggleStatus($testimonial);

            $this->reset('password');
            $this->toast('Success!', description: 'Testimonial status updated!');
            $this->dispatch('testimonial-status-updated');
        } else {
            $this->addError('password', 'Password mismatched, try again');
        }
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
