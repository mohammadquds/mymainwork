<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterAccount extends Component
{

    // signin component
    public $name;
    public $sign_email;
    public $sign_password;
    public $sign_password_confirmation;
    public $company_name;
    public $isCompanyLocked = false;




    // login component
    public $log_email;
    public $log_password;
    public $remember = false;


    // to compine two variables
    public $isLoginMode = false;

    // that will hold the admin id to share the url
    public $admin_id = null;



    // here the system will check if its from user unique link then will fill company name if not will leave it empty
    public function mount()
    {
        $this->admin_id = request()->query('ref');

        if ($this->admin_id) {
            $this->isLoginMode = true;

            $admin = User::where('invite_code', $this->admin_id)->first();

            if ($admin && $admin->company_name) {
                $this->company_name = $admin->company_name;
                $this->isCompanyLocked = true;
            }
        }
    }

    public function toggleMode()
    {
        $this->resetValidation();
        $this->reset(['name', 'sign_email', 'sign_password', 'sign_password_confirmation', 'company_name', 'log_email', 'log_password']);
        $this->isLoginMode = !$this->isLoginMode;
    }



    // sign in page
    public function registerUser()
    {
        $this->validate([
            'name' => 'required | max:255',
            'company_name' => 'required | max:255',
            'sign_email' => 'required|email|unique:users,email|max:255',
            'sign_password' => 'required|confirmed|min:8|max:255'
        ]);

        $admin = null;
        $isSuperAdminInvite = false;

        if ($this->admin_id) {
            $admin = User::where('invite_code', $this->admin_id)->first();

            // Check if the person who invited them is a Super Admin
            if ($admin && $admin->hasRole('Super Admin')) {
                $isSuperAdminInvite = true;
            }
        }

//  here will check if the new user came by an active super admin he will follow his boss but if he entered from generel url he will be admin with 3 days subscription

        $startDate = null;
        $endDate = null;
        $status = 'active';
        $assignedBossId = null;

        if ($admin) {
            //  They used an invite link

            $startDate = $admin->start_date;
            $endDate = $admin->end_date;
            $status = $admin->status ?? 'active';
            $assignedBossId = $admin->id;
        } else {
            //  They signed up on the public website

            $startDate = now();
            $endDate = now()->addDays(3);
            $status = 'active';
            $assignedBossId = null;
        }


        $user = User::create([
            'name' => $this->name,
            'email' => $this->sign_email,
            'company_name' => $this->company_name,
            'password' => Hash::make($this->sign_password),
            'admin_id' => $assignedBossId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
        ]);


        if ($assignedBossId === null) {

            $user->assignRole('Admin');
        } else {

            $user->assignRole('user');
        }

        Auth::login($user);
        session()->flash('congrats', 'welcome');

        return redirect('/homePage');
    }




    // log in page

    public function loginUser()
    {
        $this->validate([
            'log_email' => 'required|email|max:255',
            'log_password' => 'required|max:255'
        ]);

        $credentials = [
            'email' => $this->log_email,
            'password' => $this->log_password
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect('/homePage');
        }

        $this->addError('log_email', 'The credentials do not match our records.');
    }


    public function render()
    {
        return view('livewire.register-account');
    }
}
