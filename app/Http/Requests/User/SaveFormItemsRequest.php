<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Consts\PlanConst;
use App\Models\FormSetting;
use App\Models\User;
use App\Service\PlanService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            $plan_service = app(PlanService::class);

            /** @var FormSetting $form_setting */
            $form_setting = $this->route('form_setting');
            $owner_plan = $plan_service->getPlanByUser($form_setting->owner_user);

            if ($owner_plan === PlanConst::FREE_PLAN) {
                if ($form_setting->draftFormItems->count() > 5) {
                    $validator->errors()->add('custom_error', 'このフォームのオーナーは ' . PlanConst::FREE_PLAN . 'プランの為、5項目までしか設定できません');
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


