<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MailTemplate;
use Illuminate\Database\Eloquent\Collection;

class MailTemplateRepository
{


    public function update(MailTemplate $mail_template, array $param)
    {
        return $mail_template->update($param);
    }


}
