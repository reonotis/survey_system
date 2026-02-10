<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        DB::table('users')->truncate();
        DB::table('admins')->truncate();
        DB::table('form_settings')->truncate();
        DB::table('form_items')->truncate();

        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
//            OwnerSeeder::class,
            FormSettingSeeder::class,
            FormItemsSeeder::class,
        ]);
    }
}
