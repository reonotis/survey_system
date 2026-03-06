<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FormSettingUser;
use Illuminate\Database\Eloquent\Builder;

class FormSettingUserRepository
{
    /**
     * @param int $form_setting_id
     * @return Builder
     */
    public function makeGetListByFormQuery(int $form_setting_id): Builder
    {
        return FormSettingUser::select('users.id','users.name', 'users.email')
            ->where('form_setting_user.form_setting_id', $form_setting_id)
            ->join('users', 'users.id', '=', 'form_setting_user.user_id');
    }

    public function getMemberCount(int $form_setting_id)
    {
        return FormSettingUser::where('form_setting_user.form_setting_id', $form_setting_id)
            ->join('users', 'users.id', '=', 'form_setting_user.user_id')
            ->count();
    }

    /**
     * @param int $user_id
     * @param int $form_setting_id
     * @return bool
     */
    public function checkRelation(int $user_id, int $form_setting_id): bool
    {
        return FormSettingUser::where('user_id', $user_id)
            ->where('form_setting_id', $form_setting_id)
            ->exists();
    }

    /**
     * @param int $user_id
     * @param int $form_setting_id
     * @return FormSettingUser
     */
    public function createRelation(int $user_id, int $form_setting_id): FormSettingUser
    {
        return FormSettingUser::create([
            'user_id' => $user_id,
            'form_setting_id' => $form_setting_id,
        ]);
    }

    /**
     * @param int $form_setting_id
     * @param int $user_id
     * @return int
     */
    public function delete(int $form_setting_id, int $user_id): int
    {
        return FormSettingUser::where('user_id', $user_id)
            ->where('form_setting_id', $form_setting_id)
            ->delete();
    }


}
