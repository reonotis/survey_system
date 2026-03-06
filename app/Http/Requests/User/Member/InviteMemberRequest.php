<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Member;

use App\Models\FormSetting;
use App\Repositories\FormSettingUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @property string $email
 */
class InviteMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // 基本のバリデーションでエラーがあれば追加チェックはしない
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $my_user = Auth::guard('user')->user();
            if ($my_user->email === $this->email) {
                $validator->errors()->add('email', '自分自身のメールアドレスを招待する事は出来ません。');
            }

            /** @var FormSetting $form_setting */
            $form_setting = $this->route('form_setting');

            $user = app(UserRepository::class)->getByEmail($this->email);
            if($user) {
                $exist_relation = app(FormSettingUserRepository::class)->checkRelation($user->id, $form_setting->id);

                if ($exist_relation) {
                    $validator->errors()->add('email', 'このメールアドレスは既に招待されています。');
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


