<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'ユーザー',
            'email' => 'user@test.jp',
            'host' => 'localhost',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
