<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Schema::disableForeignKeyConstraints();

        DB::table('subscriptions')->truncate();
        DB::table('form_subscriptions')->truncate();
        DB::table('form_items')->truncate();
        DB::table('form_settings')->truncate();
        DB::table('users')->truncate();
        DB::table('admins')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
//            OwnerSeeder::class,
            FormSettingSeeder::class,
            FormItemsSeeder::class,
        ]);
    }
}
