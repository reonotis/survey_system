<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Application;
use App\Models\ApplicationSub;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Traits\FormParamChangerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ApplicationsService
{
    use FormParamChangerTrait;

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
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_NAME => $this->makeParamForName($form_item->details, $request_data),
            FormItem::ITEM_TYPE_KANA => $this->makeParamForKana($form_item->details, $request_data),
            FormItem::ITEM_TYPE_EMAIL => $this->makeParamForEmail($request_data),
            FormItem::ITEM_TYPE_TEL => $this->makeParamForTel($request_data),
            FormItem::ITEM_TYPE_GENDER => $this->makeParamForGender($request_data),
            FormItem::ITEM_TYPE_ADDRESS => $this->makeParamForAddress($form_item->details, $request_data),

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
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeParamForCheckbox($application_id, $form_item, $request_data),
            FormItem::ITEM_TYPE_RADIO => $this->makeParamForRadio($application_id, $form_item, $request_data),

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
     * @param $form_setting_id
     * @return array
     */
    public function getSelectMaxCount($form_setting_id): array
    {
        $records = ApplicationSub::query()
            ->join('applications', 'applications.id', '=', 'application_sub.application_id')
            ->where('applications.form_setting_id', $form_setting_id)
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
            $formItemId = $row->form_item_id;
            $answerText = $row->answer_text;
            $total      = $row->total;

            $data[$formItemId][$answerText] = $total;
        }

        return $data;
    }

}











