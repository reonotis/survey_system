<?php

declare(strict_types=1);

namespace App\Http\Requests\User\ItemSetting;

use App\Consts\PlanConst;
use App\Http\Requests\ApiRequest;
use App\Models\FormSetting;
use App\Service\PlanService;

/**
 * @property int|string $item_type
 */
class AddDraftItemRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_type' => ['required', 'integer'],
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

            /** @var PlanService $plan_service */
            $plan_service = app(PlanService::class);
            $form_plan = $plan_service->getPlanByUser($form_setting->owner_user);

            // このフォームがのオーナーが無料プランの場合は5件までしか設定できないようにする
            if ($form_plan == PlanConst::FREE_PLAN) {
                $now_count = $form_setting->draftFormItems->count();
                if ($now_count >= PlanConst::UPPER_FORM_ITEM_COUNT) {
                    $validator->errors()->add('item_id', 'このフォームは' . PlanConst::UPPER_FORM_ITEM_COUNT . 'つまでしか項目を設定できません');
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


