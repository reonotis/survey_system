<?php

namespace App\Http\Requests\Form;

use App\Consts\FormConst;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int|string $item_type
 */
class RegisterRequest extends FormRequest
{
    /**
     * 申込フォーム設定（ルート名から取得）
     */
    protected FormSetting $form_setting;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーション前に共通の取得処理を実行
     */
    protected function prepareForValidation(): void
    {
        $route_name = (string)$this->route('route_name');
        $this->form_setting = app(FormSettingService::class)->getSurveyByRouteName($route_name);
    }

    /**
     * バリデーションルールを作成する
     */
    public function rules(): array
    {
        $rules = [];
        // 設定されている項目毎のバリデーションを作成する
        foreach ($this->form_setting->formItems as $form_item) {
            $rules = array_merge($rules, $this->makeRulesByItemType($form_item));
        }

        return $rules;
    }

    /**
     * 項目名を返却する
     */
    public function attributes(): array
    {
        $item_title_list = [];
        foreach ($this->form_setting->formItems as $form_item) {
            $item_title_list = array_merge($item_title_list, $this->makeAttributesByItemType($form_item));
        }

        return $item_title_list;
    }

    /**
     * 項目毎のattributeを返却する
     * @param FormItem $form_item
     * @return array
     */
    private function makeAttributesByItemType(FormItem $form_item): array
    {
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_NAME, FormItem::ITEM_TYPE_KANA => $this->makeAttributesForName($form_item),
            default => $this->makeAttributesDefault($form_item),
        };
    }

    /**
     * お名前のattributeを返却する
     * @param FormItem $form_item
     * @return array
     */
    private function makeAttributesForName(FormItem $form_item): array
    {
        return FormConst::ATTRIBUTE_LIST[$form_item->item_type];
    }

    /**
     * 基本となるattributeを返却する
     * @param FormItem $form_item
     * @return array
     */
    private function makeAttributesDefault(FormItem $form_item): array
    {
        $item_title_name = ($form_item->item_title) ?? FormItem::ITEM_TYPE_LIST[$form_item->item_type];
        $item_title_list[FormConst::DEFAULT_ATTRIBUTE_TITLE[$form_item->item_type]] = $item_title_name;

        return $item_title_list;
    }

    /**
     * 項目毎の条件を返却する
     * @param FormItem $form_item
     * @return array
     */
    private function makeRulesByItemType(FormItem $form_item): array
    {
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_NAME => $this->makeRulesForName($form_item),
            FormItem::ITEM_TYPE_KANA => $this->makeRulesForKana($form_item),
            FormItem::ITEM_TYPE_EMAIL => $this->makeRulesForEmail($form_item),
            FormItem::ITEM_TYPE_TEL => $this->makeRulesForTel($form_item),
            default => [],
        };
    }

    /**
     * お名前のバリデーション
     * @return array[]
     */
    private function makeRulesForName($form_item): array
    {
        $details = json_decode($form_item->details ?? '{}', true);
        $name_type = $details['name_type'] ?? 1;

        $validates = [];
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        if ($name_type == 1) {
            return [
                'sei' => $validates,
                'mei' => $validates,
            ];
        }

        return [
            'name' => $validates,
        ];
    }

    /**
     * ヨミのバリデーション
     * @return array[]
     */
    private function makeRulesForKana($form_item): array
    {
        $details = json_decode($form_item->details ?? '{}', true);
        $name_type = $details['name_type'] ?? 1;

        $validates = [];
        $validates[] = ($form_item->field_required) ? 'required' : 'nullable';
        // 全角カナ（長音記号含む）のみ許可
        $validates[] = 'regex:/\A[ァ-ヶー]+\z/u';

        if ($name_type == 1) {
            return [
                'sei_kana' => $validates,
                'mei_kana' => $validates,
            ];
        }

        return [
            'kana' => $validates,
        ];
    }

    /**
     * メールアドレスのバリデーション
     * @return array[]
     */
    private function makeRulesForEmail($form_item): array
    {
        $validates = [];
        $validates[] = ($form_item->field_required) ? 'required' : 'nullable';
        $validates[] = 'string';
        $validates[] = 'email';

        $rules = [
            'email' => $validates,
        ];

        // 確認入力が有効な場合、同一確認のルールを追加
        $details = json_decode($form_item->details ?? '{}', true);
        $confirm_flg = $details['confirm_flg'] ?? 1;
        if ($confirm_flg == 1) {
            $rules['email_confirm'] = ['same:email'];
        }

        return $rules;
    }

    /**
     * 電話番号のバリデーション
     * @return array[]
     */
    private function makeRulesForTel($form_item): array
    {
        $details = json_decode($form_item->details ?? '{}', true);
        $hyphen_flg = $details['hyphen_flg'] ?? 1;

        $validates = [];
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        if ($hyphen_flg == 1) { // ハイフンが必要か

        }

        return [
            'tel' => $validates,
        ];
    }

}
