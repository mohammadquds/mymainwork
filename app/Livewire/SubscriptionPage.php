<?php

namespace App\Livewire;
use App\Models\Subscriptions;
use Livewire\Component;

class SubscriptionPage extends Component
{
    
    public function render()
    {
        $subscriptions = Subscriptions::with('user')->latest()->paginate(10);
        return view('livewire.subscription-page', compact('subscriptions'));
    }
}
