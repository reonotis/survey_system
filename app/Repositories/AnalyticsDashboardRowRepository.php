<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AnalyticsDashboardRow;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsDashboardRowRepository
{
    /**
     */
    public function getById(int $id)
    {
        return AnalyticsDashboardRow::find($id);
    }

    /**
     * @param int $form_setting_id
     * @return Collection
     */
    public function getByFormSettingId(int $form_setting_id): Collection
    {
        return AnalyticsDashboardRow::where('form_setting_id', $form_setting_id)
            ->orderBy('row_index')
            ->get();
    }

    /**
     * @param int $id
     */
    public function deleteById(int $id)
    {
        return AnalyticsDashboardRow::destroy($id);
    }
}
