<?php

namespace App\Consts;

class CommonConst
{
    // 利用可否
    public const USE_TYPE_DISABLED  = 0;
    public const USE_TYPE_ENABLED   = 1;
    public const USE_TYPE_LIST = [
        self::USE_TYPE_DISABLED => '利用しない',
        self::USE_TYPE_ENABLED   => '利用する',
    ];

    // 名前
    public const NAME_SEPARATE = 1;
    public const NAME_NON_SEPARATE = 2;
    public const NAME_SEPARATE_LIST = [
        self::NAME_SEPARATE => '姓名を分ける',
        self::NAME_NON_SEPARATE => '姓名を分けない',
    ];

    // 性別のリスト
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_OTHER = 3;
    public const GENDER_UNKNOWN = 4;
    public const GENDER_LIST = [
        self::GENDER_MALE => '男性',
        self::GENDER_FEMALE => '女性',
        self::GENDER_OTHER => 'その他',
        self::GENDER_UNKNOWN => '未回答',
    ];

}
