<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumTraits
{
    public static function options(): array
    {
        return array_map(
            fn(self $case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
}
