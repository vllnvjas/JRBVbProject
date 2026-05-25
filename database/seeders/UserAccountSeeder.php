<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $accounts = [
            [
                'name' => env('SEED_ADMIN_NAME', 'Deploy Admin'),
                'username' => env('SEED_ADMIN_USERNAME', 'deployadmin'),
                'email' => env('SEED_ADMIN_EMAIL', 'deployadmin@example.com'),
                'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'Admin@12345')),
                'role' => 'admin',
                'is_active' => 1,
                'must_change_password' => 0,
            ],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => 1,
                'must_change_password' => 0,
            ],
            [
                'name' => 'Teacher User',
                'username' => 'teacher',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'is_active' => 1,
                'must_change_password' => 0,
            ],
            [
                'name' => 'Student User',
                'username' => 'student',
                'email' => 'student@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'is_active' => 1,
                'must_change_password' => 0,
            ],
        ];

        foreach ($accounts as $data) {
            UserAccount::updateOrCreate(
                ['username' => $data['username']],
                $data
            );
        }
    }
}
