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
            default => false,
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
     * @return string|float
     */
    private function getRateData(AnalyticsDashboardWidget $analytics_widget, $total_count): string|float
    {
        return match ($analytics_widget->item_type) {
            FormItem::ITEM_TYPE_NAME => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'name'),
            FormItem::ITEM_TYPE_KANA => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'kana'),
            FormItem::ITEM_TYPE_EMAIL => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'email'),
            FormItem::ITEM_TYPE_TEL => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'tel'),
            FormItem::ITEM_TYPE_GENDER => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'gender'),
            FormItem::ITEM_TYPE_ADDRESS => $this->getInputRateByTargetItem($analytics_widget, $total_count, 'address'),
            default => '調整中',
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
            FormItem::ITEM_TYPE_CHECKBOX,
            FormItem::ITEM_TYPE_RADIO,
            FormItem::ITEM_TYPE_SELECT_BOX
                => $this->getCheckboxCircleData($analytics_widget),
            default => ['調整中'],
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
            FormItem::ITEM_TYPE_CHECKBOX,
            FormItem::ITEM_TYPE_RADIO,
            FormItem::ITEM_TYPE_SELECT_BOX
                => $this->getCheckboxCircleData($analytics_widget),
            default => ['調整中'],
        };
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return array
     */
    private function getGenderData(AnalyticsDashboardWidget $analytics_widget): array
    {
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

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @return array
     */
    private function getCheckboxCircleData(AnalyticsDashboardWidget $analytics_widget): array
    {
        $data = $this->application_sub_repository->getSelectionsItemCount($analytics_widget->form_item_id);

        return [
            'labels' => array_column($data, 'labels'),
            'count' => array_column($data, 'count'),
        ];
    }

    /**
     * @param AnalyticsDashboardWidget $analytics_widget
     * @param int $total_count
     * @param string $column_name
     * @return float
     */
    private function getInputRateByTargetItem(AnalyticsDashboardWidget $analytics_widget, int $total_count, string $column_name): float
    {
        $gender_count = $this->application_repository->getApplicationsCountByColumn($analytics_widget->form_setting_id, $column_name);
        return round(($gender_count / $total_count) * 100, 2);

    }
}
