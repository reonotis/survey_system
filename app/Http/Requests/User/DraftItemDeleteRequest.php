<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\FormSetting;
use App\Http\Requests\ApiRequest;
use App\Repositories\FormItemRepository;

/**
 * @property int $item_id
 */
class DraftItemDeleteRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // 基本のバリデーションでエラーがあれば追加チェックはしない
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            /** @var FormSetting $form_setting */
            $form_setting = $this->route('form_setting');
            $form_setting_id = is_object($form_setting) ? $form_setting->id : $form_setting;

            /** @var FormItemRepository $formItemRepository */
            $formItemRepository = app(FormItemRepository::class);
            $form_item_draft = $formItemRepository->findDraftById($this->item_id);

            // レコードが存在しない、または form_setting_id が一致しない場合はエラー
            if (!$form_item_draft || $form_item_draft->form_setting_id !== $form_setting_id) {
                $validator->errors()->add('item_id', '不正な項目です。');
            }
        });
    }

    public function attributes(): array
    {
        return [
        ];
    }
}


