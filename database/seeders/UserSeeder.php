<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role ada
        $adminRole = Role::firstOrCreate(['nama_role' => 'admin']);
        $userRole  = Role::firstOrCreate(['nama_role' => 'user']);

        // ===== AKUN ADMIN =====
        $admin = User::firstOrCreate(
            ['email' => 'admin@cianjurfresh.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'phone_number' => '081234567890',
            ]
        );
        $admin->role_id = $adminRole->id;
        $admin->save();

        // ===== AKUN USER DEMO =====
        $user = User::firstOrCreate(
            ['email' => 'user@cianjurfresh.com'],
            [
                'name' => 'User Demo',
                'password' => Hash::make('user123'),
                'phone_number' => '081234567891',
            ]
        );
        $user->role_id = $userRole->id;
        $user->save();
    }
}