<?php

namespace App\Traits;

use App\Consts\CommonConst;
use App\Models\FormItem;

trait FormReplaceTrait
{
    /**
     * @param FormItem $form_item
     * @param $request_data
     * @return array
     */
    public function makeReplaceData(FormItem $form_item, $request_data): array
    {
        return match ($form_item->item_type) {
            FormItem::ITEM_TYPE_NAME => $this->getReplaceDataByName($form_item, $request_data),
            FormItem::ITEM_TYPE_EMAIL => $this->getReplaceDataByEmail($request_data),
            default => [],
        };
    }

    /**
     * @param $form_item
     * @param $request_data
     * @return array
     */
    public function getReplaceDataByName($form_item, $request_data): array
    {
        $details = $form_item->details;
        $res = [];

        if ($details['name_separate_type'] == CommonConst::NAME_NON_SEPARATE) {
            if (!isset($request_data['name'])) {
                return $res;
            }
            $res['#####name#####'] = $request_data['name'];
            return $res;
        }

        if (!isset($request_data['sei']) || !isset($request_data['mei'])) {
            return $res;
        }

        $res['#####name#####'] = $request_data['sei'] . ' ' . $request_data['mei'];
        return $res;
    }

    /**
     * @param $request_data
     * @return array
     */
    public function getReplaceDataByEmail($request_data): array
    {
        $res = [];

        if (!isset($request_data['email'])) {
            return $res;
        }

        $res['#####email#####'] = $request_data['email'];
        return $res;
    }
}
