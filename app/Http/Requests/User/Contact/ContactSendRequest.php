<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Contact;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $price_id
 */
class ContactSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => [ 'required','string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
    }

    public function attributes(): array
    {
        return [
            'message' => '問い合わせ内容',
        ];
    }

}


