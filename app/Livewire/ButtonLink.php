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
            class="inline-block px-4 py-2 font-semibold rounded-lg border transition-all ease-in-out duration-200
                    {{ $type === 'primary'
                        ? 'bg-blue-500 text-white border-transparent hover:bg-blue-600 hover:border-blue-700'
                        : 'bg-white hover:bg-slate-50 hover:border-slate-200' }}"
            wire:navigate
            >
            {{ $name }}
        </a>
        HTML;
    }
}
