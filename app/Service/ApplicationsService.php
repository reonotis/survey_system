<?php

declare(strict_types=1);

namespace App\Service;

use App\Enums\ItemType;
use App\Models\Application;
use App\Models\ApplicationSub;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Repositories\ApplicationRepository;
use App\Repositories\ApplicationSubRepository;
use App\Traits\FormParamChangerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ApplicationsService
{
    use FormParamChangerTrait;

    private ApplicationRepository $application_repository;
    private ApplicationSubRepository $application_sub_repository;

    public function __construct(
        ApplicationRepository    $application,
        ApplicationSubRepository $application_sub,
    )
    {
        $this->application_repository = $application;
        $this->application_sub_repository = $application_sub;
    }


    public function getApplicationCount(int $form_setting_id)
    {
        return Application::where('form_setting_id', $form_setting_id)->count();
    }

    /**
     * @param FormSetting $form_setting
     * @param array $request_data
     */
    public function register(FormSetting $form_setting, array $request_data)
    {
        // Applicationテーブルへ登録
        $param = [
            'form_setting_id' => $form_setting->id,
        ];

        foreach ($form_setting->formItems as $form_item) {
            $param = array_merge($param, $this->makeParamByItemType($form_item, $request_data));
        }
        $application = Application::create($param);

        // その他のテーブルへ登録
        $application_id = $application->id;
        $records = [];
        foreach ($form_setting->formItems as $form_item) {
            $records = array_merge($records, $this->applicationSubRecord($application_id, $form_item, $request_data));
        }

        ApplicationSub::insert($records);
    }

    /**
     * @param FormItem $form_item
     * @param array $request_data
     * @return array
     */
    private function makeParamByItemType(FormItem $form_item, array $request_data): array
    {
        return match ($form_item->item_type->value) {
            ItemType::NAME->value => $this->makeParamForName($form_item->details, $request_data),
            ItemType::KANA->value => $this->makeParamForKana($form_item->details, $request_data),
            ItemType::EMAIL->value => $this->makeParamForEmail($request_data),
            ItemType::TEL->value => $this->makeParamForTel($request_data),
            ItemType::GENDER->value => $this->makeParamForGender($request_data),
            ItemType::ADDRESS->value => $this->makeParamForAddress($form_item->details, $request_data),

            default => [],
        };
    }

    /**
     * @param int $application_id
     * @param FormItem $form_item
     * @param array $request_data
     * @return array
     */
    private function applicationSubRecord(int $application_id, FormItem $form_item, array $request_data): array
    {
        return match ($form_item->item_type->value) {
            ItemType::CHECKBOX->value => $this->makeParamForCheckbox($application_id, $form_item, $request_data),
            ItemType::RADIO->value => $this->makeParamForSelection($application_id, $form_item, $request_data, 'radio_'),
            ItemType::SELECT_BOX->value => $this->makeParamForSelection($application_id, $form_item, $request_data, 'select_box_'),
            default => [],
        };
    }

    /**
     *
     * @param int $form_setting_id
     * @param array $request_data
     * @return Builder
     */
    public function getFormListQuery(int $form_setting_id, array $request_data): Builder
    {
        $select = [
            'id',
            'name',
            'name_last',
            'kana',
            'kana_last',
            'email',
            'tel',
            'gender',
            'post_code',
            'address',
            'created_at',
        ];

        $query = Application::select($select)
            ->where('form_setting_id', $form_setting_id)
            ->with('applicationSubs.formItem');

        if (!isset($request_data['order'])) {
            $query = $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    /**
     * 既に登録されている項目が、どれだけ選択されていたのかを取得する
     * @param FormSetting $form_setting
     * @return array
     */
    public function getSelectedCount(FormSetting $form_setting): array
    {
        $records = ApplicationSub::query()
            ->join('applications', 'applications.id', '=', 'application_sub.application_id')
            ->where('applications.form_setting_id', $form_setting->id)
            ->whereNull('application_sub.deleted_at')
            ->select([
                'application_sub.form_item_id',
                'application_sub.answer_text',
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('application_sub.form_item_id', 'application_sub.answer_text')
            ->orderBy('application_sub.form_item_id')
            ->orderByDesc('total')
            ->get();

        $data = [];
        foreach ($records as $row) {
            $form_item_id = $row->form_item_id;
            $answer_text = $row->answer_text;
            $total = $row->total;

            $data[$form_item_id][$answer_text] = $total;
        }

        return $data;
    }

    /**
     * 項目別上限値 が設定されているかチェックする
     * @param FormSetting $form_setting
     * @return bool
     */
    public function checkMaxSetting(FormSetting $form_setting): bool
    {
        foreach ($form_setting->formItems as $form_items) {
            // value_listが設定されてい無い場合は、スルーして次の項目を確認させる
            if (is_null($form_items->value_list)) {
                continue;
            }

            foreach ($form_items->value_list as $value_item) {
                if (is_null($value_item['count'])) {
                    return true;
                }
            }
        }

        return false;
    }

}











