<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\MailTemplate;
use App\Repositories\MailTemplateRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MailTemplateService
{
    private MailTemplateRepository $mail_template_repository;

    /**
     * コンストラクタ
     * @param MailTemplateRepository $mail_template_repository
     */
    public function __construct(MailTemplateRepository $mail_template_repository)
    {
        $this->mail_template_repository = $mail_template_repository;
    }

    /**
     * @param int $id
     * @return MailTemplate|null
     */
    public function getById(int $id): ?MailTemplate
    {
        return MailTemplate::find($id);
    }

    /**
     * @param int $user_id
     * @return Collection<int, MailTemplate>
     */
    public function getMailTemplateList(int $user_id): Collection
    {
        $query = $this->mail_template_repository->getMailTemplateListQuery($user_id);

        return $query->get();
    }

    /**
     * @param int $user_id
     * @return Builder
     */
    public function getMailTemplateListQuery(int $user_id): Builder
    {
        return $this->mail_template_repository->getMailTemplateListQuery($user_id);
    }

    /**
     * @param MailTemplate $mail_template
     * @param array $param
     * @return bool
     */
    public function update(MailTemplate $mail_template, array $param)
    {
        return $this->mail_template_repository->update($mail_template, $param);
    }

    /**
     * @param MailTemplate $mail_template
     * @return bool
     */
    public function delete(MailTemplate $mail_template): bool
    {
        return $mail_template->delete();
    }
}

