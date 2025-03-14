<?php

namespace App\Livewire\App\Content\Home;

use App\Enums\TestimonialStatus;
use App\Services\TestimonialService;
use App\Traits\DispatchesToast;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ShowTestimonials extends Component
{
    use DispatchesToast;

    #[Validate] public $name;
    #[Validate] public $testimonial;
    #[Validate] public $rating = 5;

    public function rules() {
        return [
            'name' => 'required',
            'testimonial' => 'required|max:200',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    public function addTestimonial() {
        $validated = $this->validate();
        $validated['status'] = TestimonialStatus::ACTIVE->value;

        $service = new TestimonialService;
        $service->create($validated);

        $this->toast('Success!', description: 'Testimonial created succesfully!');
        $this->dispatch('pg:eventRefresh-TestimonialTable');
        $this->dispatch('testimonial-added');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.app.content.home.show-testimonials');
    }
}
