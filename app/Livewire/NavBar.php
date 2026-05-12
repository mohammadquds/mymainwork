<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NavBar extends Component
{
// the nav bar new comment
    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/register-account');
    }
    public function render()
    {
        return view('livewire.nav-bar')
            ->layout('layoutscreen.app');
    }
}
