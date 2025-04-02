<?php

namespace App\Livewire\Guest;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Livewire\Component;

class HomeTestimonials extends Component
{
    public $testimonials;
    public $limit = 3;
    public $testimonial_count;

    public function seeMore() {
        $this->limit += 3;
    }

    public function render()
    {
        $this->testimonials = Testimonial::whereStatus(TestimonialStatus::ACTIVE)
            ->limit($this->limit)
            ->get();
        $this->testimonial_count = Testimonial::whereStatus(TestimonialStatus::ACTIVE)->count();

        return view('livewire.guest.home-testimonials');
    }
}
