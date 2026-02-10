<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FormSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FormSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $host = request()->getHost();
        $now = Carbon::now();

        for ($i = 1; $i <= 100; $i++) {
            $records[] = [
                'host_name' => $host,
                'form_name' => "サンプルアンケート$i",
                'title' => "サンプルアンケート$i",
                'route_name' => "sample-form-$i",
                'admin_email' => 'admin@example.com',
                'start_date' => $now->copy()->subDays(3),
                'end_date' => $now->copy()->addDays(10),
                'max_applications' => 100,
                'image_directory' => "forms/sample-form-$i/images",
                'css_filename' => 'form-style.css',
                'banner_filename' => 'form-banner.jpg',
                'publication_status' => ($i === 1) ? 1 : 0,
                'created_by_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        FormSetting::insert($records);
    }
}
