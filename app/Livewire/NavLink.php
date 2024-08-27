<?php

namespace App\Livewire;

use Livewire\Component;

class NavLink extends Component
{
    public $to; /* Name of the link */
    public $href; /* Redirect link  */
    public $active = false; /* Identify active link  */

    public $classes = 'border-b-2 border-transparent inline-flex items-center py-2 transition duration-150 ease-in-out';

    public function isActive($active) {
        ($active ?? false)
            ? $this->classes .= ' text-zinc-800'
            : $this->classes .= ' text-zinc-800/50 hover:text-zinc-800 hover:border-blue-500';
    }
    
    public function render()
    {
        $this->isActive($this->active);

        return <<<'HTML'
        <a
            href="{{ $href }}"
            class="{{ $classes }}"
            wire:navigate>
            {{ $to }}
        </a>
        HTML;
    }
}
