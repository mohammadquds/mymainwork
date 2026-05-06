<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class RegisterAccount extends Component
{
    // email otp and the steps of email check then receive the otp and lastly fill your information and sign in
    public $currentStep = 1;
    public $enteredOtp;

    // sign in
    public $name;
    public $sign_email;
    public $sign_password;
    public $sign_password_confirmation;
    public $company_name;
    public $vat_number;
    public $official_company_number;

    public $mobile_number;
    public $isCompanyLocked = false;

    // log in
    public $log_email;
    public $log_password;
    public $remember = false;

    // change between log in and sign in
    public $isLoginMode = true;
    public $admin_id = null;



    public function mount()
    {
        $this->admin_id = request()->query('ref');

        if ($this->admin_id) {
            $this->isLoginMode = false;
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

        //  Create a list of fields to clear
        $fieldsToReset = [
            'name', 'sign_email', 'sign_password', 'sign_password_confirmation',
            'log_email', 'log_password', 'mobile_number',
             'enteredOtp'
        ];

        // Only clear the company name if it is NOT locked by an invite link
        if (!$this->isCompanyLocked) {
            $fieldsToReset[] = 'company_name';
        }

        // Reset the allowed fields
        $this->reset($fieldsToReset);

        $this->currentStep = 1;
        $this->isLoginMode = !$this->isLoginMode;
    }



    // 1 send the otp
    public function sendOtp()
    {
        //check the email first
        $this->validate([
            'sign_email' => 'required|email|unique:users,email|max:255',
        ]);

        // we will generaate 6 numbers for otp
        $otp = rand(100000, 999999);

        session()->put('registration_otp', $otp);
        session()->put('registration_email', $this->sign_email);
        session()->put('registration_otp_expires_at', now()->addMinutes(5));


        // otp for email
        Mail::raw("رمز التحقق : {$otp}", function($msg) {
            $msg->to($this->sign_email)->subject('Verification Code');
        });

        // move to Step 2 after cheacking the email
        $this->currentStep = 2;
    }

    //  2 VERIFY OTP

    public function verifyOtp()
    {
        $this->validate([
            'enteredOtp' => 'required|numeric|digits:6',
        ]);

        $serverOtp = session()->get('registration_otp');
        $expiresAt = session()->get('registration_otp_expires_at');

        // we will check if the oto excist
        if (!$serverOtp || !$expiresAt) {
            $this->addError('enteredOtp', 'يرجى طلب رمز جديد.');
            return;
        }

        // and will see if its not expired
        if (now()->isAfter($expiresAt)) {
            // It's too late Delete the old OTP and show an error.
            session()->forget(['registration_otp', 'registration_otp_expires_at']);
            $this->addError('enteredOtp', 'انتهت صلاحية الرمز. يرجى طلب رمز جديد.');
            return;
        }

        // does the wriitten otp matches the otp sended before
        if ($this->enteredOtp == $serverOtp) {
            // Success! Move to final step
            $this->currentStep = 3;
        } else {
            $this->addError('enteredOtp', 'الرمز المدخل غير صحيح.');
        }
    }

    public function stepBack()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // 3 sign in steps
public function registerUser()
    {
        if ($this->currentStep !== 3) {
            return;
        }
        //  Find the Admin (if this is an invitation link)
        $admin = null;
        if ($this->admin_id) {
            $admin = User::where('invite_code', $this->admin_id)->first();
        }

        if ($admin) {
            $this->company_name = $admin->company_name;
        }

        //  Now it is safe to validate
        $this->validate([
            'name' => 'required|max:255',
            'company_name' => 'required|max:255',
            'mobile_number' => 'required|max:20',
            'sign_password' => 'required|confirmed|min:8|max:255'
        ]);

        $startDate = null;
        $endDate = null;
        $status = 'active';
        $assignedBossId = null;

        // Variables to hold the company details
        $finalCompanyName = $this->company_name;
        $finalVatNumber = null;
        $finalOfficialNumber = null;

        if ($admin) {
            $startDate = $admin->start_date;
            $endDate = $admin->end_date;
            $status = $admin->status ?? 'active';
            $assignedBossId = $admin->id;

            //  Force the hidden data directly from the Admin
            $finalCompanyName = $admin->company_name;
            $finalVatNumber = $admin->vat_number;
            $finalOfficialNumber = $admin->official_company_number;
        } else {
            // This is a brand new Admin registering themselves
            $startDate = now();
            $endDate = now()->addDays(3);
            $status = 'active';
            $assignedBossId = null;

            // They will fill these out later in the Onboarding Popup!
            $finalVatNumber = null;
            $finalOfficialNumber = null;
        }

        // Create the User using our secure $final variables
        $user = User::create([
            'name' => $this->name,
            'email' => session()->get('registration_email'),
            'mobile_number' => $this->mobile_number,
            'password' => Hash::make($this->sign_password),
            'admin_id' => $assignedBossId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'company_name' => $finalCompanyName,
            'vat_number' =>  $finalVatNumber,
            'official_company_number' => $finalOfficialNumber,
        ]);

        if ($assignedBossId === null) {
            $user->assignRole('Admin');
        } else {
            $user->assignRole('user');
        }

        session()->forget(['registration_otp', 'registration_email']);

        Auth::login($user);
        session()->flash('congrats', 'welcome');

        return redirect('/homePage');
    }



// log in steps
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
