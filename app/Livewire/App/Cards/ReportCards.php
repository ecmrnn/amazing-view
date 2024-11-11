<?php

namespace App\Livewire\App\Cards;

use App\Models\Report;
use App\Traits\DispatchesToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Ramsey\Collection\Map\AssociativeArrayMap;

class ReportCards extends Component
{
    public function render()
    {
        return view('livewire.app.cards.report-cards');
    }
}
