<?php

namespace App\Consts;

use App\Models\FormItem;

class FormConst
{
    public const DEFAULT_ATTRIBUTE_TITLE = [
        FormItem::ITEM_TYPE_EMAIL => 'email',
        FormItem::ITEM_TYPE_TEL => 'tel',
        FormItem::ITEM_TYPE_GENDER => 'sex',
        FormItem::ITEM_TYPE_TERMS => 'terms',
    ];

    /**
     */
    public const ATTRIBUTE_LIST = [
        FormItem::ITEM_TYPE_NAME => [
            'sei' => '苗字',
            'mei' => '名前',
            'name' => 'お名前',
        ],
        FormItem::ITEM_TYPE_KANA => [
            'sei_kana' => 'ミョウジ',
            'mei_kana' => 'ナマエ',
            'kana' => 'ナマエ',
        ],
        FormItem::ITEM_TYPE_EMAIL => [
            'email' => 'メールアドレス',
            'email_confirm' => '確認用メールアドレス',
        ]
    ];

}
