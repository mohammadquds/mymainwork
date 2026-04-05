<?php

namespace App\Livewire;
use App\Models\Activity_logs;
use Livewire\Component;

class ActivityLogsPage extends Component
{
    public function render()
    {
        $activity_logs = Activity_logs::with('user')->latest()->paginate(10);
        return view('livewire.activity-logs-page', compact('activity_logs'))
        ->layout('layoutscreen.app');
    }
}
