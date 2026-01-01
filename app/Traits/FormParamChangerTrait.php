<?php

namespace App\Traits;

use App\Consts\CommonConst;

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
        if ((int)$details['name_separate_type'] === CommonConst::NAME_SEPARATE) {
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
     * カナ入力のパラメータを作成する
     * @param array $details
     * @param array $request_data
     * @return array
     */
    private function makeParamForKana(array $details, array $request_data): array
    {
        if ((int)$details['kana_separate_type'] === CommonConst::KANA_SEPARATE) {
            return [
                'kana' => $request_data['sei_kana'] ?? null,
                'kana_last' => $request_data['mei_kana'] ?? null,
            ];
        }

        return [
            'kana' => $request_data['kana'] ?? null,
        ];
    }

    /**
     * メールアドレス入力のパラメータを作成する
     * @param array $request_data
     * @return array
     */
    private function makeParamForEmail(array $request_data): array
    {
        return [
            'email' => $request_data['email'] ?? null,
        ];
    }

    /**
     * 電話番号入力のパラメータを作成する
     * @param array $request_data
     * @return array
     */
    private function makeParamForTel(array $request_data): array
    {
        return [
            'tel' => $request_data['tel'] ?? null,
        ];
    }

    /**
     * 性別入力のパラメータを作成する
     * @param array $request_data
     * @return array
     */
    private function makeParamForGender(array $request_data): array
    {
        return [
            'gender' => $request_data['gender'] ?? null,
        ];
    }

    /**
     * 住所入力のパラメータを作成する
     * @param array $details
     * @param array $request_data
     * @return array
     */
    private function makeParamForAddress(array $details, array $request_data): array
    {
        // 郵便番号
        $post_code = null;
        if ($details['use_post_code_type'] == CommonConst::POST_CODE_DISABLED) {
            $zip21 = $request_data['zip21'] ?? '';
            $zip22 = $request_data['zip22'] ?? '';
            $post_code = $zip21 . '-' . $zip22;
        }

        // 住所
        if ($details['address_separate_type'] == CommonConst::ADDRESS_NON_SEPARATE) {
            $address = $request_data['address'] ?? '';
        } else {
            $address = $request_data['pref21'] . ' ' . $request_data['address21'] . ' ' . $request_data['street21'] ?? '';
        }

        return [
            'post_code' => $post_code,
            'address' => $address,
        ];
    }

}
