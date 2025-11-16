<?php

namespace App\Service;

use App\Models\MailSetting;
use App\Models\MessageSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FormMailSettingService
{
    /**
     * @param array $param
     * @return MailSetting
     */
    public function create(array $param): MailSetting
    {
        return MailSetting::create($param);
    }

    /**
     * @param MailSetting $mail_message
     * @param array $param
     * @return bool
     */
    public function update(MailSetting $mail_message, array $param)
    {
        return $mail_message->update($param);
    }

}











