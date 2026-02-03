<?php

namespace App\Http\Requests\Form;

use App\Consts\CommonConst;
use App\Consts\FormConst;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Repositories\ApplicationSubRepository;
use App\Service\FormSettingService;
use App\Service\ApplicationsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int|string $item_type
 */
class RegisterRequest extends FormRequest
{
    /**
     * 申込フォーム設定（ルート名から取得）
     */
    protected FormSetting $form_setting;

    /** @var ApplicationsService $application_service */
    private ApplicationsService $application_service;

    private array $selected_count = [];

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

        $this->application_service = app(ApplicationsService::class);
        $this->form_setting = app(FormSettingService::class)->getSurveyByRouteName($route_name);
        $this->form_setting->load('formItems');
    }

    /**
     * バリデーションルールを作成する
     */
    public function rules(): array
    {
        if ($this->application_service->checkMaxSetting($this->form_setting)) {
            $this->selected_count = $this->application_service->getSelectedCount($this->form_setting);
        }

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
            FormItem::ITEM_TYPE_NAME,
            FormItem::ITEM_TYPE_KANA,
            FormItem::ITEM_TYPE_EMAIL,
            FormItem::ITEM_TYPE_ADDRESS,
                => $this->makeAttributesForName($form_item),
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeAttributesForSelectionItem($form_item, 'checkbox_'),
            FormItem::ITEM_TYPE_RADIO => $this->makeAttributesForSelectionItem($form_item, 'radio_'),
            FormItem::ITEM_TYPE_SELECT_BOX => $this->makeAttributesForSelectionItem($form_item, 'selectbox_'),

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
     * チェックボックス、ラジオボタン、セレクトボックスの項目名を生成する
     * @param FormItem $form_item
     * @param string $type_name
     * @return array
     */
    private function makeAttributesForSelectionItem(FormItem $form_item, string $type_name): array
    {
        $request_key_name = $type_name . $form_item->id;
        $item_title_name = ($form_item->item_title) ?? FormItem::ITEM_TYPE_LIST[$form_item->item_type];

        return  [
            $request_key_name => $item_title_name,
            $request_key_name . '.*' => $item_title_name,
        ];
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
            FormItem::ITEM_TYPE_GENDER => $this->makeRulesForGender($form_item),
            FormItem::ITEM_TYPE_ADDRESS => $this->makeRulesForAddress($form_item),
            FormItem::ITEM_TYPE_TERMS => $this->makeRulesForTerms($form_item),
            FormItem::ITEM_TYPE_CHECKBOX => $this->makeRulesForCheckbox($form_item),
            FormItem::ITEM_TYPE_RADIO => $this->makeRulesForRadio($form_item),
            FormItem::ITEM_TYPE_SELECT_BOX => [], //TODO
            default => [],
        };
    }

    /**
     * お名前のバリデーション
     * @return array[]
     */
    private function makeRulesForName(FormItem $form_item): array
    {
        $name_separate_type = $form_item->details['name_separate_type'] ?? CommonConst::NAME_SEPARATE;

        $validates = [];
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        if ($name_separate_type == CommonConst::NAME_SEPARATE) {
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
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForKana(FormItem $form_item): array
    {
        $kana_separate_type = $form_item->details['name_separate_type'] ?? CommonConst::KANA_SEPARATE;

        $validates = [];
        $validates[] = ($form_item->field_required) ? 'required' : 'nullable';
        // 全角カナ（長音記号含む）のみ許可
        $validates[] = 'regex:/\A[ァ-ヶー]+\z/u';

        if ($kana_separate_type == CommonConst::KANA_SEPARATE) {
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
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForEmail(FormItem $form_item): array
    {
        $rules = [];

        // email 用
        $email_rules = [
            $form_item->field_required ? 'required' : 'nullable',
            'string',
            'email',
        ];

        $rules['email'] = $email_rules;

        // confirm 設定の判定
        $confirm_type = $form_item->details['confirm_type'] ?? CommonConst::EMAIL_CONFIRM_DISABLED;

        if ($confirm_type == CommonConst::EMAIL_CONFIRM_ENABLED) {

            $confirm_rules = [
                $form_item->field_required ? 'required' : 'nullable',
                'string',
                'email',
                'same:email',
            ];

            $rules['email_confirm'] = $confirm_rules;
        }

        return $rules;
    }

    /**
     * 電話番号のバリデーション
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForTel(FormItem $form_item): array
    {
        $hyphen_flg = $form_item->details['hyphen_flg'] ?? 1;

        $validates = [];
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        if ($hyphen_flg == 1) { // TODO ハイフンが必要か

        }

        return [
            'tel' => $validates,
        ];
    }

    /**
     * 住所のバリデーション
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForAddress(FormItem $form_item): array
    {
        $details = $form_item->details;
        $post_code_use_type = $details['post_code_use_type'] ?? CommonConst::POST_CODE_DISABLED;
        $address_separate_type = $details['address_separate_type'] ?? 1;

        $roles = [
        ];

        if ($form_item->field_required) {
            if ($post_code_use_type == CommonConst::POST_CODE_ENABLED) {
                $roles['zip21'][] = 'required';
                $roles['zip22'][] = 'required';
            }

            if ($address_separate_type == CommonConst::ADDRESS_SEPARATE) {
                $roles['pref21'][] = 'required';
                $roles['address21'][] = 'required';
                $roles['street21'][] = 'required';
            } else {
                $roles['address'][] = 'required';
            }
        }

        return $roles;
    }

    /**
     * 性別のバリデーション
     * @param FormItem $form_item
     * @return array
     */
    private function makeRulesForGender(FormItem $form_item): array
    {
        $details = $form_item->details;
        $name_type = $details['gender_list'];

        $roles = [];

        if ($form_item->field_required) {
            $roles['gender'][] = 'required';
        }

        $roles['gender'][] = 'in:' . implode(',', $name_type);

        return $roles;
    }

    /**
     * 利用規約のバリデーション
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForTerms(FormItem $form_item): array
    {
        $validates = [];
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        return [
            'terms' => $validates,
        ];
    }

    /**
     * チェックボックスのバリデーション
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForCheckbox(FormItem $form_item): array
    {
        $request_key_name = 'checkbox_' . $form_item->id;
        $validates = [];

        // 必須の場合
        if ($form_item->field_required) {
            $validates[] = 'required';
        }

        // 配列である事
        $validates[] = 'array';

        // 選択可能最大数が設定されている場合は、それ以上選択していない事
        if (isset($form_item->details['max_count'])) {
            $max_count = $form_item->details['max_count'];
            $validates[] = 'max:' . $max_count;
        }

        // 不正な値を選択していないか
        $selectable_values = $this->makeSelectableValueList($form_item);

        return [
            $request_key_name => $validates,
            $request_key_name . '.*' => ['string', Rule::in($selectable_values)],
        ];
    }

    /**
     * ラジオボタンのバリデーション
     * @param FormItem $form_item
     * @return array[]
     */
    private function makeRulesForRadio(FormItem $form_item): array
    {
        $request_key_name = 'radio_' . $form_item->id;
        $validates = [];

        if ($form_item->field_required) {
            $validates[] = 'required';
        }
        $validates[] = 'string';


        // 不正な値を選択していないか
        $selectable_values = $this->makeSelectableValueList($form_item);
        $validates[] = Rule::in($selectable_values);

        return [
            $request_key_name => $validates,
        ];
    }

    /**
     * 選択可能な項目を取得する
     * @param FormItem $form_item
     * @return array
     */
    private function makeSelectableValueList(FormItem $form_item): array
    {
        $form_item_id = $form_item->id;

        $selected_count_list = $this->selected_count[$form_item_id]?? [];

        $selectable_values = [];
        foreach ($form_item->value_list as $value_list) {
            $name = $value_list['name'];
            $selectable_count = $value_list['count'];
            $selected_count = $selected_count_list[$name] ?? 0;

            if (
                is_null($selectable_count) // 項目別上限値 が設定されていない
                ||
                $selected_count < $selectable_count  // 項目別上限値 に達していない
            ) {
                $selectable_values[] = $name;
            }
        }

        return $selectable_values;
    }
}

