<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class FormSettingRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'form_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'form_name' => 'フォーム管理名',
            'title' => 'タイトル',
        ];
    }
}


