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
        $host = request()->getHost();

        User::insert([
            [
                'name' => 'ユーザー1',
                'email' => 'user@test.jp',
                'host' => $host,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],[
                'name' => 'ユーザー2',
                'email' => 'user2@test.jp',
                'host' => $host,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        ]);
    }
}
