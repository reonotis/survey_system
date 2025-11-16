<?php

namespace App\Service;

use App\Models\FormSetting;
use App\Models\MessageSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FormMessageSettingService
{
    /**
     * @param array $param
     * @return MessageSetting
     */
    public function create(array $param): MessageSetting
    {
        return MessageSetting::create($param);
    }

    /**
     * @param MessageSetting $message_setting
     * @param array $param
     * @return bool
     */
    public function update(MessageSetting $message_setting, array $param)
    {
        return $message_setting->update($param);
    }

}











