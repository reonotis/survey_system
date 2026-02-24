<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\FormSetting;
use Illuminate\Foundation\Http\FormRequest;

/**
 */
class SaveFormItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var FormSetting $form_setting */
            $form_setting = $this->route('form_setting');

            if (!$form_setting->isPaid()) {
                if ($form_setting->draftFormItems->count() > 5) {
                    $validator->errors()->add('custom_error', '無料版では5項目までしか選択できません');
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
        ];
    }

}


