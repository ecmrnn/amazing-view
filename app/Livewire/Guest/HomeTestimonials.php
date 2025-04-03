<?php

namespace App\Livewire\Guest;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Livewire\Component;

class HomeTestimonials extends Component
{
    public $testimonials;
    public $max;

    public function render()
    {
        $this->testimonials = Testimonial::whereStatus(TestimonialStatus::ACTIVE)->orderByDesc('rating')->get();
        $this->max = Testimonial::whereStatus(TestimonialStatus::ACTIVE)->count();
        
        return view('livewire.guest.home-testimonials');
    }
}
