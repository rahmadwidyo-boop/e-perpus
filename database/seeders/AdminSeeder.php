<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eperpus.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@eperpus.com',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
