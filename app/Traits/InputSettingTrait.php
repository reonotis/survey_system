<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait InputSettingTrait
{
    /**
     * @param Collection $collection
     * @param string|null $key
     * @param string|null $label
     * @return array
     */
    public function makeSectionItemsByCollection(Collection $collection, bool $require, string $label = null, string $key = null): array
    {
        $array = [];

        if (!$require) {
            $array[0] = '選択しない';
        }

        foreach ($collection as $item) {
            $key = $key ?? 'id';
            $label = $label ?? 'name';
            $array[$item->$key] = $item->$label;
        }

        return $array;
    }

}
