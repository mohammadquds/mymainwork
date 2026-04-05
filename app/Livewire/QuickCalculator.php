<?php

namespace App\Livewire;

use Livewire\Component;

class QuickCalculator extends Component
{


// public $karat = '21';
// public $weight = '';
// public $discount ='';
// public $cost_per_gram ='';
// public $price_per_gram = '';


// public function getTotalProperty(){
//     $subtotal = ((float)$this->price_per_gram * (float)$this->weight);
//     $discountAmount = ((float)$this->discount /100) * $subtotal;

//     return max(0, $subtotal - $discountAmount);
// }


// public function getProfitProperty(){
//     $totalCost = ((float)$this->cost_per_gram * (float)$this->weight);

//     return $this->total -$totalCost;
// }

// public function resetCalculator(){
//     $this->reset(['cost_per_gram', 'discount', 'weight', 'price_per_gram']);
//     $this->karat ='21';
// }



    public function render()
    {
        return view('livewire.quick-calculator')
        ->layout('layoutscreen.app');
    }
}
