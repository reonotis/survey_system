<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('status') && !$this->has('publication_status')) {
            $this->merge([
                'publication_status' => $this->input('status'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'form_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'route_name' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'publication_status' => ['required', 'in:0,1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'form_name' => 'フォーム管理名',
            'title' => 'タイトル',
            'route_name' => 'ルーティング名',
            'start_date' => '開始日時',
            'end_date' => '終了日時',
            'publication_status' => '状態',
        ];
    }
}


