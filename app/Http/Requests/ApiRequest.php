<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiRequest extends FormRequest
{
    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * デフォルトのバリデーションエラーメッセージ
     * 各 Request で上書き可能
     */
    protected function validationErrorMessage(): string
    {
        return 'バリデーションエラー';
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $this->validationErrorMessage(),
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}

