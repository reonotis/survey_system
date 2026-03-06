<?php

declare(strict_types=1);

namespace App\Traits;

trait PasswordTrait
{
    /**
     */
    private function makeFirstPassword(): string
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $number = '0123456789';
        $symbol = '!@#$_=';

        // 必須1文字ずつ
        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $number[random_int(0, strlen($number) - 1)],
            $symbol[random_int(0, strlen($symbol) - 1)],
        ];

        // 全文字セット
        $all = $upper . $lower . $number . $symbol;

        // 残り8文字
        for ($i = 0; $i < 8; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // シャッフル
        shuffle($password);

        return implode('', $password);
    }

}
