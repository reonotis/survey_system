<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Owner::create([
            'name' => 'オーナー',
            'email' => 'owner@test.jp',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
