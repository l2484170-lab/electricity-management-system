<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@electricity.local',
            'full_name' => 'مدير النظام',
            'password' => 'admin123',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'accountant',
            'email' => 'accountant@electricity.local',
            'full_name' => 'المحاسب الرئيسي',
            'password' => 'account123',
            'role' => 'accountant',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'reader',
            'email' => 'reader@electricity.local',
            'full_name' => 'قارئ العدادات',
            'password' => 'reader123',
            'role' => 'meter_reader',
            'is_active' => true,
        ]);

        User::create([
            'username' => 'cs',
            'email' => 'cs@electricity.local',
            'full_name' => 'موظف خدمة العملاء',
            'password' => 'cs123456',
            'role' => 'customer_service',
            'is_active' => true,
        ]);
    }
}
