<?php

namespace App\Service;

use App\Consts\CommonConst;
use App\Models\Application;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Traits\FormParamChangerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ApplicationsService
{
    use FormParamChangerTrait;

    /**
     * @param FormSetting $form_setting
     * @param array $request_data
     * @return void
     */
    public function register(FormSetting $form_setting, array $request_data)
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
            FormItem::ITEM_TYPE_NAME => $this->makeParamForName(json_decode($form_item->details, true), $request_data),
            FormItem::ITEM_TYPE_KANA => $this->makeParamForKana(json_decode($form_item->details, true), $request_data),
            FormItem::ITEM_TYPE_EMAIL => $this->makeParamForEmail($request_data),
            default => [],
        };
    }


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
            'created_at',
        ];

        $query = Application::select($select)
            ->where('form_setting_id', $form_setting_id);

        if (!isset($request_data['order'])) {
            $query = $query->orderBy('created_at', 'desc');
        };

        return $query;
    }

}











