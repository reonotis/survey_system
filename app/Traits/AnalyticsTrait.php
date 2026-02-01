<?php

namespace App\Traits;

use App\Consts\CommonConst;
use App\Models\AnalyticsDashboardWidget;
use App\Models\FormItem;

trait AnalyticsTrait
{
    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @param int $total_count
     * @return array
     */
    private function getAnalyticsData(AnalyticsDashboardWidget $analytics_widget, int $total_count): array
    {
        $data = match ($analytics_widget->display_type) {
            CommonConst::GRAPH_TYPE_TOTAL => $total_count,
            CommonConst::GRAPH_TYPE_RATE => $this->getRateData($analytics_widget, $total_count),
            CommonConst::GRAPH_TYPE_CIRCLE => $this->getCircleData($analytics_widget),
            CommonConst::GRAPH_TYPE_VERTICAL => $this->getVerticalData($analytics_widget),
            default => [],
        };

        return [
            'title' => $this->getTitle($analytics_widget),
            'widget_setting' => $analytics_widget,
            'analytics_data' => $data,
        ];
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return string
     */
    private function getTitle(AnalyticsDashboardWidget $analytics_widget): string
    {
        $type = CommonConst::GRAPH_TYPE_LIST[$analytics_widget->display_type];

        // 総件数
        if ($analytics_widget->display_type === CommonConst::GRAPH_TYPE_TOTAL) {
            return $type;
        }

        $title = $analytics_widget->item_title ?? FormItem::ITEM_TYPE_LIST[$analytics_widget->item_type];

        return $title . ' (' . $type . ')';
    }

    /**
     * 項目毎の登録率を返却する
     * @param AnalyticsDashboardWidget $analytics_widget
     * @param $total_count
     * @return mixed
     */
    private function getRateData(AnalyticsDashboardWidget $analytics_widget, $total_count): mixed
    {
        return match ($analytics_widget->item_type) {
            FormItem::ITEM_TYPE_GENDER => $this->getGenderRateData($analytics_widget, $total_count),
            default => [],
        };
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return array
     */
    private function getCircleData(AnalyticsDashboardWidget $analytics_widget): array
    {
        return match ($analytics_widget->item_type) {
            FormItem::ITEM_TYPE_GENDER => $this->getGenderData($analytics_widget),
            default => [],
        };

    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return array
     */
    private function getVerticalData(AnalyticsDashboardWidget $analytics_widget): array
    {
        return match ($analytics_widget->item_type) {
            FormItem::ITEM_TYPE_GENDER => $this->getGenderData($analytics_widget),
            default => [],
        };
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return array
     */
    private function getGenderData(AnalyticsDashboardWidget $analytics_widget): array
    {

        if (in_array($analytics_widget->display_type, [CommonConst::GRAPH_TYPE_CIRCLE, CommonConst::GRAPH_TYPE_VERTICAL,])) {
            $data = $this->application_repository->getGenderData($analytics_widget->form_setting_id);

            $array = [
                'labels' => array_column($data, 'labels'),
                'count' => array_column($data, 'count'),
            ];

            return [
                'labels' => array_map(
                    fn($gender) => CommonConst::GENDER_LIST[$gender] ?? '未回答',
                    $array['labels']
                ),
                'count' => $array['count'],
            ];
        }

        return [];
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @param int $total_count
     * @return float
     */
    private function getGenderRateData(AnalyticsDashboardWidget $analytics_widget, int $total_count): float
    {
        $gender_count = $this->application_repository->getApplicationsByGenderCount($analytics_widget->form_setting_id);
        return round(($gender_count / $total_count) * 100, 2);

    }
}
