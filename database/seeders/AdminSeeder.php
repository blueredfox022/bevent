<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'zulkfilisaleh022@gmail.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);
    }
}
