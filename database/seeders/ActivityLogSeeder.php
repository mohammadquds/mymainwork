<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity_logs;
use App\Models\Subscriptions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $users = User::factory(3)->create(); // إنشاء 3 مستخدمين إذا لم يوجد أحد
        }

        $roles = ['User', 'Admin', 'Super Admin'];
        $activities = ['تسجيل دخول', 'تعديل ملف', 'حذف مستخدم', 'تحديث إعدادات', 'إضافة طلب'];
        $sections = ['لوحة التحكم', 'الإعدادات', 'المستخدمين', 'التقارير'];

        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random(); // اختيار مستخدم عشوائي من الموجودين
            
            Activity_logs::create([
                'user_id'       => $user->id,
                'user_role'     => Arr::random($roles), 
                'user_email'    => $user->email,
                'activity_type' => Arr::random($activities),
                'description'   => 'قام المستخدم بإجراء عملية تجريبية رقم ' . $i,
                'section'       => Arr::random($sections),
                'date'          => Carbon::now()->subMinutes(rand(1, 1000)), // تواريخ متنوعة
            ]);
        }
        // إضافة مستخدم واحد فقط وسجل اشتراك واحد
        $testUser = User::first() ?? User::factory()->create([
            'name' => 'عمر',
            'email' => 'omar@example.com'
        ]);

        Subscriptions::create([
            'company_name' => 'مؤسسة عمر البرمجية',
            'type' => 'سنوي (Enterprise)',
            'email' => $testUser->email,
            'phone_number' => '0558599402',
            'user_id' => $testUser->id,
            'Commercial_Registration_Number' => 'CRN-123456789',
            'vat_number' => 'VAT-987654321',
            'status' => 'active',
            'duration' => '1 year',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
        ]);
    }
}