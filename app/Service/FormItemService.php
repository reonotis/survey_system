<?php

namespace App\Service;

use App\Models\FormItem;
use App\Models\FormSetting;
use App\Models\Survey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 *
 */
class FormItemService
{

    public function create(FormSetting $form_setting, array $param)
    {
        // 全ての項目で更新するカラム
        $base_param = [
            'form_setting_id' => $form_setting->id,
            'item_type' => $param['new_item_type'],
            'item_title' => $param['item_title'],
            'sort' => $form_setting->formItems->max('sort') + 1,
            'field_required' => isset($param['required']) ? 1 : 0,
            'annotation_text' => $param['annotation_text'] ?? '',
        ];

        // 項目ごとに更新可能とするカラム
        $create_param = array_merge($base_param, $this->makeUpdateParam($param['new_item_type'], $param));

        return FormItem::create($create_param);
    }

    /**
     * @param FormItem $form_item
     * @param int $form_type
     * @param array $param
     * @return bool
     */
    public function update(FormItem $form_item, int $form_type, array $param)
    {
        // 全ての項目で更新するカラム
        $base_param = [
            'item_title' => $param['item_title'],
            'field_required' => isset($param['field_required']) ? 1 : 0,
            'annotation_text' => $param['annotation_text'],
        ];

        // 項目ごとに更新可能とするカラム
        $update_param = array_merge($base_param, $this->makeUpdateParam($form_type, $param));

        // 更新処理
        return $form_item->update($update_param);
    }

    /**
     * @param int $type
     * @param array $param
     * @return array
     */
    private function makeUpdateParam(int $type, array $param): array
    {
        return match ($type) {
            FormItem::ITEM_TYPE_NAME => $this->makeUpdateParamForNames($param),
            FormItem::ITEM_TYPE_KANA => $this->makeUpdateParamForNames($param),
            FormItem::ITEM_TYPE_EMAIL => $this->makeUpdateParamForEmail($param),
            FormItem::ITEM_TYPE_TEL => $this->makeUpdateParamForTel($param),
            FormItem::ITEM_TYPE_GENDER => $this->makeUpdateParamForGender($param['gender_list']),
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeUpdateParamForCheckbox($param),
            FormItem::ITEM_TYPE_TERMS => $this->makeUpdateParamForTerms($param),
            default => [],
        };
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForNames(array $param): array
    {
        return [
            'details' => json_encode([
                'name_type' => $param['name_type']
            ]),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForEmail(array $param): array
    {
        return [
            'details' => json_encode([
                'confirm_flg' => $param['confirm_flg']
            ]),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForTel(array $param): array
    {
        return [
            'details' => json_encode([
                'hyphen_flg' => $param['hyphen_flg']
            ]),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForGender(array $gender_list): array
    {
        $gender_list_array = [];
        foreach($gender_list as $gender) {
            $gender_list_array[] = $gender;
        }

        return [
            'details' => json_encode([
                'gender_list' => $gender_list_array,
            ]),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForCheckbox(array $param): array
    {
        $raw = $param['checkbox_list'] ?? '';
        $lines = preg_split("/\r\n|\r|\n/", $raw);
        $options = array_values(array_filter(array_map('trim', $lines), function ($v) { return $v !== ''; }));

        return [
            'item_title' => $param['item_title'],
            'value_list' => json_encode($options),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForTerms(array $param): array
    {
        return [
            'item_title' => $param['item_title'],
            'value_list' => json_encode([$param['terms_text']]),
            'details' => json_encode([
                'consent_name' => $param['consent_name']
            ]),
        ];
    }
}

