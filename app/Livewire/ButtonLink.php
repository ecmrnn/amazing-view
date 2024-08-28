<?php

namespace App\Livewire;

use Livewire\Component;

class ButtonLink extends Component
{
    public $href;
    public $name;
    public $type = 'primary';

    public function render()
    {
        return <<<'HTML'
        <a 
            href="{{ $href }}"
            class="inline-block px-4 py-2 font-semibold rounded-lg border focus:outline-none focus:ring-0 focus:border-blue-600 transition-all ease-in-out duration-200
                    {{ $type === 'primary'
                        ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white border-transparent hover:border-blue-700 hover:shadow-lg'
                        : 'bg-white hover:bg-slate-50 hover:border-slate-200' }}"
            wire:navigate
            >
            {{ $name }}
        </a>
        HTML;
    }
}
