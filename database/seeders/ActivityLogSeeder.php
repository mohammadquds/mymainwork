<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity_logs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود مستخدمين في النظام أو أنشئ مستخدمين جدد
        $users = User::all();
        
        if ($users->isEmpty()) {
            $users = User::factory(3)->create(); // إنشاء 3 مستخدمين إذا لم يوجد أحد
        }

        // قائمة بالأدوار والأنشطة لجعل البيانات متنوعة
        $roles = ['User', 'Admin', 'Super Admin'];
        $activities = ['تسجيل دخول', 'تعديل ملف', 'حذف مستخدم', 'تحديث إعدادات', 'إضافة طلب'];
        $sections = ['لوحة التحكم', 'الإعدادات', 'المستخدمين', 'التقارير'];

        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random(); // اختيار مستخدم عشوائي من الموجودين
            
            Activity_logs::create([
                'user_id'       => $user->id,
                'user_role'     => Arr::random($roles), // اختيار دور عشوائي
                'user_email'    => $user->email,
                'activity_type' => Arr::random($activities),
                'description'   => 'قام المستخدم بإجراء عملية تجريبية رقم ' . $i,
                'section'       => Arr::random($sections),
                'date'          => Carbon::now()->subMinutes(rand(1, 1000)), // تواريخ متنوعة
            ]);
        }
    }
}