<?php

declare(strict_types=1);

namespace App\Service;

/**
 *
 */
class BrandService
{
    // 運用費用を頂いているサービス提供中のドメイン
    private static array $service_domain_list = [
         'localhost', // ローカル環境で確認する場合に、この localhost をコメントアウトしてください
        // ニューバランス等
    ];

    /**
     * サービス提供中のドメインかどうかを判定する
     * @return boolean
     */
    public static function isClientDomain(): bool
    {
        $host = request()->getHost();

        if (in_array($host, self::$service_domain_list)) {
            return true;
        }

        return false;
    }
}
