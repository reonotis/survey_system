<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => '管理者',
            'email' => 'admin@test.jp',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
