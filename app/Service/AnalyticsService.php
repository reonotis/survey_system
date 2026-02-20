<?php

declare(strict_types=1);

namespace App\Service;

use App\Consts\CommonConst;
use App\Models\AnalyticsDashboardRow;
use App\Models\AnalyticsDashboardWidget;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Repositories\AnalyticsDashboardRowRepository;
use App\Repositories\AnalyticsDashboardWidgetRepository;
use App\Repositories\ApplicationRepository;
use App\Repositories\ApplicationSubRepository;
use App\Repositories\FormItemRepository;
use App\Traits\AnalyticsTrait;
use App\Traits\FormParamChangerTrait;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsService
{
    use AnalyticsTrait;
    use FormParamChangerTrait;

    private AnalyticsDashboardRowRepository $analytics_dashboard_row_repository;
    private AnalyticsDashboardWidgetRepository $analytics_dashboard_widget_repository;
    private ApplicationRepository $application_repository;
    private ApplicationSubRepository $application_sub_repository;
    private FormItemRepository $form_iItem_repository;

    public function __construct(
        AnalyticsDashboardRowRepository    $analytics_dashboard_row_repository,
        AnalyticsDashboardWidgetRepository $analytics_dashboard_widget_repository,
        ApplicationRepository              $application,
        ApplicationSubRepository           $application_sub,
        FormItemRepository                 $form_item
    )
    {
        $this->analytics_dashboard_row_repository = $analytics_dashboard_row_repository;
        $this->analytics_dashboard_widget_repository = $analytics_dashboard_widget_repository;
        $this->application_repository = $application;
        $this->application_sub_repository = $application_sub;
        $this->form_iItem_repository = $form_item;
    }

    /**
     * @param int $form_setting_id
     * @param int $total_count
     * @return array
     */
    public function getByFormSettingId(int $form_setting_id, int $total_count): array
    {
        // 行データを取得
        $rows = $this->analytics_dashboard_row_repository->getByFormSettingId($form_setting_id);
        $analytics_list = $rows->toArray();

        // 行データに結びつくウィジェットのIDを取得
        $widget_ids = $this->getWidgetIds($rows);

        // idを基にウィジェットの設定データを取得
        $analytics_widget_list = $this->analytics_dashboard_widget_repository->getByIds($form_setting_id, $widget_ids);

        // 分析データを集計
        $analytics_data = [];
        foreach ($analytics_widget_list as $analytics_widget) {
            $analytics_data[$analytics_widget['id']] = $this->getAnalyticsData($analytics_widget, $total_count);
        }

        // 集計した分析データを紐づけて返却する
        return $this->setAnalyticsData($analytics_list, $analytics_data);
    }

    /**
     * @param int $id
     * @return ?AnalyticsDashboardRow
     */
    public function getAnalyticsRowById(int $id): ?AnalyticsDashboardRow
    {
        return AnalyticsDashboardRow::find($id);
    }

    /**
     * @param int $form_setting_id
     * @param array $request
     * @return AnalyticsDashboardRow
     */
    public function createDashboardRows(int $form_setting_id, array $request): AnalyticsDashboardRow
    {
        // 既存の行を取得
        $rows = AnalyticsDashboardRow::where('form_setting_id', $form_setting_id)->get();

        // 新しい行を登録して返却
        return AnalyticsDashboardRow::create([
            'form_setting_id' => $form_setting_id,
            'row_index' => $rows->count() + 1,
            'layout_type' => $request['layout_type'],
            'layout_definition' => CommonConst::WIDGET_TYPE_COLUMN[$request['layout_type']],
        ]);
    }

    /**
     * @param array $param
     * @return AnalyticsDashboardWidget
     */
    public function create(array $param): AnalyticsDashboardWidget
    {
        return AnalyticsDashboardWidget::create($param);
    }

    /**
     * @param FormSetting $form_setting
     * @return array
     */
    public function makeSelectableFormItems(FormSetting $form_setting): array
    {
        return $form_setting->formItems->map(function ($item) {
            return [
                'id' => $item->id,
                'item_type' => $item->item_type,
                'field_required' => $item->field_required,
                'item_title' => $item->item_title ?? $item->item_type->label(),
                'value_list' => $item->value_list,
                'details' => $item->details,
            ];
        })->toArray();
    }

    /**
     * @param AnalyticsDashboardRow $analytics_row
     * @param array $param
     * @return bool
     */
    public function updateAnalyticsRow(AnalyticsDashboardRow $analytics_row, array $param): bool
    {
        return $analytics_row->update($param);
    }

    /**
     * @param int $form_setting_id
     * @param int $id
     * @return void
     */
    public function dashboardRowDelete(int $form_setting_id, int $id): void
    {
        // 行データを取得
        $analytics_dashboard_row = $this->analytics_dashboard_row_repository->getById($id);

        // 紐づくウィジェットのIDを取得
        $widget_ids = [
            $analytics_dashboard_row->analytics_dashboard_widget_id_1,
            $analytics_dashboard_row->analytics_dashboard_widget_id_2,
            $analytics_dashboard_row->analytics_dashboard_widget_id_3,
            $analytics_dashboard_row->analytics_dashboard_widget_id_4,
        ];

        // ウィジェットの削除
        $this->analytics_dashboard_widget_repository->deleteByIds($widget_ids);

        // 行データの削除
        $this->analytics_dashboard_row_repository->deleteById($id);

        // indexを再度割り振り
        $this->reindex($form_setting_id);
    }


    /**
     * @param int $form_setting_id
     * @return void
     */
    private function reindex(int $form_setting_id): void
    {
        $analytics_row_list = $this->analytics_dashboard_row_repository->getByFormSettingId($form_setting_id);

        $index = 1;
        foreach ($analytics_row_list as $analytics_row) {
            $this->updateAnalyticsRow($analytics_row, [
                'row_index' => $index,
            ]);
            $index++;
        }
    }

    /**
     * @param Collection $rows
     * @return array
     */
    private function getWidgetIds(Collection $rows): array
    {
        return $rows
            ->flatMap(fn($row) => [
                $row->analytics_dashboard_widget_id_1,
                $row->analytics_dashboard_widget_id_2,
                $row->analytics_dashboard_widget_id_3,
                $row->analytics_dashboard_widget_id_4,
                $row->analytics_dashboard_widget_id_5,
                $row->analytics_dashboard_widget_id_6,
            ])->filter()->unique()->values()->toArray();
    }

    /**
     * @param array $analytics_list
     * @param array $analytics_data
     * @return array
     */
    private function setAnalyticsData(array $analytics_list, array $analytics_data): array
    {
        foreach ($analytics_list as &$row) {
            if ($row['analytics_dashboard_widget_id_1']) {
                $row['analytics_dashboard_widget_id_1'] = $analytics_data[$row['analytics_dashboard_widget_id_1']];
            }
            if ($row['analytics_dashboard_widget_id_2']) {
                $row['analytics_dashboard_widget_id_2'] = $analytics_data[$row['analytics_dashboard_widget_id_2']];
            }
            if ($row['analytics_dashboard_widget_id_3']) {
                $row['analytics_dashboard_widget_id_3'] = $analytics_data[$row['analytics_dashboard_widget_id_3']];
            }
            if ($row['analytics_dashboard_widget_id_4']) {
                $row['analytics_dashboard_widget_id_4'] = $analytics_data[$row['analytics_dashboard_widget_id_4']];
            }
            if ($row['analytics_dashboard_widget_id_5']) {
                $row['analytics_dashboard_widget_id_5'] = $analytics_data[$row['analytics_dashboard_widget_id_5']];
            }
            if ($row['analytics_dashboard_widget_id_6']) {
                $row['analytics_dashboard_widget_id_6'] = $analytics_data[$row['analytics_dashboard_widget_id_6']];
            }
        }

        return $analytics_list;
    }

}











