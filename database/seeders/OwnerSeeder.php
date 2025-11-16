<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Owner;

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
