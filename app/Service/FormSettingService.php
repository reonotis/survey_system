<?php

namespace App\Service;

use App\Models\FormSetting;
use App\Models\FormItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FormSettingService
{
    /**
     * @param string $route_name
     * @return FormSetting|null
     */
    public function getSurveyByRouteName(string $route_name): ?FormSetting
    {
        return FormSetting::where('route_name', $route_name)->with('formItems')->first();
    }

    /**
     * @param int|null $user_id
     * @return Builder
     */
    public function getFormListQuery(int $user_id = null): Builder
    {
        $select = [
            'id',
            'form_name',
            'title',
            'start_date',
            'end_date',
            'publication_status',
            'created_at',
        ];

        $query = FormSetting::select($select)
            ->withCount('applications');

        $query->where('host_name',  request()->getHost());

        if ($user_id) {
            $query->where('created_by_user', $user_id);
        }

        return $query;
    }

    /**
     * @param array $param
     * @param string $host_name
     * @return FormSetting
     */
    public function create(array $param, string $host_name): FormSetting
    {
        return FormSetting::create([
            'host_name' => $host_name,
            'form_name' => $param['form_name'],
            'title' => $param['title'],
            'route_name' => $param['route_name'] ?? bin2hex(random_bytes(16)),
            'admin_email' => 'admin@test.com',
            'start_date' => $param['start_date'] ?? null,
            'end_date' => $param['end_date'] ?? null,
            'max_applications' => $param['max_applications'] ?? null,
            'image_directory' => $param['image_directory'] ?? null,
            'css_filename' => $param['css_filename'] ?? null,
            'banner_filename' => $param['banner_filename'] ?? null,
            'publication_status' => $param['publication_status'] ?? 0,
            'created_by_admin' => $param['created_by_admin'] ?? null,
            'created_by_user' => $param['created_by_user'] ?? null,
        ]);
    }

    /**
     * 登録可能な項目の一覧を返す。
     * @param Collection $formItems
     * @return array
     */
    public function getSelectableItemList(FormSetting $form_setting)
    {
        $form_items = $form_setting->formItems;
        $draft_form_items = $form_setting->draftFormItems;
        $merged = $form_items
            ->merge($draft_form_items)
            ->sortBy('sort')
            ->values(); // インデックスを振り直す

        return $merged;

        // 現在登録されている各 item_type の件数を集計
        $current_counts_type = $form_items
            ->pluck('item_type')
            ->countBy()
            ->toArray();

        // 全ての項目候補と上限値を取得
        $all_form_item_list = FormItem::ITEM_TYPE_LIST;
        $upper_limit_item_type = FormItem::ITEM_TYPE_UPPER_LIMIT;

        // 上限に達していない型のみを残す
        $available = [];
        foreach ($all_form_item_list as $type => $label) {
            $limit = $upper_limit_item_type[$type] ?? null;  // null → 無制限
            $current_count = $current_counts_type[$type] ?? 0;

            // 無制限、または未達なら選択可
            if ($limit === null || $current_count < $limit) {
                $available[$type] = $label;
            }
        }

        return $available;
    }

    /**
     * @param FormSetting $form_setting
     * @param array $param
     * @return bool
     */
    public function update(FormSetting $form_setting, array $param)
    {
        return $form_setting->update($param);
    }

}
