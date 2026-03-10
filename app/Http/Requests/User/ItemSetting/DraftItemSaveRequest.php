<?php

declare(strict_types=1);

namespace App\Http\Requests\User\ItemSetting;

use App\Http\Requests\ApiRequest;
use App\Models\FormSetting;
use App\Repositories\FormItemRepository;

/**
 * @property int $item_id
 * @property string $key
 * @property string $value
 */
class DraftItemSaveRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer'],
            'key' => ['required'],
            'value' => ['required'],
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


