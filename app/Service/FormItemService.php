<?php

namespace App\Service;

use App\Consts\CommonConst;
use App\Models\FormItem;
use App\Models\FormSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 *
 */
class FormItemService
{

    public function create(FormSetting $form_setting, array $param)
    {
        // sortの値を決定（insert_indexが指定されている場合はinsert_index+1を使用、そうでなければ最大値+1）
        // insert_indexは0ベースのインデックス、sortは1ベースの値
        $sort = isset($param['insert_index'])
            ? $param['insert_index'] + 1
            : ($form_setting->formItems->max('sort') + 1);

        // 全ての項目で更新するカラム
        $base_param = [
            'form_setting_id' => $form_setting->id,
            'item_type' => $param['new_item_type'],
            'item_title' => $param['item_title'],
            'sort' => $sort,
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

    public function updateByFormItem(FormItem $form_item, string $target_key, $target_value)
    {
        $param = [$target_key => $target_value];
        $param = $this->makeUpdateParamReact($form_item, $target_key, $target_value);

        // 更新処理
        return $form_item->update($param);
    }

    /**
     * @param int $type
     * @param array $param
     * @return array
     */
    private function makeUpdateParamReact(FormItem $form_item, string $target_key, $target_value): array
    {
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_EMAIL => $this->makeUpdateParamForEmail($form_item, $target_key, $target_value),
            default => [],
        };
    }
    /**
     * @param int $type
     * @param array $param
     * @return array
     */
    private function makeUpdateParam(int $type, array $param): array
    {
        return match ($type) {
            FormItem::ITEM_TYPE_NAME => $this->makeUpdateParamForName($param),
            FormItem::ITEM_TYPE_KANA => $this->makeUpdateParamForKana($param),
            FormItem::ITEM_TYPE_EMAIL => $this->makeUpdateParamForEmail($param),
            FormItem::ITEM_TYPE_TEL => $this->makeUpdateParamForTel($param),
            FormItem::ITEM_TYPE_GENDER => $this->makeUpdateParamForGender($param['gender_list'] ?? []),
            FormItem::ITEM_TYPE_ADDRESS => $this->makeUpdateParamForAddress($param),
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeUpdateParamForCheckbox($param),
            FormItem::ITEM_TYPE_TERMS => $this->makeUpdateParamForTerms($param),
            default => [],
        };
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForName(array $param): array
    {
        return [
            'details' => json_encode([
                'name_type' => $param['name_type'] ?? CommonConst::NAME_SEPARATE,
            ]),
        ];
    }

    /**
     * @param array $param
     * @return array
     */
    private function makeUpdateParamForKana(array $param): array
    {
        return [
            'details' => json_encode([
                'name_type_kana' => $param['name_type_kana'] ?? CommonConst::KANA_SEPARATE,
            ]),
        ];
    }

    /**
     * @param FormItem $form_item
     * @param string $target_key
     * @param $target_value
     * @return array
     */
    private function makeUpdateParamForEmail(FormItem $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if ($target_key === 'confirm_flg') {
            $details = json_decode($form_item->details ?? '{}', true);
            $details[$target_key] = $target_value;

            return [
                'details' => $details,
            ];
        }

        return [
            $target_key => $target_value
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
                'hyphen_flg' => $param['hyphen_flg'] ?? CommonConst::TEL_HYPHEN_USE,
            ]),
        ];
    }

    /**
     * @param array $gender_list
     * @return array
     */
    private function makeUpdateParamForGender(array $gender_list): array
    {
        $gender_list_array = [];
        foreach ($gender_list as $gender) {
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
    private function makeUpdateParamForAddress(array $param): array
    {
        return [
            'details' => json_encode([
                'post_code_use_type' => $param['post_code_use_type'] ?? CommonConst::POST_CODE_DISABLED,
                'address_separate_type' => $param['address_separate_type'] ?? CommonConst::ADDRESS_SEPARATE,
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
        $options = array_values(array_filter(array_map('trim', $lines), function ($v) {
            return $v !== '';
        }));

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

