<?php

declare(strict_types=1);

namespace App\Consts;

class PlanConst
{
    public const FREE_PLAN = 'FREE';
    public const LITE_PLAN = 'LITE';
    public const FULL_PLAN = 'FULL';

    public const UPPER_INVITE_MEMBER_COUNT = [
        self::FREE_PLAN => 0,
        self::LITE_PLAN => 3,
        self::FULL_PLAN => null, // 上限なし
    ];

    public const UPPER_FORM_COUNT = [
        self::FREE_PLAN => 1,
        self::LITE_PLAN => 3,
        self::FULL_PLAN => null, // 上限なし
    ];
}
