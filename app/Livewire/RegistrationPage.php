<?php

namespace App\Livewire;
use App\Models\Subscriptions;
use App\Models\Activity_logs;
use App\Models\User;

use Livewire\Component;

class RegistrationPage extends Component
{
    public $company_name, $type, $email, $phone_number , $Commercial_Registration_Number, $vat_number , $full_name;

    protected $rules = [
        'company_name' => 'required|min:3',
        'type' => 'required',
        'email' => 'required|email|unique:subscriptions,email',
        'phone_number' => 'required',
        'Commercial_Registration_Number' => 'nullable',
        'vat_number' => 'nullable',
    ];

    public function register()
    {
        $this->validate();
        // إنشاء المستخدم الجديد
        User::create([
            'name' => $this->full_name,
            'email' => $this->email,
            'password' => bcrypt('defaultpassword'),
        ]);

        // إنشاء طلب الاشتراك
        $sub = Subscriptions::create([
            'company_name' => $this->company_name,
            'type' => $this->type,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => 'pending', // حالة مبدئية
            'start_date' => now(),
            'Commercial_Registration_Number' => $this->Commercial_Registration_Number, // الحقل الجديد
            'vat_number' => $this->vat_number,
        ]);

        // تسجيل النشاط
        Activity_logs::create([
            'user_role' => 'Guest',
            'user_email' => $this->email,
            'activity_type' => 'طلب اشتراك جديد',
            'description' => "قامت شركة {$this->company_name} بتقديم طلب اشتراك جديد بنوع {$this->type}",
            'section' => 'التسجيل',
            'date' => now(),
        ]);

        session()->flash('message', 'تم إرسال طلبك بنجاح!');
        return redirect()->route('homePage');
        
    }
    public function render()
    {
        return view('livewire.registration-page');
    }
}
