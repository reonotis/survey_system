<?php

namespace App\Service;

use App\Models\FormItem;
use App\Models\DisplayFormItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 *
 */
class DisplayFormItemService
{
    public function getByFormSettingId(int $form_setting_id)
    {
        return DisplayFormItem::where('form_setting_id', $form_setting_id)
            ->orderBy('id')
            ->get();
    }

    public function insert(array $record)
    {
        return DisplayFormItem::insert($record);
    }

    public function deleteByFormSettingId(int $form_setting_id)
    {
        return DisplayFormItem::where('form_setting_id', $form_setting_id)->delete();
    }

}

