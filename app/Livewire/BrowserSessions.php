<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BrowserSessions extends Component
{
    public $confirmingLogout = false;
    public $password = '';

    public function confirmLogout()
    {
        $this->password = '';
        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions()
    {
        $this->validate([
            'password' => 'required',
        ]);

        // Check if password is correct before logging them out of other devices
        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['كلمة المرور التي أدخلتها غير صحيحة.'],
            ]);
        }

        // Log them out of other devices using Laravel's built-in feature
        Auth::logoutOtherDevices($this->password);

        //  Delete the old sessions from the database table you showed me (except the current one)
        DB::table('sessions')
            ->where('user_id', Auth::user()->id)
            ->where('id', '!=', request()->session()->getId())
            ->delete();

        $this->confirmingLogout = false;
        session()->flash('message', 'تم تسجيل الخروج من جميع الأجهزة الأخرى بنجاح.');
    }

    public function getSessionsProperty()
    {
        // Fetch from the sessions table
        return DB::table('sessions')
            ->where('user_id', Auth::user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return (object) [
                    'agent' => $this->createAgent($session->user_agent),
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === request()->session()->getId(),
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });
    }

    // Helper function to figure out the Device and Browser from the long string
    protected function createAgent($userAgent)
    {
        $os = 'غير معروف';
        $browser = 'غير معروف';

        if (preg_match('/windows/i', $userAgent)) $os = 'Windows';
        elseif (preg_match('/mac/i', $userAgent)) $os = 'macOS';
        elseif (preg_match('/linux/i', $userAgent)) $os = 'Linux';
        elseif (preg_match('/iphone/i', $userAgent)) $os = 'iOS';
        elseif (preg_match('/android/i', $userAgent)) $os = 'Android';

        if (preg_match('/edg/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/firefox/i', $userAgent)) $browser = 'Firefox';

        return $os . ' - ' . $browser;
    }

    public function render()
    {
        return view('livewire.browser-sessions');
    }
}
