<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Enums\PublicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property string $title
 * @property ?string $start_date
 * @property ?string $end_date
 * @property PublicationStatus $publication_status
 * @property ?int $max_applications
 *
 */
class BasicSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:50'],
            'start_date' => ['nullable', 'date',],
            'end_date' => ['nullable', 'date', 'gte:start_date',],
            'publication_status' => ['required', 'integer', new Enum(PublicationStatus::class),],
            'max_applications' => ['nullable', 'integer', 'min:1', 'max:100',],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'フォームタイトル',
        ];
    }

}


