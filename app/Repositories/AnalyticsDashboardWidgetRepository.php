<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AnalyticsDashboardRow;
use App\Models\AnalyticsDashboardWidget;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsDashboardWidgetRepository
{
    /**
     */
    public function getByIds(int $form_setting_id, array $ids): Collection
    {
        return AnalyticsDashboardWidget::select('analytics_dashboard_widget.*', 'form_items.item_title', 'form_items.item_type')
            ->where('analytics_dashboard_widget.form_setting_id', $form_setting_id)
            ->whereIn('analytics_dashboard_widget.id', $ids)
            ->join('form_items', 'form_items.id', '=', 'analytics_dashboard_widget.form_item_id')
            ->get();
    }

    /**
     * @param array $widget_ids
     */
    public function deleteByIds(array $widget_ids)
    {
        return AnalyticsDashboardWidget::whereIn('id', $widget_ids)->delete();
    }
}
