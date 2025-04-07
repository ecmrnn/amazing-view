<?php

namespace App\Livewire;

use App\Enums\PromoStatus;
use App\Models\Promo;
use Livewire\Component;

class Banner extends Component
{
    public $promo;

    public function render()
    {
        $this->promo = Promo::whereStatus(PromoStatus::ACTIVE)->first();

        if ($this->promo) {
            return <<<'HTML'
            <div class="px-5 py-2 text-xs text-center text-white bg-gradient-to-r from-blue-500 to-blue-600">
                <p class="max-w-screen-xl mx-auto tracking-wide">⭐ Get
                <span class="font-semi-bold">
                    <x-currency />{{ number_format($promo->amount, 2) }}
                </span> off on your next booking with the code <span class="font-semibold">{{ $promo->code }}</span>. Hurry, offers expires
                @if ($promo->end_date == now()->format('Y-m-d'))
                    today!
                @else
                    on {{ date_format(date_create($promo->end_date), 'F j, Y') }}!
                @endif
                ⭐
                </p>
            </div>
            HTML;
        }

        return <<<'HTML'
            <div></div>
        HTML;
    }
}
