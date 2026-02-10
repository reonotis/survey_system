<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FormSetting;

class FormSettingRepository
{
    /**
     * @param FormSetting $form_setting
     * @return bool
     */
    public function delete(FormSetting $form_setting): bool
    {
        return $form_setting->delete();
    }
}
