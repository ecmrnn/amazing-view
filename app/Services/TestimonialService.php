<?php 

namespace App\Services;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;

class TestimonialService
{
    public function create($data) {
        // Assuming $data is validated
        DB::transaction(function () use ($data) {
            Testimonial::create($data);
        });
    }

    public function edit(Testimonial $testimonial, $data) {
        DB::transaction(function () use ($testimonial, $data) {
            $testimonial->update($data);
        });
    }

    public function delete(Testimonial $testimonial) {
        DB::transaction(function () use ($testimonial) {
            $testimonial->delete();
        });
    }

    public function toggleStatus(Testimonial $testimonial) {
        DB::transaction(function () use ($testimonial) {
            if ($testimonial->status == TestimonialStatus::INACTIVE->value) {
                // dd($testimonial);
                $testimonial->update([
                    'status' => TestimonialStatus::ACTIVE->value
                ]);
                return $testimonial;
            }

            $testimonial->update([
                'status' => TestimonialStatus::INACTIVE->value
            ]);
        });
    }
}
