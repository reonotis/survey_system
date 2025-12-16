<?php

namespace App\Http\Requests\Owner;

use App\Models\FormItem;
use App\Consts\CommonConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

/**
 * @property int|string $item_type
 * @property string $target_key
 * @property string $target_value
 */
class UpdateFormItemReactRequest extends FormRequest
{
    /**
     * レコードを更新するためのカラムを特定するキー
     * @var string|null
     */
    public ?string $target_key = null;

    /**
     * レコードを更新する際の値
     * @var string|null
     */
    public ?string $target_value = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // _tokenとitem_typeを除いたリクエストデータから最初の項目を取得してプロパティに保存
        $data = $this->except(['_token', 'item_type']);
        $this->target_key = array_key_first($data);
        $this->target_value = $this->target_key !== null ? $data[$this->target_key] : null;

        $base_rules = [];
        $base_rules = array_merge($base_rules, $this->makeRulesByItemType((int)$this->item_type));

        return $base_rules;
    }

    /**
     * バリデーション前にform_settingとform_itemの関連性をチェック
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // ルーティングパラメータを取得
            $form_setting = $this->route('form_setting');
            $form_item = $this->route('form_item');

            // form_settingがモデルインスタンスの場合はidを取得、そうでない場合はそのまま使用
            $form_setting_id = is_object($form_setting) ? $form_setting->id : $form_setting;

            // form_itemのform_setting_idと一致しない場合はエラー
            if ($form_item && $form_item->form_setting_id != $form_setting_id) {
                $validator->errors()->add(
                    'form_item',
                    '指定されたフォーム項目は、このフォーム設定に属していません。'
                );
            }
        });
    }

    public function attributes(): array
    {
        return [
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
            FormItem::ITEM_TYPE_EMAIL => $this->makeRulesForEmail(),
            default => [],
        };
    }

    /**
     * メールアドレスの更新条件を作成する
     * @return array[]
     */
    private function makeRulesForEmail(): array
    {
        return [
        ];
    }

}

