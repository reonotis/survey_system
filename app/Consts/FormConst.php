<?php

namespace App\Consts;

use App\Enums\ItemType;

class FormConst
{
    public const DEFAULT_ATTRIBUTE_TITLE = [
        ItemType::TEL->value => 'tel',
        ItemType::GENDER->value => 'gender',
        ItemType::TERMS->value => 'terms',
        ItemType::CHECKBOX->value => 'checkbox',
        ItemType::RADIO->value => 'radio',
    ];

    /**
     */
    public const ATTRIBUTE_LIST = [
        ItemType::NAME->value => [
            'sei' => '苗字',
            'mei' => '名前',
            'name' => 'お名前',
        ],
        ItemType::KANA->value => [
            'sei_kana' => 'ミョウジ',
            'mei_kana' => 'ナマエ',
            'kana' => 'ナマエ',
        ],
        ItemType::EMAIL->value => [
            'email.*' => 'メールアドレス',
            'email_confirm.*' => '確認用メールアドレス',
        ],
        ItemType::ADDRESS->value => [
            'zip21' => '郵便番号上3桁',
            'zip22' => '郵便番号下4桁',
            'pref21' => '都道府県',
            'address21' => '市区町村',
            'street21' => '住所',
            'address' => '住所',
        ],
    ];

}
