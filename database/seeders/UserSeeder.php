<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),

            'status' => 'active',

            'last_login_at' => now(),

            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => Hash::make('password'),

            'status' => 'inactive',

            'last_login_at' => now()->subDays(3),

            'email_verified_at' => now(),
        ]);

    }
}