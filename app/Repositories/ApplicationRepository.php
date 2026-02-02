<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Support\Facades\DB;

class ApplicationRepository
{

    public function getGenderData(int $form_setting_id)
    {
        return Application::select('gender as labels', DB::raw('COUNT(*) as count'))
            ->where('form_setting_id', $form_setting_id)
            ->groupBy('gender')
            ->get()->toArray();
    }

    public function getApplicationsCountByColumn(int $form_setting_id, string $column_name)
    {
        return Application::where('form_setting_id', $form_setting_id)
            ->whereNotNull($column_name)
            ->count();
    }
}
