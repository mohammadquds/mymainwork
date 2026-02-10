<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity_logs;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'omar',
            'email' => 'omarmetwaly888@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        Activity_logs::create([
            'user_id' => $user->id,
            'user_role' => 'admin',
            'user_email' => $user->email,
            'activity_type' => 'تسجيل دخول',
            'description' => 'قام الموظف بتسجيل الدخول للنظام',
            'section' => 'لوحة التحكم',
            'date' => Carbon::now(),
        ]);

        Activity_logs::create([
            'user_id' => $user->id,
            'user_role' => 'admin',
            'user_email' => $user->email,
            'activity_type' => 'تعديل بيانات',
            'description' => 'تعديل الملف الشخصي',
            'section' => 'الإعدادات',
            'date' => Carbon::now()->subHours(2),
        ]);    }
}
