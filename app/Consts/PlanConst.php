<?php

declare(strict_types=1);

namespace App\Consts;

class PlanConst
{
    public const FREE_PLAN = 'FREE';
    public const LITE_PLAN = 'LITE';
    public const FULL_PLAN = 'FULL';

    // プラン毎に招待できるメンバーの上限人数
    public const UPPER_INVITE_MEMBER_COUNT = [
        self::FREE_PLAN => 0,
        self::LITE_PLAN => 3,
        self::FULL_PLAN => null, // 上限なし
    ];

    // プラン毎に作成できるフォームの最大数
    public const UPPER_FORM_COUNT = [
        self::FREE_PLAN => 1,
        self::LITE_PLAN => 3,
        self::FULL_PLAN => null, // 上限なし
    ];

    // プラン毎にフォームに設定できる項目の最大数
    public const UPPER_FORM_ITEM_COUNT = 5;

}
