<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity_logs;

class ActivityLogsPage extends Component
{
    public function render()
    {
      $activity_logs = Activity_logs::with('user')->latest()->get();
    return view('components.activity-logs-page', compact('activity_logs'));
    }
}