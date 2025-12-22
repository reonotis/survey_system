<?php

namespace App\Service;

use App\Consts\CommonConst;
use App\Models\FormItem;
use App\Models\FormItemDraft;
use App\Models\FormSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 *
 */
class FormItemService
{
    public function addDraft(int $form_setting_id, int $item_type)
    {
        $details = match ($item_type) {
            FormItem::ITEM_TYPE_NAME => json_encode(['name_separate_type' => (string)CommonConst::NAME_SEPARATE]),
            FormItem::ITEM_TYPE_KANA => json_encode(['kana_separate_type' => (string)CommonConst::KANA_SEPARATE]),
            default => json_encode([]),
        };

        return FormItemDraft::create([
            'form_setting_id' => $form_setting_id,
            'item_type' => $item_type,
            'details' => $details,
        ])->refresh();
    }

    public function insertDraft(array $records)
    {
        return FormItemDraft::insert($records);
    }

    public function sortChangeDraft(int $form_item_drafts_id, int $sort): bool
    {
        return FormItemDraft::where('id', $form_item_drafts_id)
                ->update([
                    'sort' => $sort,
                ]) > 0;
    }

    public function create(array $param)
    {
        return FormItem::create($param);
    }

    /**
     * @param int $form_item_id
     * @param array $param
     * @return bool
     */
    public function updateFormItemById(int $form_item_id, array $param)
    {
        $form_item = FormItem::find($form_item_id);

        return $form_item->update($param);
    }

    /**
     * @param int $form_item_id
     * @param string $target_key
     * @param $target_value
     */
    public function updateByFormItem(int $form_item_id, string $target_key, $target_value)
    {
        $form_item_draft = FormItemDraft::find($form_item_id);
        $param = $this->makeUpdateParamReact($form_item_draft, $target_key, $target_value);

        // 更新処理
        return $form_item_draft->update($param);
    }

    /**
     * @param int $form_item_id
     */
    public function deleteDraftItem(int $form_item_id)
    {
         return FormItemDraft::where('id', $form_item_id)->delete();
    }

    /**
     */
    private function makeUpdateParamReact(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_NAME => $this->makeUpdateParamForName($form_item, $target_key, $target_value),
            FormItem::ITEM_TYPE_KANA => $this->makeUpdateParamForKana($form_item, $target_key, $target_value),
            FormItem::ITEM_TYPE_EMAIL => $this->makeUpdateParamForEmail($form_item, $target_key, $target_value),
            default => [],
        };
    }

    /**
     * @param FormItemDraft $form_item
     * @param string $target_key
     * @param $target_value
     * @return array
     */
    private function makeUpdateParamForName(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if ($target_key === 'name_separate_type') {
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
     * @param FormItemDraft $form_item
     * @param string $target_key
     * @param $target_value
     * @return array
     */
    private function makeUpdateParamForKana(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if ($target_key === 'kana_separate_type') {
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
     * @param FormItemDraft $form_item
     * @param string $target_key
     * @param $target_value
     * @return array
     */
    private function makeUpdateParamForEmail(FormItemDraft $form_item, string $target_key, $target_value): array
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

    /**
     * @param int $form_setting_id
     * @param array $updated_ids
     * @return void
     */
    public function deleteDraftFormItems(int $form_setting_id, array $updated_ids)
    {
        FormItem::where('form_setting_id', $form_setting_id)
            ->whereNotIn('id', $updated_ids)
            ->delete();
    }
}

