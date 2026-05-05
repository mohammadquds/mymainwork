<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class SubscriptionPage extends Component
{

    use WithPagination;
    public $showModal = false;
    public $selectedSub = null;
    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }

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

 $query = User::with('children');

    if (!empty($this->search)) {
        $query->where(function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%')
              ->orWhere('company_name', 'like', '%' . $this->search . '%');
        });
    } else {
        $query->whereNull('admin_id');
    }

       if (!auth()->user()->hasRole('Super Admin')) {
        $query->where(function($q) {
            $q->where('id', auth()->id())
              ->orWhere('admin_id', auth()->id());
        });
    }

    $subscriptions = $query->orderBy('id', 'asc')
                           ->paginate(10);

        return view('livewire.subscription-page', ['subscriptions' => $subscriptions])
            ->layout('layoutscreen.app');
    }
}


