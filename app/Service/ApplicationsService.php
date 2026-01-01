<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Application;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Traits\FormParamChangerTrait;
use Illuminate\Database\Eloquent\Builder;

class ApplicationsService
{
    use FormParamChangerTrait;

    public function getApplications(int $form_setting_id)
    {
        return Application::where('form_setting_id', $form_setting_id)->count();
    }

    /**
     * @param FormSetting $form_setting
     * @param array $request_data
     * @return Application
     */
    public function register(FormSetting $form_setting, array $request_data): Application
    {
        $param = [
            'form_setting_id' => $form_setting->id,
        ];

        foreach ($form_setting->formItems as $form_item) {
            $param = array_merge($param, $this->makeParamByItemType($form_item, $request_data));
        }

        return Application::create($param);
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
            ->where('form_setting_id', $form_setting_id);

        if (!isset($request_data['order'])) {
            $query = $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

}











