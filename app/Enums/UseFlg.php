<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum UseFlg: int
{
    use EnumTraits;

    case USE_TYPE_DISABLED = 0; // 利用しない
    case USE_TYPE_ENABLED = 1; // 利用する

    /**
     * 日本語表示名
     */
    public function label(): string
    {
        return match ($this) {
            self::USE_TYPE_DISABLED => '利用しない',
            self::USE_TYPE_ENABLED => '利用する',
        };
    }

}
