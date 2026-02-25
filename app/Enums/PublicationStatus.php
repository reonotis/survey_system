<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum PublicationStatus: int
{
    use EnumTraits;

    case PRIVATE = 0; // 非公開
    case PUBLIC = 1; // 公開

    /**
     * 日本語表示名
     */
    public function label(): string
    {
        return match ($this) {
            self::PRIVATE => '非公開',
            self::PUBLIC => '公開',
        };
    }

    /**
     * 非公開であるかを判定する
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this === self::PRIVATE;
    }

}
