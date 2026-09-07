<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $adminRole = Role::firstOrCreate(['nama_role' => 'admin']);
        $customerRole = Role::firstOrCreate(['nama_role' => 'customer']);
        $supplierRole = Role::firstOrCreate(['nama_role' => 'supplier']);

        User::updateOrCreate(
            ['email' => 'admin@ecommerce.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'email' => 'admin@ecommerce.com',
                'password' => Hash::make('password123'),
                'phone_number' => '081234567890',
            ]
        );
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'role_id' => $customerRole->id,
                'name' => 'Test Customer',
                'email' => 'customer@test.com',
                'password' => Hash::make('password123'),
                'phone_number' => '081234567891',
            ]
        );

        User::updateOrCreate(
            ['email' => 'supplier@test.com'],
            [
                'role_id' => $supplierRole->id,
                'name' => 'Test Supplier',
                'email' => 'supplier@test.com',
                'password' => Hash::make('password123'),
                'phone_number' => '081234567892',
            ]
        );
        $this->call(ProductSeeder::class);

        $this->call(TransactionSeeder::class);
    }
}