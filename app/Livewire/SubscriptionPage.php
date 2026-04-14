<?php

namespace App\Livewire;
use App\Models\Subscriptions;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class SubscriptionPage extends Component
{

    use WithPagination;
    public $showModal = false;
    public $selectedSub = null;

    public function openDetails($id)
    {
        $this->selectedSub = User::findOrFail($id);
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSub = null;
    }


    public function render()
    {
        $this->authorize('subscription.unactive.view');
        $query = User::query();

        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where(function ($q) {
                $q->where('id', auth()->id())
                    ->orWhere('admin_id', auth()->id());
            });
        }
        $subscriptions = $query->whereNotNull('end_date')->paginate(10);

        return view('livewire.subscription-page', ['subscriptions' => $subscriptions])
            ->layout('layoutscreen.app');
    }
}
