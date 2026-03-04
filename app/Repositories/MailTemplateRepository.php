<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MailTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MailTemplateRepository
{

    /**
     * @param int $user_id
     * @return Builder
     */
    public function getMailTemplateListQuery(int $user_id): Builder
    {
        $select = [
            '*',
        ];

        $query = MailTemplate::select($select)
            ->where('created_by', $user_id);

        return $query;
    }


    public function update(MailTemplate $mail_template, array $param)
    {
        return $mail_template->update($param);
    }


}
