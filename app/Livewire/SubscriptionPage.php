<?php

namespace App\Livewire;
use App\Models\Subscriptions;
use Livewire\Component;
use App\Models\User;

class SubscriptionPage extends Component
{

    // public function render()
    // {
    //     $subscriptions = Subscriptions::with('user')->latest()->paginate(10);
    //     return view('livewire.subscription-page', compact('subscriptions'))
    //     ->layout('layoutscreen.app');
    // }
// Add these at the top of your class
public $showModal = false;
public  $selectedSub = null;

// Add this function to open the pop-up
public function openDetails($id)
{
    // Make sure 'Subscription' matches your actual Model name!
    $this->selectedSub = \App\Models\User::findOrFail($id);
    $this->showModal = true;
}

// Add this function to close it
public function closeModal()
{
    $this->showModal = false;
    $this->selectedSub = null;
}



// the old one that shows only admin  in the subscription now its shows the admin and its child

// public function render()
// {
//     // $this->authorize('subscription.view');
//     // If Admin, see everyone. If User, see only yourself.
//     $query = auth()->user()->hasRole('Super Admin')
//              ? User::query()
//              : User::where('id', auth()->id());

//     $subscriptions = $query->whereNotNull('end_date')
//                                 ->paginate(10);

//     return view('livewire.subscription-page', [
//         'subscriptions' => $subscriptions
//     ])->layout('layoutscreen.app');
// }
// }


public function render()
{
    // 1. Start the query
    $query = \App\Models\User::query();

    // 2. THE FIX: Apply the Parent-Child security filter
    if (!auth()->user()->hasRole('Super Admin')) {
        $query->where(function($q) {
            $q->where('id', auth()->id())          // Show my own subscription
              ->orWhere('admin_id', auth()->id()); // Show my children's subscriptions
        });
    }

    // 3. Get the results
    $subscriptions = $query->whereNotNull('end_date')->paginate(10); // (Or however you normally sort/paginate this page)

    return view('livewire.subscription-page', [
        'subscriptions' => $subscriptions
    ])->layout('layoutscreen.app');
}
}
