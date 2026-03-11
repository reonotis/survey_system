<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AnalyticsDashboardRow;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsDashboardRowRepository
{
    /**
     * @param int $id
     * @return AnalyticsDashboardRow|null
     */
    public function getById(int $id): ?AnalyticsDashboardRow
    {
        return AnalyticsDashboardRow::find($id);
    }

    /**
     * @param int $form_setting_id
     * @return Collection<int, AnalyticsDashboardRow>
     */
    public function getByFormSettingId(int $form_setting_id): Collection
    {
        return AnalyticsDashboardRow::where('form_setting_id', $form_setting_id)
            ->orderBy('row_index')
            ->get();
    }

    /**
     * @param AnalyticsDashboardRow $analytics_row
     * @param array $param
     * @return bool
     */
    public function update(AnalyticsDashboardRow $analytics_row, array $param): bool
    {
        return $analytics_row->update($param);
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteById(int $id): int
    {
        return AnalyticsDashboardRow::destroy($id);
    }
}
