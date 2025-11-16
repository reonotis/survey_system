<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormSetting;
use Carbon\Carbon;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormSetting::create([
            'host_name' => 'example.com',
            'form_name' => 'サンプルアンケート',
            'title' => 'サンプルアンケート',
            'route_name' => 'sample-form',
            'admin_email' => 'admin@example.com',
            'start_date' => Carbon::now()->subDays(),
            'end_date' => Carbon::now()->addDays(10),
            'max_applications' => 100,
            'image_directory' => 'forms/sample-form/images',
            'css_filename' => 'form-style.css',
            'banner_filename' => 'form-banner.jpg',
        ]);
    }
}
