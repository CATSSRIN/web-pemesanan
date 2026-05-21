<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $staff = User::create([
            'name' => 'Staff Sales',
            'email' => 'staff@admin.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $staff->assignRole('staff');
    }
}
