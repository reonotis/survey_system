<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ApplicationSub;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ApplicationSubRepository
{
    /**
     * @param int $form_item_id
     * @return array
     */
    public function getSelectionsItemCount(int $form_item_id): array
    {
        return ApplicationSub::select('answer_text as labels', DB::raw('COUNT(*) as count'))
                ->where('form_item_id', $form_item_id)
                ->whereNotNull('answer_text')
                ->groupBy('answer_text')
                ->get()->toArray();
    }
}
