<?php

namespace App\Livewire;

use Livewire\Component;

class QuickCalculator extends Component
{

    public function render()
    {
        return view('livewire.quick-calculator')
        ->layout('layoutscreen.app');
    }
}
