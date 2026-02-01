<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ApplicationRepository
{
    public function getCountByFormSettingId(int $form_setting_id): int
    {
        return Application::where('form_setting_id', $form_setting_id)->count();
    }

    public function getGenderData(int $form_setting_id)
    {
        return Application::select('gender as labels', DB::raw('COUNT(*) as count'))
            ->where('form_setting_id', $form_setting_id)
            ->groupBy('gender')
            ->get()->toArray();
    }

    public function getApplicationsByGenderCount(int $form_setting_id)
    {
        return Application::where('form_setting_id', $form_setting_id)
            ->whereNotNull('gender')
            ->count();
    }
}
