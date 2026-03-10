<?php

declare(strict_types=1);

namespace App\Http\Requests\User\ItemSetting;

use App\Http\Requests\ApiRequest;

/**
 * @property array $item_ids
 */
class SortChangeRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_ids' => ['required', 'array'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
        });
    }

    public function attributes(): array
    {
        return [
        ];
    }

}


