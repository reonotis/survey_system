<?php

namespace App\Traits;

use App\Consts\CommonConst;
use App\Models\FormItem;

trait FormParamChangerTrait
{
    /**
     * 名前入力のパラメータを作成する
     * @param array $details
     * @param array $request_data
     * @return array
     */
    private function makeParamForName(array $details, array $request_data): array
    {
        if ((int)$details['name_type'] === CommonConst::NAME_SEPARATE) {
            return [
                'name' => $request_data['sei'] ?? null,
                'name_last' => $request_data['mei'] ?? null,
            ];
        }

        return [
            'name' => $request_data['name'] ?? null,
        ];
    }

    /**
     * @param array $details
     * @param array $request_data
     * @return array
     */
    private function makeParamForKana(array $details, array $request_data): array
    {
        if ((int)$details['name_type'] === CommonConst::NAME_SEPARATE) {
            return [
                'kana' => $request_data['kana'] ?? null,
                'kana_last' => $request_data['kana_last'] ?? null,
            ];
        }

        return [
            'kana' => $request_data['kana'] ?? null,
        ];
    }

    /**
     * @param array $request_data
     * @return array
     */
    private function makeParamForEmail(array $request_data): array
    {
        return [
            'email' => $request_data['email'] ?? null,
        ];
    }

}
