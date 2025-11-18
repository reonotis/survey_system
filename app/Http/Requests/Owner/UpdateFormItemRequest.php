<?php

namespace App\Http\Requests\Owner;

use App\Models\FormItem;
use App\Consts\CommonConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int|string $item_type
 */
class UpdateFormItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $base_rules = [
            'item_type' => ['required', 'integer'],
            'item_title' => ['nullable', 'string', 'max:50'],
            'field_required' => ['nullable', 'integer', 'in:0,1'],
            'annotation_text' => ['nullable', 'string', 'max:5000'],
        ];
        $base_rules = array_merge($base_rules, $this->makeRulesByItemType((int)$this->item_type));

        return $base_rules;
    }

    public function attributes(): array
    {
        return [
            'item_type' => '項目種別',
            'item_title' => '項目名',
            'field_required' => '必須',
        ];
    }

    /**
     * 項目毎の条件を返却する
     * @param int $item_type
     * @return array
     */
    private function makeRulesByItemType(int $item_type): array
    {
        return match($item_type) {
            FormItem::ITEM_TYPE_NAME => $this->makeRulesForName(),
            FormItem::ITEM_TYPE_KANA => $this->makeRulesForName(),
            FormItem::ITEM_TYPE_EMAIL => $this->makeRulesForEmail(),
            FormItem::ITEM_TYPE_TEL => $this->makeRulesForTel(),
            FormItem::ITEM_TYPE_GENDER => $this->makeRulesForGender(),
            FormItem::ITEM_TYPE_ADDRESS => $this->makeRulesForAddress(),
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeRulesForCheckbox(),
            FormItem::ITEM_TYPE_TERMS => $this->makeRulesForTerms(),
            default => [],
        };
    }

    /**
     * 名前の更新条件を作成する
     * @return array[]
     */
    private function makeRulesForName(): array
    {
        return [
            'name_type' => ['required', 'integer', 'in:1,2'],
        ];
    }

    /**
     * ヨミの更新条件を作成する
     * @return array[]
     */
    private function makeRulesForEmail(): array
    {
        return [
            'confirm_flg' => ['required', 'integer', 'in:1,2'],
        ];
    }

    /**
     * 電話番号の更新条件を作成する
     * @return array[]
     */
    private function makeRulesForTel(): array
    {
        return [
            'hyphen_flg' => ['required', 'integer', 'in:1,2'],
        ];
    }

    /**
     * 性別の更新条件を作成する
     * @return array[]
     */
    private function makeRulesForGender(): array
    {
        return [
            'gender_list' => ['required', 'array'],
            'gender_list.*' => ['required', 'integer', 'in:1,2,3,4'],
        ];
    }

    /**
     * 住所の更新条件を作成する
     * @return array[]
     */
    private function makeRulesForAddress(): array
    {
        return [
            'post_code_use_type' => [
                'required',
                'integer',
                Rule::in(array_keys(CommonConst::POST_CODE_USE_LIST)),
            ],
            'address_separate_type' => [
                'required',
                'integer',
                Rule::in(array_keys(CommonConst::ADDRESS_SEPARATE_LIST)),
            ],
        ];
    }

    /**
     * チェックボックスの更新条件を作成する
     * @return array[]
     */
    private function makeRulesForCheckbox(): array
    {
        return [
            'checkbox_list' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    // 入力された改行区切りテキストから空でない行を抽出
                    $lines = preg_split('/\r\n|\r|\n/', (string) $value) ?: [];
                    $option_list = array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
                    if (count($option_list) < 2) {
                        $fail('選択肢は2つ以上入力してください。');
                    }
                },
            ],
        ];
    }

    /**
     * 利用規約の更新条件を作成する
     * @return array
     */
    private function makeRulesForTerms(): array
    {
        return [
            'terms_text' => ['required', 'string', 'max:1000'],
            'consent_name' => ['nullable', 'string', 'max:50'],
        ];
    }
}

