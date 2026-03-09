<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $price_id
 */
class PlanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price_id' => [
                'required',
                'string',
                Rule::in([
                    config('services.stripe.plans.lite'),
                    config('services.stripe.plans.full'),
                ]),
            ],
        ];
    }

    public function withValidator($validator): void
    {
    }

    public function attributes(): array
    {
        return [
        ];
    }

}


