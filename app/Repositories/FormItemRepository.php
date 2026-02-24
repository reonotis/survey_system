<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FormItemDraft;

class FormItemRepository
{
    /**
     * 下書き項目を ID で取得する
     */
    public function findDraftById(int $id): ?FormItemDraft
    {
        return FormItemDraft::find($id);
    }
}
