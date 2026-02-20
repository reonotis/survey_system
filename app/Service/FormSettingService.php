<?php

namespace App\Service;

use App\Models\FormSetting;
use App\Models\FormItem;
use App\Repositories\FormSettingRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FormSettingService
{
    private FormSettingRepository $form_setting_repository;

    public function __construct(
        FormSettingRepository $form_setting_repository,
    ) {
        $this->form_setting_repository = $form_setting_repository;
    }

    /**
     * @param string $route_name
     * @return FormSetting|null
     */
    public function getSurveyByRouteName(string $route_name): ?FormSetting
    {
        return FormSetting::where('route_name', $route_name)
            ->where('host_name',  request()->getHost())
            ->with('formItems')->first();
    }

    /**
     * @param int|null $user_id
     * @param string|null $form_name 管理名（部分一致）
     * @param array<int, int>|null $publication_statuses 状態（公開/非公開）
     * @return Builder
     */
    public function getFormListQuery(
        int $user_id = null,
        ?string $form_name = null,
        array $publication_statuses
    ): Builder {
        $select = [
            'form_settings.id',
            'form_settings.form_name',
            'form_settings.title',
            'form_settings.start_date',
            'form_settings.end_date',
            'form_settings.publication_status',
            'form_settings.created_by_user',
            'users.name AS owner_name',
            'form_settings.created_at',
        ];

        $query = FormSetting::select($select)
            ->where('host_name',  request()->getHost())
            ->join('users', 'users.id', '=', 'form_settings.created_by_user')
            ->addSelect([
                'has_active_subscription' => DB::table('form_subscriptions')
                    ->selectRaw('1')
                    ->whereColumn('form_subscriptions.form_setting_id', 'form_settings.id')
                    ->where('form_subscriptions.status', 'active')
                    ->where('form_subscriptions.current_period_end', '>', now())
                    ->whereNull('form_subscriptions.deleted_at')
                    ->limit(1),
            ])
            ->withCount('applications');

        if ($user_id !== null) {
            $query->where('created_by_user', $user_id);
        }

        if ($form_name !== null && $form_name !== '') {
            $query->where('form_settings.form_name', 'like', '%' . $form_name . '%');
        }

        if ($publication_statuses) {
            $query->whereIn('form_settings.publication_status', $publication_statuses);
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

    /**
     * @param FormSetting $form_setting
     * @return bool
     */
    public function delete(FormSetting $form_setting): bool
    {
        return $this->form_setting_repository->delete($form_setting);
    }

}
