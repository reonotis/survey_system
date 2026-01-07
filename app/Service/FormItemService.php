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
    public function addDraft(int $form_setting_id, int $item_type, int $sort)
    {
        // 詳細の初期値
        $details = match ($item_type) {
            FormItem::ITEM_TYPE_NAME => ['name_separate_type' => CommonConst::NAME_SEPARATE],
            FormItem::ITEM_TYPE_KANA => ['kana_separate_type' => CommonConst::KANA_SEPARATE],
            FormItem::ITEM_TYPE_EMAIL => ['confirm_type' => CommonConst::EMAIL_CONFIRM_ENABLED],
            FormItem::ITEM_TYPE_TEL => ['hyphen_type' => CommonConst::TEL_HYPHEN_USE],
            FormItem::ITEM_TYPE_GENDER => ['gender_list' => [1, 2]],
            FormItem::ITEM_TYPE_ADDRESS => ['use_post_code_type' => CommonConst::POST_CODE_DISABLED, 'address_separate_type' => CommonConst::ADDRESS_SEPARATE],
            FormItem::ITEM_TYPE_CHECKBOX => ['max_count' => null],
            FormItem::ITEM_TYPE_TERMS => ['label_name' => '同意する'],
            default => [],
        };

        return FormItemDraft::create([
            'form_setting_id' => $form_setting_id,
            'item_type' => $item_type,
            'field_required' => in_array($item_type, [FormItem::ITEM_TYPE_TERMS,]),
            'details' => $details,
            'long_text' => $this->setLongText($item_type),
            'sort' => $sort,
        ])->refresh();
    }

    /**
     * @param int $item_type
     * @return string|null
     */
    private function setLongText(int $item_type): ?string
    {
        return match ($item_type) {
            FormItem::ITEM_TYPE_TERMS => '利用規約が入ります。利用規約が入ります。',
            FormItem::ITEM_TYPE_PRECAUTIONS => '注意事項が入ります。注意事項が入ります。',
            default => null,
        };
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
            FormItem::ITEM_TYPE_TEL => $this->makeUpdateParamForTel($form_item, $target_key, $target_value),
            FormItem::ITEM_TYPE_GENDER => $this->makeUpdateParamForGender($form_item, $target_key, $target_value),
            FormItem::ITEM_TYPE_ADDRESS => $this->makeUpdateParamForAddress($form_item, $target_key, $target_value),

            FormItem::ITEM_TYPE_CHECKBOX,
            FormItem::ITEM_TYPE_RADIO,
            FormItem::ITEM_TYPE_SELECT_BOX,
            FormItem::ITEM_TYPE_TERMS,
            FormItem::ITEM_TYPE_PRECAUTIONS => $this->makeUpdateParamDefault($target_key, $target_value),
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
            $details = $form_item->details;
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
    private function makeUpdateParamForTel(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if ($target_key === 'hyphen_type') {
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
    private function makeUpdateParamForGender(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if ($target_key === 'gender_list') {
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
    private function makeUpdateParamForAddress(FormItemDraft $form_item, string $target_key, $target_value): array
    {
        // 特定の項目は、detailsカラムに格納するが、detailsに既に登録されている別の項目に影響を与えないようにする
        if (in_array($target_key, ['use_post_code_type', 'address_separate_list'])) {
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
     * @param string $target_key
     * @param $target_value
     * @return array
     */
    private function makeUpdateParamDefault(string $target_key, $target_value): array
    {
        return [
            $target_key => $target_value
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

